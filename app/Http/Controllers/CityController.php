<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware(fn ($req, $next) => Gate::authorize('admin') ? $next($req) : abort(403));
    }

    public function index()
    {
        $cities = City::latest()->paginate(20);

        return view('cities.index', compact('cities'));
    }

    public function create()
    {
        return view('cities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        City::create($request->all());
        toastr()->success('City added successfully.');

        return redirect()->route('cities.index');
    }

    public function show(City $city)
    {
        return view('cities.show', compact('city'));
    }

    public function edit(City $city)
    {
        return view('cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $city->update($request->all());
        toastr()->success('City updated successfully.');

        return redirect()->route('cities.index');
    }

    public function destroy(City $city)
    {
        $city->delete();
        toastr()->success('City deleted successfully.');

        return redirect()->route('cities.index');
    }
}
