<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StorageZone;
use App\Models\ItemLocation;
use Illuminate\Validation\Rule;

class StorageZoneController extends Controller
{
    public function index()
    {
        $zones = StorageZone::withSum('itemLocations', 'occupied_m2')
                            ->withSum('itemLocations', 'stored_quantity')
                            ->orderBy('name')
                            ->get();
        return view('storage_zones.index', compact('zones'));
    }
    public function create()
    {
        return view('storage_zones.create');
    }
    public function store(Request $request)
    {
       $data = $request->validate([
            'name' => 'required|string|max:255|unique:storage_zones,name',
            'description' => 'nullable|string',
            'dimension_x' => 'nullable|numeric|min:0',
            'dimension_y' => 'nullable|numeric|min:0',
            'capacity_m2' => 'nullable|numeric|min:0',
        ]);
        $zone = StorageZone::create($data);

        return redirect()->route('storage_zones.index')
                         ->with('success', "Zona '{$zone->name}' creada con éxito.");
    }

    public function show(StorageZone $zone)
    {
        $zone->loadSum('itemLocations', 'occupied_m2');
        $zone->loadSum('itemLocations', 'stored_quantity');

        $locations = ItemLocation::with('presentation.item')
                                 ->where('storage_zone_id', $zone->id)
                                 ->get();
        
        return view('storage_zones.show', compact('zone', 'locations'));
    }

     public function edit(StorageZone $zone)
    {
        return view('storage_zones.edit', compact('zone'));
    }

    public function update(Request $request, StorageZone $zone)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('storage_zones')->ignore($zone->id),
            ],
            'description' => 'nullable|string',
            'dimension_x' => 'nullable|numeric|min:0',
            'dimension_y' => 'nullable|numeric|min:0',
            'capacity_m2' => 'nullable|numeric|min:0',
        ]);
        $zone->update($data);

        return redirect()->route('storage_zones.show', $zone->id)
                         ->with('success', "Zona '{$zone->name}' actualizada con éxito.");
    }

    public function destroy(StorageZone $zone)
    {
        $zone->delete();

        return redirect()->route('storage_zones.index')
                         ->with('success', "Zona '{$zone->name}' eliminada con éxito.");
    }

}
