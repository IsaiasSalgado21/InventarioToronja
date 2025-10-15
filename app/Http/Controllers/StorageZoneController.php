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
}
