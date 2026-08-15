<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::with('convertedDeal')->latest()->paginate(20);

        return view('referrals.index', compact('referrals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'referred_name' => 'required|string|max:120',
            'referred_phone' => 'nullable|string|max:20',
            'referred_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['referrer_name'] = session('client_name') ?? ('Client #'.session('client_id'));
        $data['status'] = 'pending';

        Referral::create($data);

        toastr()->success('Referral submitted. Thank you!');

        return back();
    }
}
