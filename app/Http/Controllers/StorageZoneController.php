<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageZoneController extends Controller
{
    public function index()
    {
        return DB::table('storage_zones')->get();
    }

    public function store(Request $request)
    {
        $id = DB::table('storage_zones')->insertGetId([
            'name' => $request->name,
            'description' => $request->description,
            'dimension_x' => $request->dimension_x ?? 0,
            'dimension_y' => $request->dimension_y ?? 0,
            'capacity_m2' => $request->capacity_m2 ?? 0,
        ]);

        return response()->json(['message' => 'Storage zone created', 'id' => $id]);
    }

    public function show($id)
    {
        return DB::table('storage_zones')->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        DB::table('storage_zones')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Storage zone updated']);
    }

    public function destroy($id)
    {
        DB::table('storage_zones')->where('id', $id)->delete();
        return response()->json(['message' => 'Storage zone deleted']);
    }
    public function dashboard()
{
    $zones = DB::table('storage_zones as z')
        ->leftJoin('item_locations as l', 'l.storage_zone_id', '=', 'z.id')
        ->leftJoin('presentations as p', 'p.id', '=', 'l.presentation_id')
        ->leftJoin('items as i', 'i.id', '=', 'p.item_id')
        ->select(
            'z.id',
            'z.name as zone_name',
            'z.description',
            'z.dimension_x',
            'z.dimension_y',
            'z.capacity_m2',
            DB::raw('COALESCE(SUM(l.occupied_m2), 0) as occupied_m2'),
            DB::raw('COALESCE(SUM(l.stored_quantity), 0) as total_units')
        )
        ->groupBy('z.id', 'z.name', 'z.description', 'z.dimension_x', 'z.dimension_y', 'z.capacity_m2')
        ->get();

    $presentations = DB::table('item_locations as l')
        ->join('presentations as p', 'p.id', '=', 'l.presentation_id')
        ->join('items as i', 'i.id', '=', 'p.item_id')
        ->select(
            'l.storage_zone_id',
            'p.sku',
            'p.description as presentation_description',
            'l.stored_quantity'
        )
        ->get()
        ->groupBy('storage_zone_id'); // Agrupa presentaciones por zona

    return view('storage', compact('zones', 'presentations'));
}

}
