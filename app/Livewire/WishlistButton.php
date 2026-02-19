<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Wishlist;

class WishlistButton extends Component
{
    public int $productId;
    public bool $isWishlisted = false;
    public string $size = 'md'; // sm, md

    public function mount(int $productId, string $size = 'md')
    {
        $this->productId = $productId;
        $this->size = $size;

        if (auth()->check()) {
            $this->isWishlisted = Wishlist::where('user_id', auth()->id())
                ->where('product_id', $this->productId)
                ->exists();
        }
    }

    public function toggle()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();

        if ($this->isWishlisted) {
            Wishlist::where('user_id', $userId)
                ->where('product_id', $this->productId)
                ->delete();
            $this->isWishlisted = false;
            $this->dispatch('wishlist-updated');
            $this->dispatch('toast', message: 'นำออกจากรายการโปรดแล้ว', type: 'info');
        } else {
            Wishlist::firstOrCreate([
                'user_id' => $userId,
                'product_id' => $this->productId,
            ]);
            $this->isWishlisted = true;
            $this->dispatch('wishlist-updated');
            $this->dispatch('toast', message: 'เพิ่มในรายการโปรดแล้ว', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.wishlist-button');
    }
}
