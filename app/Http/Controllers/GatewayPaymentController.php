<?php

namespace App\Http\Controllers;

use App\Models\GatewayPayment;
use App\Models\Invoice;
use App\Models\Installment;
use App\Models\RentPayment;
use App\Models\Token;
use App\Services\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GatewayPaymentController extends Controller
{
    public function create(Request $request, PaymentGateway $gatewayService)
    {
        $request->validate([
            'gateway' => 'required|string|in:jazzcash,easypaisa,raast',
            'amount' => 'required|numeric|min:1',
            'payable_type' => 'required|string|in:invoice,rent_payment,token,installment',
            'payable_id' => 'required|integer|min:1',
        ]);

        $payable = $this->resolvePayable($request);

        $driver = $gatewayService->for($request->gateway);

        if (! $driver->configured()) {
            return back()->withErrors(['gateway' => ucfirst($request->gateway).' is not configured.']);
        }

        $reference = $this->referenceFor($payable, $request->payable_type);

        $order = $driver->createOrder(
            (float) $request->amount,
            $reference,
            $this->descriptionFor($payable, $request->payable_type),
        );

        $record = DB::transaction(function () use ($payable, $request, $order, $reference) {
            return GatewayPayment::create([
                'company_id' => $payable->company_id ?? current_company_id(),
                'gateway' => $request->gateway,
                'recipient_type' => $payable->getMorphClass(),
                'recipient_id' => $payable->id,
                'currency' => $order['currency'] ?? 'PKR',
                'amount' => $request->amount,
                'order_id' => $order['order_id'] ?? Str::uuid(),
                'reference' => $reference,
                'status' => GatewayPayment::STATUS_PENDING,
                'payload' => $order,
                'created_by' => auth()->id(),
            ]);
        });

        if (! empty($order['redirect_url'])) {
            return redirect($order['redirect_url']);
        }

        return redirect()->route('admin.dashboard')->with('info', 'Payment link generated.');
    }

    public function callback(Request $request, PaymentGateway $gatewayService, string $gateway)
    {
        $orderId = $this->extractOrderId($request);

        $driver = $gatewayService->for($gateway);
        $verified = $driver->verify((string) $orderId);

        return $this->handleResponse($request, $gateway, $orderId, $verified, true);
    }

    public function return(Request $request, PaymentGateway $gatewayService, string $gateway)
    {
        $orderId = $this->extractOrderId($request);

        $driver = $gatewayService->for($gateway);
        $verified = $gatewayService->supportsOnlineConfirmation($gateway)
            ? $driver->verify((string) $orderId)
            : false;

        return $this->handleResponse($request, $gateway, $orderId, $verified, false);
    }

    protected function handleResponse(
        Request $request,
        string $gateway,
        string $orderId,
        bool $verified,
        bool $serverCallback,
    ) {
        $record = GatewayPayment::where('gateway', $gateway)
            ->where('order_id', $orderId)
            ->lockForUpdate()
            ->first();

        if (! $record) {
            return $serverCallback ? response('ok', 200) : back()->withErrors(['gateway' => 'Order not found.']);
        }

        if ($record->isPaid()) {
            return $serverCallback ? response('ok', 200) : $this->redirectAfter($record, 'Payment already confirmed.');
        }

        $onlineConfirmed = $verified || ! app(PaymentGateway::class)->supportsOnlineConfirmation($gateway);

        $record->forceFill([
            'status' => $onlineConfirmed ? GatewayPayment::STATUS_PAID : GatewayPayment::STATUS_PENDING,
            'paid_at' => $onlineConfirmed ? now() : null,
            'payload' => array_merge((array) $record->payload, $request->only([
                'txnType', 'responseCode', 'pp_PaymentStatus', 'paymentStatus', 'status', 'orderId',
            ])),
        ])->save();

        if ($onlineConfirmed) {
            $this->reconcileRecipient($record);
        }

        if ($serverCallback) {
            return response('ok', 200);
        }

        return $this->redirectAfter($record, $onlineConfirmed
            ? 'Payment confirmed successfully.'
            : 'Payment submitted. We will confirm shortly.');
    }

    protected function reconcileRecipient(GatewayPayment $record): void
    {
        $recipient = $record->recipient;

        if ($recipient instanceof Invoice) {
            $newPaid = ($recipient->paid_amount + $record->amount);
            $recipient->update([
                'paid_amount' => $newPaid,
                'payment_status' => $newPaid >= $recipient->total ? 'paid'
                    : ($newPaid > 0 ? 'partial' : 'pending'),
            ]);
        } elseif ($recipient instanceof RentPayment) {
            $recipient->update([
                'status' => 'paid',
                'paid_date' => now(),
                'payment_method' => 'online',
                'reference_no' => $record->order_id,
            ]);
        } elseif ($recipient instanceof Token) {
            $recipient->update([
                'status' => $recipient->status === 'pending' ? 'received' : $recipient->status,
                'payment_method' => 'online',
            ]);
        } elseif ($recipient instanceof Installment) {
            $recipient->update([
                'status' => 'paid',
                'paid_date' => now(),
                'payment_method' => 'online',
                'reference_no' => $record->order_id,
            ]);
        }
    }

    protected function resolvePayable(Request $request)
    {
        return match ($request->payable_type) {
            'invoice' => Invoice::with('client')->findOrFail($request->payable_id),
            'rent_payment' => RentPayment::with('rentAgreement')->findOrFail($request->payable_id),
            'token' => Token::with('deal')->findOrFail($request->payable_id),
            'installment' => Installment::with('plan')->findOrFail($request->payable_id),
            default => abort(400, 'Invalid payable type.'),
        };
    }

    protected function referenceFor(object $payable, string $type): string
    {
        return match ($type) {
            'invoice' => 'INV-'.$payable->invoice_number,
            'rent_payment' => 'RP-'.$payable->id.'-'.$payable->month.'/'.$payable->year,
            'token' => 'TKN-'.$payable->id,
            'installment' => 'INST-'.$payable->id,
            default => 'PAY-'.$payable->id,
        };
    }

    protected function descriptionFor(object $payable, string $type): string
    {
        return match ($type) {
            'invoice' => 'Invoice '.$payable->invoice_number.' payment',
            'rent_payment' => 'Rent payment for '.($payable->month_name ?? 'N/A'),
            'token' => 'Token payment',
            'installment' => 'Installment payment',
            default => 'Agency payment',
        };
    }

    protected function extractOrderId(Request $request): string
    {
        $orderId = $request->filled('order_id') ? $request->input('order_id')
            : ($request->filled('pp_TxnRefID') ? $request->input('pp_TxnRefID') : $request->input('orderId'));

        return (string) $orderId;
    }

    protected function redirectAfter(GatewayPayment $record, string $message)
    {
        $recipient = $record->recipient;

        if ($recipient instanceof Invoice) {
            return redirect()->route('invoices.show', $recipient)->with('success', $message);
        }

        if ($recipient instanceof RentPayment) {
            return redirect()->route('rent-payments.show', $recipient->rent_agreement_id)->with('success', $message);
        }

        return redirect()->route('admin.dashboard')->with('success', $message);
    }
}
