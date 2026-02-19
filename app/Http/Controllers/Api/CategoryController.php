<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('activeProducts')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }

    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        return response()->json($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        $category = Category::create([
            ...$request->only(['name', 'description', 'image', 'sort_order']),
            'slug' => Str::slug($request->name),
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'ลบหมวดหมู่สำเร็จ']);
    }
}
