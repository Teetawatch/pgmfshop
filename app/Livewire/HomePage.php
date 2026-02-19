<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Banner;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class HomePage extends Component
{
    use WithSeo;

    public $activeTab = 'all';
    public $visibleCount = 16;
    public $sortBy = 'default';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->visibleCount = 16;
    }

    public function loadMore()
    {
        $this->visibleCount += 8;
    }

    public function render()
    {
        $query = Product::with('category');

        if ($this->activeTab === 'hot') {
            $query->where('is_featured', true);
        } elseif ($this->activeTab === 'new') {
            $query->where('is_new', true);
        } elseif ($this->activeTab === 'recommended') {
            $query->orderByDesc('rating');
        }

        if ($this->sortBy === 'price_asc') {
            $query->orderBy('price');
        } elseif ($this->sortBy === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($this->sortBy === 'in_stock') {
            $query->where('stock', '>', 0)->orderByDesc('stock');
        }

        $products = $query->limit(50)->get();
        $totalSold = $this->activeTab === 'all' ? Product::sum('sold') : 0;

        $banners = Banner::active()->get();

        $this->setSeo(
            title: 'PGMF Shop — ร้านค้าออนไลน์ Progressive Movement Foundation',
            description: 'ร้านค้าออนไลน์ Progressive Movement Foundation Shop รวมหนังสือ เสื้อผ้า และสินค้าคุณภาพ สั่งซื้อง่าย จัดส่งทั่วไทย',
        );

        return $this->renderWithSeo('livewire.home-page', [
            'products' => $products,
            'totalSold' => $totalSold,
            'banners' => $banners,
        ]);
    }
}
