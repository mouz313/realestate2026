<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\PortalAction;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $quotation = $client->quotations()->with('items')->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();
        $action = $quotation->portalActions()->latest()->first();

        return view('portal.quotations.show', compact('quotation', 'settings', 'action'));
    }

    public function approve($id)
    {
        $client = $this->getClient();
        $quotation = $client->quotations()->findOrFail($id);
        $quotation->update(['status' => 'approved']);
        PortalAction::create([
            'quotation_id' => $quotation->id,
            'client_id' => $client->id,
            'action' => 'approved',
        ]);

        return redirect()->route('portal.quotations.show', $quotation)->with('success', 'Quotation approved.');
    }

    public function reject($id)
    {
        $client = $this->getClient();
        $quotation = $client->quotations()->findOrFail($id);
        $quotation->update(['status' => 'rejected']);
        PortalAction::create([
            'quotation_id' => $quotation->id,
            'client_id' => $client->id,
            'action' => 'rejected',
        ]);

        return redirect()->route('portal.quotations.show', $quotation)->with('success', 'Quotation rejected.');
    }

    public function pdf($id)
    {
        $client = $this->getClient();
        $quotation = $client->quotations()->with('items')->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('quotations.pdf', compact('quotation', 'settings'));

        return $pdf->download('quotation-'.$quotation->quote_number.'.pdf');
    }

    private function getClient()
    {
        $client = Client::find(session('client_id'));
        abort_if(! $client, 401);

        return $client;
    }
}
