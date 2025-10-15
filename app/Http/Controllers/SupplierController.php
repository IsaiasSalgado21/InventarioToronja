<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        return DB::table('suppliers')->get();
    }

    public function store(Request $request)
    {
        $id = DB::table('suppliers')->insertGetId([
            'name' => $request->name,
            'contact' => $request->contact,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return response()->json(['message' => 'Supplier created', 'id' => $id]);
    }

    public function show($id)
    {
        return DB::table('suppliers')->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        DB::table('suppliers')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Supplier updated']);
    }

    public function destroy($id)
    {
        DB::table('suppliers')->where('id', $id)->delete();
        return response()->json(['message' => 'Supplier deleted']);
    }
}
