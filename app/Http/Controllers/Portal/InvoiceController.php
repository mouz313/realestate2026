<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $client = $this->getClient();
        $invoices = $client->invoices()->with('payments')->latest()->paginate(12);
        return view('portal.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $client = $this->getClient();
        $invoice = $client->invoices()->with('items', 'payments')->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('portal.invoices.show', compact('invoice', 'settings'));
    }

    public function pdf($id)
    {
        $client = $this->getClient();
        $invoice = $client->invoices()->with('items')->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'settings'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    private function getClient()
    {
        $client = \App\Models\Client::find(session('client_id'));
        abort_if(!$client, 401);
        return $client;
    }
}
