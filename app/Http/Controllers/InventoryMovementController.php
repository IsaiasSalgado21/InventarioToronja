<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryMovementController extends Controller
{
    public function index()
    {
        $movements = DB::table('inventory_movements as im')
            ->join('presentations as p', 'im.presentation_id', '=', 'p.id')
            ->join('users as u', 'im.user_id', '=', 'u.id')
            ->whereNull('im.deleted_at')
            ->whereNull('p.deleted_at')
            ->whereNull('u.deleted_at')
            ->select(
                'im.id',
                'im.type',
                'im.quantity',
                'im.notes',
                'im.movement_date',
                'p.sku as presentation_sku',
                'p.description as presentation_description',
                'u.name as user_name'
            )
            ->orderByDesc('im.movement_date')
            ->get();

        return view('movements', compact('movements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'presentation_id' => 'required|integer',
            'user_id' => 'required|integer',
            'type' => 'required|string|max:50',
            'quantity' => 'required|integer',
            'notes' => 'nullable|string',
            'movement_date' => 'nullable|date',
        ]);

        $data['movement_date'] = $data['movement_date'] ?? now();
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $id = DB::table('inventory_movements')->insertGetId($data);

        return response()->json(['message' => 'Movement created', 'id' => $id]);
    }

    public function show($id)
    {
        $movement = DB::table('inventory_movements as im')
            ->join('presentations as p', 'im.presentation_id', '=', 'p.id')
            ->join('users as u', 'im.user_id', '=', 'u.id')
            ->where('im.id', $id)
            ->whereNull('im.deleted_at')
            ->select(
                'im.*',
                'p.sku as presentation_sku',
                'p.description as presentation_description',
                'u.name as user_name'
            )
            ->first();

        if (!$movement) {
            return response()->json(['message' => 'Movement not found'], 404);
        }

        return response()->json($movement);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'presentation_id' => 'sometimes|integer',
            'user_id' => 'sometimes|integer',
            'type' => 'sometimes|string|max:50',
            'quantity' => 'sometimes|integer',
            'notes' => 'nullable|string',
            'movement_date' => 'nullable|date',
        ]);

        $data['updated_at'] = now();

        $updated = DB::table('inventory_movements')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update($data);

        if (!$updated) {
            return response()->json(['message' => 'Movement not found or deleted'], 404);
        }

        return response()->json(['message' => 'Movement updated']);
    }

    public function destroy($id)
    {
        $deleted = DB::table('inventory_movements')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        if (!$deleted) {
            return response()->json(['message' => 'Movement not found or already deleted'], 404);
        }

        return response()->json(['message' => 'Movement deleted (soft)']);
    }
}
