<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItemLocation;
use App\Models\Presentation;
use App\Models\StorageZone;
use Illuminate\Validation\Rule;

class ItemLocationController extends Controller
{
    public function index()
    {
        $locations = ItemLocation::with('presentation.item', 'storageZone')
                                   ->orderBy('id', 'desc')
                                   ->get();

        return view('item_locations.index', compact('locations'));
    }

    public function create()
    {
        $presentations = Presentation::orderBy('sku')->get();
        $storageZones = StorageZone::orderBy('name')->get();

        return view('item_locations.create', compact('presentations', 'storageZones'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'presentation_id' => [
                'required',
                'integer',
                'exists:presentations,id',
                Rule::unique('item_locations')->where(function ($query) use ($request) {
                    return $query->where('storage_zone_id', $request->storage_zone_id);
                }),
            ],
            'storage_zone_id' => 'required|integer|exists:storage_zones,id',
            'occupied_m2'     => 'nullable|numeric|min:0',
            'stored_quantity' => 'nullable|integer|min:0',
            'assigned_at'     => 'nullable|date',
        ], [
            'presentation_id.unique' => 'Esta presentación ya está registrada en esta zona de almacenamiento.'
        ]);

        $location = ItemLocation::create($data);

        return redirect()->route('item_locations.index')
                         ->with('success', "Ubicación creada con éxito (ID: {$location->id}).");
    }

    public function show(ItemLocation $itemLocation)
    {
        $itemLocation->load('presentation.item', 'storageZone');
        return view('item_locations.show', compact('itemLocation'));
    }
    public function edit(ItemLocation $itemLocation) 
    {
        $presentations = Presentation::orderBy('sku')->get();
        $storageZones = StorageZone::orderBy('name')->get();

        return view('item_locations.edit', compact('itemLocation', 'presentations', 'storageZones'));
    }

    public function update(Request $request, ItemLocation $itemLocation)
    {
        $data = $request->validate([
            'presentation_id' => [
                'required',
                'integer',
                'exists:presentations,id',
                Rule::unique('item_locations')->where(function ($query) use ($request) {
                    return $query->where('storage_zone_id', $request->storage_zone_id);
                })->ignore($itemLocation->id), // Ignora el registro actual
            ],
            'storage_zone_id' => 'required|integer|exists:storage_zones,id',
            'occupied_m2'     => 'nullable|numeric|min:0',
            'stored_quantity' => 'nullable|integer|min:0',
            'assigned_at'     => 'nullable|date',
        ], [
            'presentation_id.unique' => 'Esta presentación ya está registrada en esta zona de almacenamiento.'
        ]);

        $itemLocation->update($data);

        return redirect()->route('item_locations.show', $itemLocation)
                         ->with('success', 'Ubicación actualizada con éxito.');
    }

    public function destroy(ItemLocation $itemLocation) // <-- Usa Route-Model Binding
    {
        $itemLocation->delete(); // Activa el SoftDelete

        return redirect()->route('item_locations.index')
                         ->with('success', 'Ubicación eliminada con éxito.');
    }
}
