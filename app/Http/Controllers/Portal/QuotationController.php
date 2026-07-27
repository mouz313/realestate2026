<?php

namespace App\Http\Controllers\Portal;

use App\Helpers\QrHelper;
use App\Http\Controllers\Controller;
use App\Mail\ClientActionMail;
use App\Mail\MailSettings;
use App\Models\Client;
use App\Models\PortalAction;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class QuotationController extends Controller
{
    public function index()
    {
        $client = $this->getClient();
        $quotations = $client->quotations()->latest()->paginate(12);

        return view('portal.quotations.index', compact('quotations'));
    }

    public function show($id)
    {
        $client = $this->getClient();
        $quotation = $client->quotations()->with('items', 'property')->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();
        $action = $quotation->portalActions()->latest()->first();

        return view('portal.quotations.show', compact('quotation', 'settings', 'action'));
    }

    public function approve($id)
    {
        $client = $this->getClient();
        $quotation = $client->quotations()->findOrFail($id);

        $quotation->update(['status' => 'approved']);

        $action = PortalAction::create([
            'quotation_id' => $quotation->id,
            'client_id' => $client->id,
            'action' => 'approved',
            'notes' => request('notes'),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'signed_name' => $client->name,
        ]);

        $this->notifyAdmin($action);

        return redirect()->route('portal.quotations.show', $quotation)->with('success', 'Quotation approved successfully.');
    }

    public function reject($id)
    {
        $client = $this->getClient();
        $quotation = $client->quotations()->findOrFail($id);

        $quotation->update(['status' => 'rejected']);

        $action = PortalAction::create([
            'quotation_id' => $quotation->id,
            'client_id' => $client->id,
            'action' => 'rejected',
            'notes' => request('notes'),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'signed_name' => $client->name,
        ]);

        $this->notifyAdmin($action);

        return redirect()->route('portal.quotations.show', $quotation)->with('success', 'Quotation rejected.');
    }

    public function pdf($id)
    {
        $client = $this->getClient();
        $quotation = $client->quotations()->with('items', 'property')->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();

        $qrCode = QrHelper::pngDataUri(route('portal.quotations.show', $quotation));
        $pdf = Pdf::loadView('quotations.pdf', compact('quotation', 'settings', 'qrCode'));

        return $pdf->download('quotation-'.$quotation->quote_number.'.pdf');
    }

    private function notifyAdmin(PortalAction $action): void
    {
        $adminEmail = config('app.admin_email');
        if (! $adminEmail) {
            return;
        }

        try {
            MailSettings::apply();
            Mail::to($adminEmail)->send(new ClientActionMail($action, $adminEmail));
        } catch (\Exception $e) {
            // Notification failure shouldn't block the action
        }
    }

    private function getClient()
    {
        $client = Client::find(session('client_id'));
        abort_if(! $client, 401);

        return $client;
    }
}
