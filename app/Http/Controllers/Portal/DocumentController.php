<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientDocument;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $client = $this->getClient();
        $documents = $client->documents()->latest()->get();

        return view('portal.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('portal.documents.create');
    }

    public function store(Request $request)
    {
        $client = $this->getClient();

        $request->validate([
            'document_type' => 'required|string|max:50',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
        ]);

        $path = $request->file('file')->store('client-documents/'.$client->id, 'public');

        ClientDocument::create([
            'client_id' => $client->id,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'notes' => $request->notes,
        ]);

        return redirect()->route('portal.documents.index')->with('success', 'Document uploaded.');
    }

    public function destroy($id)
    {
        $client = $this->getClient();
        $document = $client->documents()->findOrFail($id);

        $filePath = storage_path('app/public/'.$document->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $document->delete();

        return back()->with('success', 'Document deleted.');
    }

    private function getClient()
    {
        $client = Client::find(session('client_id'));
        abort_if(! $client, 401);

        return $client;
    }
}
