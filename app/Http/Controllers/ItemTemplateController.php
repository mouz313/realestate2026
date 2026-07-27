<?php

namespace App\Http\Controllers;

use App\Models\ItemTemplate;
use Illuminate\Http\Request;

class ItemTemplateController extends Controller
{
    public function index()
    {
        $templates = ItemTemplate::latest()->paginate(20);

        return view('settings.item-templates', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'default_price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
        ]);

        ItemTemplate::create($request->all());

        toastr()->success('Item template created.');

        return back();
    }

    public function update(Request $request, ItemTemplate $itemTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'default_price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $itemTemplate->update($request->all());

        toastr()->success('Item template updated.');

        return back();
    }

    public function destroy(ItemTemplate $itemTemplate)
    {
        $itemTemplate->delete();

        toastr()->success('Item template deleted.');

        return back();
    }
}
