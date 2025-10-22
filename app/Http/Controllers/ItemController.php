<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Presentation;
use App\Models\ItemLocation;
use App\Models\StorageZone;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'supplier', 'presentations.itemLocations.storageZone'])
            ->latest('id') // orderBy('id', 'desc')
            ->get();

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $storageZones = StorageZone::orderBy('name')->get();

        return view('items.create', compact('categories', 'suppliers', 'storageZones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'abc_class' => 'nullable|in:A,B,C',
            'expiry_date' => 'nullable|date',
            'presentation_sku' => 'nullable|string|max:100',
            'presentation_description' => 'nullable|string|max:200',
            'units_per_presentation' => 'nullable|integer|min:1',
            'stock_current' => 'nullable|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'storage_zone_id' => 'nullable|integer|exists:storage_zones,id',
            'stored_quantity' => 'nullable|integer|min:0',
            'occupied_m2' => 'nullable|numeric|min:0',
        ]);

        try{
            $item = DB::transaction(function () use ($data) {
                $item = Item::create([
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'category_id' => $data['category_id'] ?? null,
                    'supplier_id' => $data['supplier_id'] ?? null,
                    'abc_class' => $data['abc_class'] ?? null,
                    'expiry_date' => $data['expiry_date'] ?? null,
                ]);
                if(!empty($data['presentation_sku']) || !empty($data['presentation_description']) || !empty($data['units_per_presentation'])){
                    $presentation = Presentation::create([
                        'item_id' => $item->id,
                        'sku' => $data['presentation_sku'] ?? null,
                        'description' => $data['presentation_description'] ?? null,
                        'units_per_presentation' => $data['units_per_presentation'] ?? null,
                        'stock_current' => $data['stock_current'] ?? 0,
                        'stock_minimum' => $data['stock_minimum'] ?? 0,
                        'unit_price' => $data['unit_price'] ?? 0,
                    ]);

                    if (!empty($data['storage_zone_id'])) {
                        ItemLocation::create([
                            'presentation_id' => $presentation->id,
                            'storage_zone_id' => $data['storage_zone_id'],
                            'stored_quantity' => $data['stored_quantity'] ?? 0,
                            'occupied_m2' => $data['occupied_m2'] ?? 0,
                            'assigned_at' => now(),
                        ]);
                        $presentation->update(['stock_current' => $data['stored_quantity'] ?? 0]);
                    }else{
                        $presentation->update(['stock_current' => 0]);
                    }
                }
                return $item;
            });
            return redirect()->route('items.index')
                         ->with('success', "Item creado correctamente (ID: {$item->id})");

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el item: ' . $e->getMessage())->withInput();
        }
    }
    public function show($id)
    {
        $item = Item::with([
            'category', 
            'supplier', 
            'presentations.itemLocations.storageZone' 
        ])->find($id);

        if (!$item) {
            return redirect()->route('items.index')->with('error', 'Item no encontrado');
        }

        return view('items.show', compact('item'));
    }
    public function edit($id)
{
    $item = Item::find($id);

    if (!$item) {
        return redirect()->route('items.index')->with('error', 'Item no encontrado');
    }

    $categories = Category::orderBy('name')->get();
    $suppliers = Supplier::orderBy('name')->get();
    $storageZones = StorageZone::orderBy('name')->get();

    return view('items.edit', compact('item', 'categories', 'suppliers', 'storageZones'));
}
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:200',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'abc_class' => 'nullable|in:A,B,C',
            'expiry_date' => 'nullable|date',
        ]);

        $item = Item::find($id);

        if (!$item) {
            return redirect()->route('items.index')->with('error', 'No se pudo actualizar o el item no existe');
        }

        $item->update($data);

        return redirect()->route('items.show', $id)->with('success', 'Item actualizado correctamente');
    }

    public function destroy($id)
    {
       $item =Item::find($id);
        if (!$item) {
            return redirect()->route('items.index')->with('error', 'Item no encontrado');
        }
        $item->delete(); 

        return redirect()->route('items.index')->with('success', 'Item eliminado lógicamente');
    }
}
