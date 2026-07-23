<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->paginate(15);
        return view('items.index', compact('items'));
    }

    public function show(Item $item)
    {
        return redirect()->route('items.edit', $item);
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'default_price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        Item::create($request->validated());
        toastr()->success('Item added successfully.');
        return redirect()->route('items.index');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'default_price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        $item->update($request->validated());
        toastr()->success('Item updated successfully.');
        return redirect()->route('items.index');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        toastr()->success('Item deleted successfully.');
        return redirect()->route('items.index');
    }
}
