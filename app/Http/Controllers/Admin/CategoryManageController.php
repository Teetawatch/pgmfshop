<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug ?: $this->generateUniqueSlug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'เพิ่มหมวดหมู่สำเร็จ');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug ?: $this->generateUniqueSlug($request->name, $category->id),
            'description' => $request->description,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? $category->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'แก้ไขหมวดหมู่สำเร็จ');
    }

    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);

        // Str::slug returns empty for Thai/non-ASCII text
        if ($slug === '') {
            $slug = 'cat-' . mb_substr(md5($name), 0, 8);
        }

        $original = $slug;
        $counter = 1;
        $query = Category::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $original . '-' . $counter;
            $query = Category::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            $counter++;
        }

        return $slug;
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'ไม่สามารถลบหมวดหมู่นี้ได้ เนื่องจากมีสินค้าอยู่ ' . $category->products()->count() . ' รายการ');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'ลบหมวดหมู่สำเร็จ');
    }
}
