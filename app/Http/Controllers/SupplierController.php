<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'contact' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
            'RFC' => 'nullable|string|max:13',
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
            'RFC' => 'nullable|string|max:13',
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

        // If the request expects JSON (AJAX/API), return JSON, otherwise render a blade view
        if (request()->wantsJson() || request()->expectsJson()) {
            return response()->json($supplier);
        }

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier (web).
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('suppliers.edit', compact('supplier'));
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
            'RFC' => 'nullable|string|max:13',
        ]);

        $supplier->update($data);

        // For AJAX / API callers, keep returning JSON
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json(['message' => 'Supplier updated']);
        }

        // For web requests, redirect back to the supplier show page with a flash message
        return redirect()->route('suppliers.show', $supplier->id)
            ->with('success', 'Proveedor actualizado correctamente.');
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
