<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Role;
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

    public function staffCreate()
    {
        return view('team.staff-create');
    }

    public function staffStore(Request $request)
    {
        $companyId = current_company_id();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'company_id' => $companyId,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'staff',
            'is_active' => true,
        ]);

        if ($staffRole = Role::where('slug', 'staff')->where('company_id', $companyId)->first()) {
            $user->roles()->attach($staffRole->id);
        }

        toastr()->success('Staff member added.');

        return redirect()->route('team.index', ['type' => 'staff']);
    }

    public function staffEdit(User $user)
    {
        abort_if(! $this->isStaff($user), 404);

        return view('team.staff-edit', compact('user'));
    }

    public function staffUpdate(Request $request, User $user)
    {
        abort_if(! $this->isStaff($user), 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        toastr()->success('Staff member updated.');

        return redirect()->route('team.index', ['type' => 'staff']);
    }

    public function staffDestroy(User $user)
    {
        abort_if(! $this->isStaff($user), 404);

        $user->delete();

        toastr()->success('Staff member removed.');

        return redirect()->route('team.index', ['type' => 'staff']);
    }

    protected function isStaff(User $user): bool
    {
        return $user->role === 'staff' || $user->roles()->where('slug', 'staff')->exists();
    }
}
