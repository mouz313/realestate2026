<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                $user = Auth::user();
                if ($user->company_id) {
                    session(['company_id' => $user->company_id]);
                }

                toastr()->success('Welcome back!');

                return redirect()->intended(route(dashboard_route()));
            }
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors([
                'email' => 'Unable to sign in right now. Please try again later.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $slug = Str::slug($request->company_name);
        $baseSlug = $slug;
        $i = 1;
        while (Company::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i++;
        }

        try {
            $company = Company::create([
                'name' => $request->company_name,
                'slug' => $slug,
                'email' => $request->email,
                'is_active' => true,
            ]);

            $user = User::create([
                'company_id' => $company->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'owner',
            ]);

            $user->assignRole('owner');

            session(['company_id' => $company->id]);

            Auth::login($user);
            $request->session()->regenerate();
        } catch (\Throwable $e) {
            report($e);
            return back()
                ->withErrors(['email' => 'Unable to create your account right now. Please try again later.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        toastr()->success('Company and account created successfully!');

        return redirect()->route(dashboard_route());
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect('/');
    }
}
