<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        return DB::table('items')->get();
    }

    public function store(Request $request)
    {
        $id = DB::table('items')->insertGetId([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'abc_class' => $request->abc_class,
            'expiry_date' => $request->expiry_date,  
        ]);

        return response()->json(['message' => 'Item created', 'id' => $id]);
    }

    public function show($id)
    {
        return DB::table('items')->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        DB::table('items')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Item updated']);
    }

    public function destroy($id)
    {
        DB::table('items')->where('id', $id)->delete();
        return response()->json(['message' => 'Item deleted']);
    }
}
