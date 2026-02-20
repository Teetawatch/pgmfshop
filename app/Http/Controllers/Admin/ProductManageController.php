<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.form', ['product' => null, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:book,clothing,other',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'pages' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'upload_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $images = $this->handleImageUploads($request);

        $sizes = $request->product_type === 'clothing' && $request->sizes ? array_map('trim', explode(',', $request->sizes)) : [];
        $colors = $request->product_type === 'clothing' && $request->colors ? array_map('trim', explode(',', $request->colors)) : [];

        $product = Product::create([
            ...$request->only(['name', 'category_id', 'product_type', 'description', 'price', 'original_price', 'stock', 'sku', 'weight']),
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'images' => $images,
            'publisher' => $request->product_type === 'book' ? $request->publisher : null,
            'pages' => $request->product_type === 'book' ? $request->pages : null,
            'genres' => $request->product_type === 'book' && $request->genres ? array_map('trim', explode(',', $request->genres)) : [],
            'authors' => $request->product_type === 'book' && $request->authors ? array_map('trim', explode(',', $request->authors)) : [],
            'sizes' => $sizes,
            'colors' => $colors,
            'material' => $request->product_type === 'clothing' ? $request->material : null,
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
            'is_featured' => $request->boolean('is_featured'),
            'is_new' => $request->boolean('is_new'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Create variants for clothing products
        if ($request->product_type === 'clothing') {
            $this->syncVariants($product, $sizes, $colors, $request->input('variant_stock', []));
        }

        return redirect()->route('admin.products.index')->with('success', 'เพิ่มสินค้าสำเร็จ');
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:book,clothing,other',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'pages' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'upload_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle existing images (keep or remove)
        $existingImages = $request->input('existing_images', []);
        $newImages = $this->handleImageUploads($request);
        $images = array_merge($existingImages, $newImages);

        // Delete removed images
        $oldImages = $product->images ?? [];
        foreach ($oldImages as $oldImg) {
            if (!in_array($oldImg, $existingImages)) {
                $fullPath = public_path(ltrim($oldImg, '/'));
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }
        }

        $sizes = $request->product_type === 'clothing' && $request->sizes ? array_map('trim', explode(',', $request->sizes)) : [];
        $colors = $request->product_type === 'clothing' && $request->colors ? array_map('trim', explode(',', $request->colors)) : [];

        $product->update([
            ...$request->only(['name', 'category_id', 'product_type', 'description', 'price', 'original_price', 'sku', 'weight']),
            'images' => $images ?: $product->images,
            'publisher' => $request->product_type === 'book' ? $request->publisher : null,
            'pages' => $request->product_type === 'book' ? $request->pages : null,
            'genres' => $request->product_type === 'book' && $request->genres ? array_map('trim', explode(',', $request->genres)) : [],
            'authors' => $request->product_type === 'book' && $request->authors ? array_map('trim', explode(',', $request->authors)) : [],
            'sizes' => $sizes,
            'colors' => $colors,
            'material' => $request->product_type === 'clothing' ? $request->material : null,
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
            'is_featured' => $request->boolean('is_featured'),
            'is_new' => $request->boolean('is_new'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Sync variants for clothing products
        if ($request->product_type === 'clothing') {
            $this->syncVariants($product, $sizes, $colors, $request->input('variant_stock', []));
        } else {
            // Non-clothing: delete variants and use product-level stock
            $product->variants()->delete();
            $product->update(['stock' => $request->stock]);
        }

        return redirect()->route('admin.products.index')->with('success', 'แก้ไขสินค้าสำเร็จ');
    }

    public function destroy(Product $product)
    {
        // Delete product images
        foreach ($product->images ?? [] as $img) {
            $fullPath = public_path(ltrim($img, '/'));
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'ลบสินค้าสำเร็จ');
    }

    /**
     * Sync product variants for clothing products.
     * Creates/updates variant rows for each size×color combination.
     */
    private function syncVariants(Product $product, array $sizes, array $colors, array $variantStockInput): void
    {
        $existingVariants = $product->variants()->get()->keyBy(function ($v) {
            return ($v->size ?: '') . '|' . ($v->color ?: '');
        });

        // Build the expected combinations
        $combinations = [];
        if (!empty($sizes) && !empty($colors)) {
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $combinations[] = ['size' => $size, 'color' => $color];
                }
            }
        } elseif (!empty($sizes)) {
            foreach ($sizes as $size) {
                $combinations[] = ['size' => $size, 'color' => null];
            }
        } elseif (!empty($colors)) {
            foreach ($colors as $color) {
                $combinations[] = ['size' => null, 'color' => $color];
            }
        }

        $keepKeys = [];
        foreach ($combinations as $combo) {
            $key = ($combo['size'] ?: '') . '|' . ($combo['color'] ?: '');
            $keepKeys[] = $key;
            $stockValue = (int) ($variantStockInput[$key] ?? 0);

            if ($existingVariants->has($key)) {
                $existingVariants[$key]->update(['stock' => $stockValue]);
            } else {
                $product->variants()->create([
                    'size' => $combo['size'],
                    'color' => $combo['color'],
                    'stock' => $stockValue,
                ]);
            }
        }

        // Delete variants that are no longer in the combinations
        $product->variants()->get()->each(function ($v) use ($keepKeys) {
            $key = ($v->size ?: '') . '|' . ($v->color ?: '');
            if (!in_array($key, $keepKeys)) {
                $v->delete();
            }
        });

        // Sync product-level stock = sum of all variant stocks
        $product->syncStockFromVariants();
    }

    private function handleImageUploads(Request $request): array
    {
        $images = [];
        if ($request->hasFile('upload_images')) {
            $dir = public_path('uploads/products');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            foreach ($request->file('upload_images') as $file) {
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $filename);
                $images[] = '/uploads/products/' . $filename;
            }
        }
        return $images;
    }
}
