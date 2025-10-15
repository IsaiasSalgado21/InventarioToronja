<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemLocationController extends Controller
{
    public function index()
    {
        // INNER JOIN con presentations y storage_zones
        $rows = DB::table('item_locations as l')
            ->join('presentations as p', 'l.presentation_id', '=', 'p.id')
            ->join('storage_zones as z', 'l.zone_id', '=', 'z.id')
            ->select(
                'l.id',
                'l.space_occupied_m2',
                'l.quantity_stored',
                'l.date_assigned',
                'p.sku as presentation_sku',
                'p.description as presentation_description',
                'z.name as zone_name'
            )
            ->get();

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $id = DB::table('item_locations')->insertGetId([
            'presentation_id' => $request->presentation_id,
            'zone_id' => $request->zone_id,
            'space_occupied_m2' => $request->space_occupied_m2 ?? 0,
            'quantity_stored' => $request->quantity_stored ?? 0,
        ]);

        return response()->json(['message' => 'Item location created', 'id' => $id]);
    }

    public function show($id)
    {
        return DB::table('item_locations')->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        DB::table('item_locations')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Item location updated']);
    }

    public function destroy($id)
    {
        DB::table('item_locations')->where('id', $id)->delete();
        return response()->json(['message' => 'Item location deleted']);
    }
}
