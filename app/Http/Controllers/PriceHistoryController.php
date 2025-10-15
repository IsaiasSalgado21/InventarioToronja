<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceHistoryController extends Controller
{
    public function index()
    {
        // INNER JOIN con presentations
        $rows = DB::table('price_histories as h')
            ->join('presentations as p', 'h.presentation_id', '=', 'p.id')
            ->select(
                'h.id',
                'h.price_old',
                'h.price_new',
                'h.date_change',
                'p.sku as presentation_sku',
                'p.description as presentation_description'
            )
            ->get();

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $id = DB::table('price_histories')->insertGetId([
            'presentation_id' => $request->presentation_id,
            'price_old' => $request->price_old,
            'price_new' => $request->price_new,
        ]);

        return response()->json(['message' => 'Price history created', 'id' => $id]);
    }

    public function show($id)
    {
        return DB::table('price_histories')->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        DB::table('price_histories')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Price history updated']);
    }

    public function destroy($id)
    {
        DB::table('price_histories')->where('id', $id)->delete();
        return response()->json(['message' => 'Price history deleted']);
    }
}
