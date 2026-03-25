<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Wishlist;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class WishlistPage extends Component
{
    use WithSeo;

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function removeFromWishlist(int $productId)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();

        $this->dispatch('wishlist-updated');
        $this->dispatch('toast', message: 'นำออกจากรายการโปรดแล้ว', type: 'info');
    }

    public function addToCart(int $productId)
    {
        $product = \App\Models\Product::find($productId);
        if (!$product || !$product->is_active || $product->stock <= 0) {
            $this->dispatch('toast', message: 'สินค้านี้ไม่สามารถเพิ่มลงตะกร้าได้', type: 'error');
            return;
        }

        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = min($cart[$productId]['quantity'] + 1, $product->stock);
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: "เพิ่ม \"{$product->name}\" ลงตะกร้าแล้ว", type: 'success');
    }

    #[On('wishlist-updated')]
    public function refreshWishlist()
    {
        // Re-render
    }

    public function render()
    {
        $this->setSeo(
            title: 'รายการโปรด — PGMF Shop',
            description: 'รายการสินค้าที่คุณบันทึกไว้เพื่อดูภายหลัง',
        );

        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('product.category')
            ->latest()
            ->get();

        return $this->renderWithSeo('livewire.account.wishlist-page', [
            'wishlists' => $wishlists,
        ]);
    }
}
