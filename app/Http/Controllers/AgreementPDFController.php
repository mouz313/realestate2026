<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Deal;
use App\Models\RentAgreement;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class AgreementPDFController extends Controller
{
    public function saleAgreement(Deal $deal)
    {
        $deal->load(['property', 'buyer', 'seller', 'agent']);
        $settings = Setting::pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('pdf.sale-agreement', compact('deal', 'settings'));

        return $pdf->download('sale-agreement-'.$deal->deal_number.'.pdf');
    }

    public function rentAgreement(RentAgreement $rentAgreement)
    {
        $rentAgreement->load(['property', 'tenant', 'owner', 'deal.agent']);
        $settings = Setting::pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('pdf.rent-agreement', compact('rentAgreement', 'settings'));

        return $pdf->download('rent-agreement-'.$rentAgreement->id.'.pdf');
    }

    public function tokenReceipt(Deal $deal)
    {
        $deal->load(['property', 'buyer', 'seller', 'tokens']);
        $settings = Setting::pluck('value', 'key')->toArray();
        $token = $deal->tokens()->first();
        if (! $token) {
            toastr()->error('No token found for this deal.');

            return back();
        }
        $pdf = Pdf::loadView('pdf.token-receipt', compact('deal', 'token', 'settings'));

        return $pdf->download('token-receipt-'.$deal->deal_number.'.pdf');
    }

    public function commissionInvoice(Commission $commission)
    {
        $commission->load(['deal.property', 'agent']);
        $settings = Setting::pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('pdf.commission-invoice', compact('commission', 'settings'));

        return $pdf->download('commission-'.$commission->id.'.pdf');
    }

    public function possessionLetter(Deal $deal)
    {
        $deal->load(['property', 'buyer', 'seller', 'agent']);
        $settings = Setting::pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('pdf.possession-letter', compact('deal', 'settings'));

        return $pdf->download('possession-letter-'.$deal->deal_number.'.pdf');
    }
}
