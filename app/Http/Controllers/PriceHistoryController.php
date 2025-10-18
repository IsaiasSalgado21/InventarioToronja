<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriceHistory;

class PriceHistoryController extends Controller
{

    public function index()
    {
        $histories = PriceHistory::with('presentation')->get(); // Relación con presentation

        return response()->json($histories);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'presentation_id' => 'required|integer|exists:presentations,id',
            'price_old' => 'required|numeric|min:0',
            'price_new' => 'required|numeric|min:0',
        ]);

        $history = PriceHistory::create($data);

        return response()->json(['message' => 'Price history created', 'id' => $history->id]);
    }

    public function show($id)
    {
        $history = PriceHistory::with('presentation')->findOrFail($id);

        return response()->json($history);
    }

    public function update(Request $request, $id)
    {
        $history = PriceHistory::findOrFail($id);

        $data = $request->validate([
            'presentation_id' => 'sometimes|required|integer|exists:presentations,id',
            'price_old' => 'sometimes|required|numeric|min:0',
            'price_new' => 'sometimes|required|numeric|min:0',
        ]);

        $history->update($data);

        return response()->json(['message' => 'Price history updated']);
    }

    public function destroy($id)
    {
        $history = PriceHistory::findOrFail($id);
        $history->delete();

        return response()->json(['message' => 'Price history deleted']);
    }
}
