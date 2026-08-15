<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $type = in_array($request->get('type'), ['agents', 'staff'], true)
            ? $request->get('type')
            : 'agents';

        $companyId = current_company_id();

        $agentCount = Agent::count();
        $staffCount = User::where('company_id', $companyId)
            ->whereHas('roles', fn ($q) => $q->where('slug', 'staff'))
            ->count();

        $agents = null;
        $staff = null;

        if ($type === 'staff') {
            $staff = User::with('roles')
                ->where('company_id', $companyId)
                ->whereHas('roles', fn ($q) => $q->where('slug', 'staff'))
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString();
        } else {
            $agents = Agent::with('user')
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString();
        }

        return view('team.index', compact('type', 'agents', 'staff', 'agentCount', 'staffCount'));
    }
}
