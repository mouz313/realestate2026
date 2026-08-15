<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Notifications\ClientResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('portal.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $client = Client::where('email', $request->email)->first();

        if (! $client) {
            return back()->withErrors(['email' => 'No portal account found for this email.']);
        }

        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $client->email],
            ['token' => hash('sha256', $token), 'created_at' => now()],
        );

        $client->notify(new ClientResetPassword($token));

        return back()->with('status', 'Password reset link sent to your email.');
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('portal.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', hash('sha256', $request->token))
            ->first();

        if (! $record || Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Invalid or expired reset link.']);
        }

        $client = Client::where('email', $request->email)->first();
        if (! $client) {
            return back()->withErrors(['email' => 'No portal account found for this email.']);
        }

        $client->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $client->email)->delete();

        return redirect()->route('portal.login')->with('status', 'Password reset successfully.');
    }
}
