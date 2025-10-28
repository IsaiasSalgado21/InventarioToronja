<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function index()
    {
        $movements = InventoryMovement::with([
                'presentation' => function ($query) {
                    $query->select('id', 'sku', 'description');
                },
                'user' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->orderByDesc('movement_date')
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

        $movement = InventoryMovement::create($data);

        return response()->json(['message' => 'Movement created', 'id' => $movement->id]);
    }

    public function show($id)
    {
        $movement = InventoryMovement::with([
                'presentation:id,sku,description',
                'user:id,name'
            ])
            ->find($id);

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

        $movement = InventoryMovement::find($id);

        if (!$movement) {
            return response()->json(['message' => 'Movement not found or deleted'], 404);
        }

        $movement->update($data);

        return response()->json(['message' => 'Movement updated']);
    }

    public function destroy($id)
    {
        $movement = InventoryMovement::find($id);

        if (!$movement) {
            return response()->json(['message' => 'Movement not found or already deleted'], 404);
        }

        $movement->delete();

        return response()->json(['message' => 'Movement deleted (soft)']);
    }
}
