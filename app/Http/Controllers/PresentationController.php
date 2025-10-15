<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentationController extends Controller
{
    public function index()
    {
        $rows = DB::table('presentations as p')
            ->join('items as i', 'p.item_id', '=', 'i.id')
            ->join('categories as c', 'i.category_id', '=', 'c.id')
            ->join('suppliers as s', 'i.supplier_id', '=', 's.id')
            ->select(
                'p.id',
                'p.sku',
                'p.description',
                'p.stock_current',
                'p.stock_minimum',
                'p.unit_price',
                'p.units_per_presentation',
                'i.name as item_name',
                'c.name as category_name',
                's.name as supplier_name'
            )
            ->get();

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $id = DB::table('presentations')->insertGetId($request->all());
        return response()->json(['message' => 'Presentation created', 'id' => $id]);
    }

    public function show($id)
    {
        return DB::table('presentations')->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        DB::table('presentations')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Presentation updated']);
    }

    public function destroy($id)
    {
        DB::table('presentations')->where('id', $id)->delete();
        return response()->json(['message' => 'Presentation deleted']);
    }
}
