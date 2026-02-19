<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class CartPage extends Component
{
    use WithSeo;

    public $couponCode = '';
    public $discount = 0;

    public function updateQuantity($cartKey, $quantity)
    {
        $cart = session('cart', []);
        if ($quantity <= 0) {
            unset($cart[$cartKey]);
        } else {
            $variantId = $cart[$cartKey]['variant_id'] ?? null;
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                $maxStock = $variant ? $variant->stock : 0;
            } else {
                $productId = $cart[$cartKey]['product_id'] ?? $cartKey;
                $product = Product::find($productId);
                $maxStock = $product ? $product->stock : 0;
            }
            $cart[$cartKey]['quantity'] = min($quantity, $maxStock);
        }
        session(['cart' => $cart]);
        $this->dispatch('cart-updated');
    }

    public function removeItem($cartKey)
    {
        $cart = session('cart', []);
        unset($cart[$cartKey]);
        session(['cart' => $cart]);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'ลบสินค้าออกจากตะกร้าแล้ว', type: 'success');
    }

    public function clearCart()
    {
        session(['cart' => []]);
        $this->discount = 0;
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'ล้างตะกร้าแล้ว', type: 'success');
    }

    public function applyCoupon()
    {
        $subtotal = $this->getSubtotal();
        $code = strtoupper($this->couponCode);

        if ($code === 'WELCOME10') {
            $this->discount = min($subtotal * 0.1, 200);
            $this->dispatch('toast', message: "ใช้คูปองสำเร็จ! ลด ฿" . number_format($this->discount, 0), type: 'success');
        } elseif ($code === 'SAVE100') {
            if ($subtotal >= 1000) {
                $this->discount = 100;
                $this->dispatch('toast', message: 'ใช้คูปองสำเร็จ! ลด ฿100', type: 'success');
            } else {
                $this->dispatch('toast', message: 'ยอดสั่งซื้อขั้นต่ำ ฿1,000', type: 'error');
            }
        } else {
            $this->dispatch('toast', message: 'คูปองไม่ถูกต้อง', type: 'error');
        }
    }

    private function getSubtotal()
    {
        $cart = session('cart', []);
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $subtotal = 0;
        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);
            if ($product) {
                $subtotal += $product->price * $item['quantity'];
            }
        }
        return $subtotal;
    }

    #[On('cart-updated')]
    public function refreshCart()
    {
        // This will trigger a re-render
    }

    public function render()
    {
        $cart = session('cart', []);
        $productIds = collect($cart)->pluck('product_id')->filter()->unique()->values()->toArray();
        if (empty($productIds)) {
            $productIds = array_keys($cart);
        }
        $products = Product::with('category')->whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        foreach ($cart as $cartKey => $item) {
            $productId = $item['product_id'] ?? $cartKey;
            $product = $products->get($productId);
            if ($product) {
                $variantId = $item['variant_id'] ?? null;
                $maxStock = $product->stock;
                if ($variantId) {
                    $variant = ProductVariant::find($variantId);
                    if ($variant) $maxStock = $variant->stock;
                }
                $items[] = [
                    'cart_key' => $cartKey,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'options' => $item['options'] ?? [],
                    'variant_id' => $variantId,
                    'max_stock' => $maxStock,
                ];
            }
        }

        $subtotal = collect($items)->sum(fn($i) => $i['product']->price * $i['quantity']);
        $shipping = $subtotal > 800 ? 0 : 40;
        $total = $subtotal - $this->discount + $shipping;

        $this->setSeo(
            title: 'ตะกร้าสินค้า — PGMF Shop',
            description: 'ตรวจสอบสินค้าในตะกร้าของคุณและดำเนินการสั่งซื้อ',
        );

        return $this->renderWithSeo('livewire.cart-page', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);
    }
}
