<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(15);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        Client::create($data);
        toastr()->success('Client added successfully.');

        return redirect()->route('clients.index');
    }

    public function show(Client $client)
    {
        $client->load(['quotations' => fn ($q) => $q->latest()->limit(10), 'invoices' => fn ($i) => $i->latest()->limit(10)]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $client->update($data);
        toastr()->success('Client updated successfully.');

        return redirect()->route('clients.index');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        toastr()->success('Client deleted successfully.');

        return redirect()->route('clients.index');
    }
}
