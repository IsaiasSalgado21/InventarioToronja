<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        $items = DB::table('items as i')
            ->leftJoin('categories as c', 'i.category_id', '=', 'c.id')
            ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.id')
            ->leftJoin('presentations as p', 'p.item_id', '=', 'i.id')
            ->leftJoin('item_locations as l', 'l.presentation_id', '=', 'p.id')
            ->leftJoin('storage_zones as z', 'l.storage_zone_id', '=', 'z.id')
            ->whereNull('i.deleted_at')
            ->whereNull('c.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('p.deleted_at')
            ->whereNull('z.deleted_at')
            ->select(
                'i.id',
                'i.name',
                'i.expiry_date',
                'c.name as category_name',
                's.name as supplier_name',
                'p.sku',
                'p.description as presentation_description',
                'p.stock_current',
                'p.stock_minimum',
                'p.unit_price',
                'z.name as storage_zone',
                'l.stored_quantity',
                'l.occupied_m2'
            )
            ->orderBy('i.id', 'desc')
            ->get();

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = DB::table('categories')->whereNull('deleted_at')->orderBy('name')->get();
        $suppliers = DB::table('suppliers')->whereNull('deleted_at')->orderBy('name')->get();
        $storageZones = DB::table('storage_zones')->whereNull('deleted_at')->orderBy('name')->get();

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

        $itemId = DB::table('items')->insertGetId([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'supplier_id' => $data['supplier_id'] ?? null,
            'abc_class' => $data['abc_class'] ?? null,
            'expiry_date' => $data['expiry_date'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $presentationId = null;
        if (!empty($data['presentation_sku'])) {
            $presentationId = DB::table('presentations')->insertGetId([
                'item_id' => $itemId,
                'sku' => $data['presentation_sku'],
                'description' => $data['presentation_description'] ?? '',
                'units_per_presentation' => $data['units_per_presentation'] ?? 1,
                'stock_current' => $data['stock_current'] ?? 0,
                'stock_minimum' => $data['stock_minimum'] ?? 0,
                'unit_price' => $data['unit_price'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($presentationId && !empty($data['storage_zone_id'])) {
            DB::table('item_locations')->insert([
                'presentation_id' => $presentationId,
                'storage_zone_id' => $data['storage_zone_id'],
                'stored_quantity' => $data['stored_quantity'] ?? 0,
                'occupied_m2' => $data['occupied_m2'] ?? 0,
                'date_assigned' => now(),
            ]);
        }

        return redirect()->route('items.index')
            ->with('success', "Item creado correctamente (ID: {$itemId})");
    }

    public function show($id)
    {
        $item = DB::table('items as i')
            ->leftJoin('categories as c', 'i.category_id', '=', 'c.id')
            ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.id')
            ->leftJoin('presentations as p', 'p.item_id', '=', 'i.id')
            ->leftJoin('item_locations as l', 'l.presentation_id', '=', 'p.id')
            ->leftJoin('storage_zones as z', 'l.storage_zone_id', '=', 'z.id')
            ->where('i.id', $id)
            ->whereNull('i.deleted_at')
            ->select(
                'i.*',
                'c.name as category_name',
                's.name as supplier_name',
                'p.sku',
                'p.description as presentation_description',
                'p.stock_current',
                'p.stock_minimum',
                'p.unit_price',
                'z.name as storage_zone',
                'l.stored_quantity',
                'l.occupied_m2'
            )
            ->first();

        if (!$item) {
            return redirect()->route('items.index')->with('error', 'Item no encontrado');
        }

        return view('items.show', compact('item'));
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

        $updated = DB::table('items')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update(array_merge($data, ['updated_at' => now()]));

        if (!$updated) {
            return redirect()->route('items.index')->with('error', 'No se pudo actualizar o el item no existe');
        }

        return redirect()->route('items.show', $id)->with('success', 'Item actualizado correctamente');
    }

    public function destroy($id)
    {
        $deleted = DB::table('items')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update(['deleted_at' => now()]);

        if (!$deleted) {
            return redirect()->route('items.index')->with('error', 'Item no encontrado o ya eliminado');
        }

        return redirect()->route('items.index')->with('success', 'Item eliminado lógicamente');
    }
}
