<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class AddToCartButton extends Component
{
    public int $productId;
    public string $productName = '';

    public function mount(int $productId, string $productName = '')
    {
        $this->productId = $productId;
        $this->productName = $productName;
    }

    public function addToCart()
    {
        $product = Product::find($this->productId);
        if (!$product || !$product->is_active || $product->stock <= 0) {
            $this->dispatch('toast', message: 'สินค้านี้ไม่สามารถเพิ่มลงตะกร้าได้', type: 'error');
            return;
        }

        $cart = session('cart', []);
        if (isset($cart[$this->productId])) {
            $cart[$this->productId]['quantity'] = min($cart[$this->productId]['quantity'] + 1, $product->stock);
        } else {
            $cart[$this->productId] = [
                'product_id' => $this->productId,
                'quantity'   => 1,
            ];
        }

        session(['cart' => $cart]);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: "เพิ่ม \"{$product->name}\" ลงตะกร้าแล้ว", type: 'success');
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
