<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get(); // SoftDeletes excluye automáticamente deleted_at != null
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255',
        ]);

        $category = Category::create($data);

        return response()->json(['message' => 'Category created', 'id' => $category->id]);
    }

    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        try {
            $category = Category::create($validator->validated()); 
            return response()->json(['success' => true, 'category' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create category.'], 500); // Internal Server Error
        }
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string|max:255',
        ]);

        $category->update($data);

        return response()->json(['message' => 'Category updated']);
    }


    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }

    
    public function restore($id)
    {
        $category = Category::withTrashed()->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->restore();

        return response()->json(['message' => 'Category restored']);
    }
}
