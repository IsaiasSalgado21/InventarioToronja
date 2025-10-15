<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index()
    {
        $items = DB::table('items as i')
            ->leftJoin('categories as c', 'i.category_id', '=', 'c.id')
            ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.id')
            ->select('i.*', 'c.name as category_name', 's.name as supplier_name')
            ->orderBy('i.id', 'desc')
            ->get();

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = DB::table('categories')->orderBy('name')->get();
        $suppliers = DB::table('suppliers')->orderBy('name')->get();
        return view('items.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'abc_class' => 'nullable|in:A,B,C',
            'expiry_date' => 'nullable|date',
        ]);

        $id = DB::table('items')->insertGetId(array_merge($data, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return redirect()->route('items.index')->with('success', 'Item created successfully (ID: '.$id.')');
    }

    public function show($id)
    {
        $item = DB::table('items as i')
            ->leftJoin('categories as c', 'i.category_id', '=', 'c.id')
            ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.id')
            ->select('i.*', 'c.name as category_name', 's.name as supplier_name')
            ->where('i.id', $id)
            ->first();

        if (!$item) {
            return redirect()->route('items.index')->with('error', 'Item not found');
        }

        return view('items.show', compact('item'));
    }

    public function edit($id)
    {
        $item = DB::table('items')->where('id', $id)->first();
        if (!$item) {
            return redirect()->route('items.index')->with('error', 'Item not found');
        }

        $categories = DB::table('categories')->orderBy('name')->get();
        $suppliers = DB::table('suppliers')->orderBy('name')->get();

        return view('items.edit', compact('item', 'categories', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:200',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'abc_class' => 'nullable|in:A,B,C',
            'expiry_date' => 'nullable|date',
        ]);

        $updated = DB::table('items')->where('id', $id)->update(array_merge($data, [
            'updated_at' => now(),
        ]));

        if (!$updated) {
            return redirect()->route('items.index')->with('error', 'No changes or item not found');
        }

        return redirect()->route('items.show', $id)->with('success', 'Item updated successfully');
    }

    public function destroy($id)
    {
        $deleted = DB::table('items')->where('id', $id)->delete();

        if (!$deleted) {
            return redirect()->route('items.index')->with('error', 'Item not found or could not be deleted');
        }

        return redirect()->route('items.index')->with('success', 'Item deleted successfully');
    }
}
