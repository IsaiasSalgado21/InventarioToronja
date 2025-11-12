<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StorageZone;
use App\Models\ItemLocation;
use Illuminate\Validation\Rule;

class StorageZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }
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
            'capacity_units' => 'nullable|integer|min:0',

        ]);
        $zone = StorageZone::create($data);

        return redirect()->route('storage_zones.index')
                         ->with('success', "Zona '{$zone->name}' creada con éxito.");
    }

    public function show(StorageZone $storage_zone)
    {
        $storage_zone->loadSum('itemLocations', 'occupied_m2');
        $storage_zone->loadSum('itemLocations', 'stored_quantity');

        $locations = ItemLocation::with('presentation.item')
                                 ->where('storage_zone_id', $storage_zone->id)
                                 ->get();
        
        return view('storage_zones.show', ['zone' => $storage_zone, 'locations' => $locations]); 
    }

     public function edit(StorageZone $storage_zone)
    {
        return view('storage_zones.edit', ['zone' => $storage_zone]);
    }

    public function update(Request $request, StorageZone $storage_zone)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('storage_zones')->ignore($storage_zone->id),
            ],
            'description' => 'nullable|string',
            'dimension_x' => 'nullable|numeric|min:0',
            'dimension_y' => 'nullable|numeric|min:0',
            'capacity_m2' => 'nullable|numeric|min:0',
            'capacity_units' => 'nullable|integer|min:0',
        ]);
        $storage_zone->update($data);

        return redirect()->route('storage_zones.show', $storage_zone->id)
                         ->with('success', "Zona '{$storage_zone->name}' actualizada con éxito.");
    }

    public function destroy(StorageZone $storage_zone)
    {
        $hasStock = $storage_zone->itemLocations()->where('stored_quantity', '>', 0)->exists();
        if ($hasStock) {
            return back()->with('error', "¡No se puede eliminar! La zona '{$storage_zone->name}' todavía tiene stock. Por favor, transfiera todos los items a otra zona primero.");
        }

        $storage_zone->itemLocations()->delete();

        $storage_zone->delete();

        return redirect()->route('storage_zones.index')
                         ->with('success', "Zona '{$storage_zone->name}' eliminada con éxito.");
    }

}
