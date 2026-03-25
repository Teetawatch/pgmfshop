<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use App\Models\Product;
use App\Models\Category;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class ProductsPage extends Component
{
    use WithSeo;

    #[Url]
    public $search = '';

    #[Url]
    public $category = '';

    #[Url]
    public $sort = 'default';

    #[Url]
    public $type = '';

    public $showFilters = false;

    public function updatedSearch()
    {
        // Auto-search on type (debounced in view)
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->sort = 'default';
        $this->type = '';
    }

    public function render()
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('category');

        if ($this->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $this->category));
        }

        if ($this->type) {
            $query->where('product_type', $this->type);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->sort) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'bestselling':
                $query->orderBy('sold', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->limit(50)->get();
        $total = $products->count();
        $currentCategory = $categories->firstWhere('slug', $this->category);

        $titleParts = [];
        if ($currentCategory) {
            $titleParts[] = $currentCategory->name;
        } elseif ($this->search) {
            $titleParts[] = 'ค้นหา "' . $this->search . '"';
        } else {
            $titleParts[] = 'สินค้าทั้งหมด';
        }
        $titleParts[] = 'PGMF Shop';

        $desc = $currentCategory
            ? $currentCategory->description ?: "เลือกซื้อสินค้าหมวด{$currentCategory->name} จาก PGMF Shop สินค้าคุณภาพ จัดส่งทั่วไทย"
            : "เลือกซื้อสินค้าคุณภาพจาก PGMF Shop พบ {$total} รายการ สั่งซื้อง่าย จัดส่งทั่วไทย";

        $this->setSeo(
            title: implode(' — ', $titleParts),
            description: $desc,
        );

        return $this->renderWithSeo('livewire.products-page', [
            'products' => $products,
            'categories' => $categories,
            'total' => $total,
            'currentCategory' => $currentCategory,
        ]);
    }
}
