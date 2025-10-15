<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryMovementController extends Controller
{
    public function index()
    {
    
        $movements = DB::table('inventory_movements as m')
            ->join('presentations as p', 'm.presentation_id', '=', 'p.id')
            ->leftJoin('users as u', 'm.user_id', '=', 'u.id')
            ->select(
                'm.id',
                'm.type',
                'm.quantity',
                'm.movement_date',
                'm.notes',
                'p.sku as presentation_sku',
                'p.description as presentation_description',
                'u.name as user_name'
            )
            ->orderBy('m.movement_date', 'desc')
            ->get();

        return view('movements', compact('movements'));
    }

    public function store(Request $request)
    {
        $id = DB::table('inventory_movements')->insertGetId([
            'presentation_id' => $request->presentation_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Movement created', 'id' => $id]);
    }

    public function show($id)
    {
        return DB::table('inventory_movements')->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        DB::table('inventory_movements')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Movement updated']);
    }

    public function destroy($id)
    {
        DB::table('inventory_movements')->where('id', $id)->delete();
        return response()->json(['message' => 'Movement deleted']);
    }
}
