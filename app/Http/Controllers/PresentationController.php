<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presentation;

class PresentationController extends Controller
{

    public function index()
    {
        $presentations = Presentation::with(['item.category', 'item.supplier'])->get();
        return response()->json($presentations);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'sku' => 'required|string|max:100|unique:presentations,sku',
            'description' => 'nullable|string|max:200',
            'units_per_presentation' => 'nullable|integer|min:1',
            'stock_current' => 'nullable|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'base_unit' => 'nullable|string|max:50',
        ]);

        $presentation = Presentation::create($data);

        return response()->json(['message' => 'Presentation created', 'id' => $presentation->id]);
    }
    public function show($id)
    {
        $presentation = Presentation::with(['item.category', 'item.supplier'])->findOrFail($id);
        return response()->json($presentation);
    }

    public function update(Request $request, $id)
    {
        $presentation = Presentation::findOrFail($id);

        $data = $request->validate([
            'item_id' => 'sometimes|required|integer|exists:items,id',
            'sku' => "sometimes|required|string|max:100|unique:presentations,sku,{$id}",
            'description' => 'nullable|string|max:200',
            'units_per_presentation' => 'nullable|integer|min:1',
            'stock_current' => 'nullable|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'base_unit' => 'nullable|string|max:50',
        ]);

        $presentation->update($data);

        return response()->json(['message' => 'Presentation updated']);
    }

    public function destroy($id)
    {
        $presentation = Presentation::findOrFail($id);
        $presentation->delete();

        return response()->json(['message' => 'Presentation deleted']);
    }
}
