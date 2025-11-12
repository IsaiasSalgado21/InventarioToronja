<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Presentation;
use App\Models\ItemLocation;
use App\Models\StorageZone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    public function index(Request $request)
    {
        $query = Item::query();

        $query->with(['category', 'presentations']);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('archetype')) {
            $query->whereHas('presentations', function ($q) use ($request) {
                $q->where('archetype', 'like', '%' . $request->archetype . '%');
            });
        }

        if ($request->filled('quality')) {
            $query->whereHas('presentations', function ($q) use ($request) {
                $q->where('quality', $request->quality);
            });
        }

        $items = $query->latest('id')->paginate(15)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $calidadesUnicas = Presentation::select('quality')
                            ->whereNotNull('quality')
                            ->where('quality', '!=', '')
                            ->distinct()
                            ->orderBy('quality')
                            ->get();

        return view('items.index', compact('items', 'categories', 'calidadesUnicas', 'request'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('items.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200|unique:items,name', 
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'abc_class' => 'nullable|in:A,B,C',
            'expiry_date' => 'nullable|date', 
        ]);

        try {
            $item = Item::create($data);

            return redirect()->route('items.index') 
                             ->with('success', "Item '{$item->name}' creado con éxito.");

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el item: ' . $e->getMessage())->withInput();
        }
    }
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200|unique:items,name',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $item = Item::create($validator->validated());
            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create item.'], 500);
        }
    }
    public function show($id)
    {
        $item = Item::with([
            'category',
            'presentations.itemLocations.storageZone' 
        ])->find($id);

        if (!$item) {
            return redirect()->route('dashboard')->with('error', 'Item no encontrado');
        }

        return view('items.show', compact('item'));
    }
    public function edit($id)
{
    $item = Item::find($id);

    if (!$item) {
        return redirect()->route('dashboard')->with('error', 'Item no encontrado');
    }

    $categories = Category::orderBy('name')->get();
    $suppliers = Supplier::orderBy('name')->get();

    return view('items.edit', compact('item', 'categories', 'suppliers'));
}
    public function update(Request $request, $id) 
    {
        $item = Item::find($id); 

        if (!$item) {
            return redirect()->route('items.index')->with('error', 'No se pudo encontrar el item para actualizar');
        }

        $data = $request->validate([
             'name' => [
                 'sometimes', 
                 'required',
                 'string',
                 'max:200',
                 Rule::unique('items')->ignore($item->id), 
             ],
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'abc_class' => 'nullable|in:A,B,C',
            'expiry_date' => 'nullable|date',
        ]);

        $item->update($data);

        return redirect()->route('items.show', $item->id)->with('success', 'Item actualizado correctamente');
    }

    public function destroy($id) // Puedes usar Route-Model Binding: public function destroy(Item $item)
    {
       $item = Item::find($id); // O findOrFail($id)

       if (!$item) {
           return redirect()->route('dashboard')->with('error', 'Item no encontrado');
       }

       $itemName = $item->name; 
       $item->delete(); 

       return redirect()->route('dashboard')->with('success', "Item '{$itemName}' eliminado lógicamente");
    }
}
