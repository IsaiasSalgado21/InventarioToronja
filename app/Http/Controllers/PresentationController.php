<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presentation;
use App\Models\Item;
use Illuminate\Validation\Rule;

class PresentationController extends Controller
{

    public function index(Request $request)
    {
        $query = Presentation::query();

        $query->with(['item.category']); 

        if ($request->filled('sku')) {
            $query->where('sku', 'like', '%' . $request->sku . '%');
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }
        
        if ($request->filled('archetype')) {
            $query->where('archetype', 'like', '%' . $request->archetype . '%');
        }

        if ($request->filled('quality')) {
            $query->where('quality', $request->quality);
        }
        
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'low') {
                $query->where('stock_minimum', '>', 0)
                      ->whereRaw('stock_current <= stock_minimum');
            } elseif ($request->stock_status == 'out') {
                $query->where('stock_current', '=', 0);
            }
        }

        $presentations = $query->latest('id')->paginate(15)->withQueryString();

        $items = Item::orderBy('name')->get();
        $calidadesUnicas = Presentation::select('quality')
                            ->whereNotNull('quality')
                            ->where('quality', '!=', '')
                            ->distinct()
                            ->orderBy('quality')
                            ->get();

        return view('presentations.index', compact('presentations', 'items', 'calidadesUnicas', 'request'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();
        return view('presentations.create', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'sku' => 'required|string|max:100|unique:presentations,sku',
            'description' => 'nullable|string|max:200',
            'archetype' => 'required|string|max:100',
            'quality' => 'nullable|string|max:100',
            'units_per_presentation' => 'nullable|integer|min:1',
            'stock_current' => 'nullable|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'm2_per_unit' => 'nullable|numeric|min:0',
            'base_unit' => 'nullable|string|max:50',
        ]);

        $data['stock_current'] = 0;
        $presentation = Presentation::create($data);

        return redirect()->route('presentations.index')
                         ->with('success', "Presentación '{$presentation->sku}' creada con éxito. Stock inicial es 0.");
    }
    public function show(Presentation $presentation)
    {
        $presentation->load('item.category', 'itemLocations.storageZone');
        return view('presentations.show', compact('presentation'));
    }

    public function edit(Presentation $presentation)
    {
        $items = Item::orderBy('name')->get();
        return view('presentations.edit', compact('presentation', 'items'));
    }

    public function update(Request $request, Presentation $presentation)
    {
        $data = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('presentations')->ignore($presentation->id), 
            ],
            'description' => 'nullable|string|max:200',
            'archetype' => 'required|string|max:100',
            'quality' => 'required|string|max:100',
            'units_per_presentation' => 'nullable|integer|min:1',
            'stock_minimum' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'base_unit' => 'nullable|string|max:50',
            'm2_per_unit' => 'nullable|numeric|min:0',
        ]);

        unset($data['stock_current']);

        $presentation->update($data);
        return redirect()->route('presentations.show', $presentation)
                         ->with('success', "Presentación '{$presentation->sku}' actualizada con éxito.");
    }
    public function destroy(Presentation $presentation) 
    {
        $sku = $presentation->sku; 
        $presentation->delete(); 

        return redirect()->route('inventory')
                         ->with('success', "Presentación '{$sku}' eliminada con éxito.");
    }
}
