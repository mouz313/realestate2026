<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('portal.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client || !$client->password || !Hash::check($request->password, $client->password)) {
            return back()->withErrors(['email' => 'Invalid credentials or no portal access.']);
        }

        session(['client_id' => $client->id, 'client_name' => $client->name]);
        $request->session()->regenerate();
        return redirect()->route('portal.quotations');
    }

    public function logout(Request $request)
    {
        session()->forget(['client_id', 'client_name']);
        return redirect()->route('portal.login');
    }
}
