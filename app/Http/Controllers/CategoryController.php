<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        return DB::table('categories')->get();
    }

    public function store(Request $request)
    {
        $id = DB::table('categories')->insertGetId([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Category created', 'id' => $id]);
    }

    public function show($id)
    {
        return DB::table('categories')->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        DB::table('categories')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Category updated']);
    }

    public function destroy($id)
    {
        DB::table('categories')->where('id', $id)->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}
