<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{

    public function index()
    {
    
        $suppliers = Supplier::all();

        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'contact' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create($data);

        return response()->json(['message' => 'Supplier created', 'id' => $supplier->id]);
    }
    
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:suppliers,name', 
            'contact' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $supplier = Supplier::create($validator->validated());
            return response()->json(['success' => true, 'supplier' => $supplier]);
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create supplier.'], 500);
        }
    }
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id); // Solo obtiene si no está soft deleted

        return response()->json($supplier);
    }


    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:150',
            'contact' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier->update($data);

        return response()->json(['message' => 'Supplier updated']);
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete(); 

        return response()->json(['message' => 'Supplier deleted']);
    }

    public function restore($id)
    {
        $supplier = Supplier::withTrashed()->find($id);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
        $supplier->restore();
        return response()->json(['message' => 'Supplier restored']);
    }
}
