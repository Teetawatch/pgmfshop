<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('featured')) {
            $query->featured();
        }

        if ($request->filled('new')) {
            $query->newArrivals();
        }

        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        $sortBy = $request->get('sort', 'default');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'best_selling':
                $query->orderBy('sold', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        $perPage = $request->get('per_page', 16);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'reviews.user'])->where('slug', $slug)->firstOrFail();

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'sometimes|in:book,clothing,other',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'tags' => 'nullable|array',
            'publisher' => 'nullable|string|max:255',
            'authors' => 'nullable|array',
            'genres' => 'nullable|array',
            'pages' => 'nullable|integer|min:0',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'material' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
        ]);

        $product = Product::create([
            ...$request->only([
                'category_id', 'product_type', 'name', 'description', 'price', 'original_price',
                'stock', 'sku', 'weight', 'images', 'tags', 'publisher', 'authors', 'genres', 'pages',
                'sizes', 'colors', 'material', 'is_featured', 'is_new',
            ]),
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
        ]);

        return response()->json($product->load('category'), 201);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'product_type' => 'sometimes|in:book,clothing,other',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'tags' => 'nullable|array',
            'publisher' => 'nullable|string|max:255',
            'authors' => 'nullable|array',
            'genres' => 'nullable|array',
            'pages' => 'nullable|integer|min:0',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'material' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $product->update($request->all());

        return response()->json($product->load('category'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'ลบสินค้าสำเร็จ']);
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $request->stock]);

        return response()->json($product);
    }
}
