<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('sort_order')->orderBy('id')->get();

        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePackage($request);

        Package::create($data);

        toastr()->success('Package created successfully.');

        return redirect()->route('packages.index');
    }

    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $this->validatePackage($request, $package->id);

        $package->update($data);

        toastr()->success('Package updated successfully.');

        return redirect()->route('packages.index');
    }

    public function destroy(Package $package)
    {
        if ($package->subscriptions()->exists()) {
            toastr()->error('Cannot delete a package that has subscriptions.');

            return back();
        }

        $package->delete();
        toastr()->success('Package deleted.');

        return redirect()->route('packages.index');
    }

    private function validatePackage(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:packages,slug'.($ignoreId ? ','.$ignoreId : ''),
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'interval' => 'required|in:month,year',
            'max_employees' => 'required|integer|min:0',
            'max_clients' => 'required|integer|min:0',
            'max_properties' => 'required|integer|min:0',
            'trial_days' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);
    }
}
