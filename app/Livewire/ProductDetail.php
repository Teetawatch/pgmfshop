<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\OrderItem;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class ProductDetail extends Component
{
    use WithSeo;

    public Product $product;
    public $relatedProducts = [];
    public $selectedImage = 0;
    public $quantity = 1;

    // Clothing options
    public $selectedSize = '';
    public $selectedColor = '';

    // Review form
    public $reviewRating = 5;
    public $reviewComment = '';
    public $showReviewForm = false;

    public function mount($slug)
    {
        $this->product = Product::with(['category', 'reviews.user', 'wishlists', 'variants'])->where('slug', $slug)->firstOrFail();
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->limit(4)
            ->get();

        $images = is_array($this->product->images) ? $this->product->images : [];
        $ogImage = !empty($images) ? $images[0] : '';
        $desc = $this->product->description
            ? mb_substr(strip_tags($this->product->description), 0, 160)
            : "{$this->product->name} — สั่งซื้อออนไลน์จาก PGMF Shop ราคา ฿" . number_format($this->product->price, 0);

        $this->setSeo(
            title: $this->product->name . ' — PGMF Shop',
            description: $desc,
            image: $ogImage,
            ogType: 'product',
        );
    }

    public function selectImage($index)
    {
        $this->selectedImage = $index;
    }

    /**
     * Get the available stock for the currently selected variant (or product-level for non-clothing)
     */
    public function getAvailableStock(): int
    {
        if ($this->product->isClothing() && $this->product->variants->isNotEmpty()) {
            $variant = $this->getSelectedVariant();
            return $variant ? $variant->stock : 0;
        }
        return $this->product->stock;
    }

    /**
     * Find the variant matching current selectedSize + selectedColor
     */
    public function getSelectedVariant(): ?ProductVariant
    {
        if (!$this->product->isClothing() || $this->product->variants->isEmpty()) {
            return null;
        }

        return $this->product->variants
            ->where('size', $this->selectedSize ?: null)
            ->where('color', $this->selectedColor ?: null)
            ->where('is_active', true)
            ->first();
    }

    public function updatedSelectedSize()
    {
        $this->quantity = 1;
    }

    public function updatedSelectedColor()
    {
        $this->quantity = 1;
    }

    public function incrementQty()
    {
        $maxStock = $this->getAvailableStock();
        if ($this->quantity < $maxStock) {
            $this->quantity++;
        } else {
            $this->dispatch('stock-exceeded');
        }
    }

    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        $availableStock = $this->getAvailableStock();

        // Validate clothing options
        if ($this->product->isClothing()) {
            $hasSizes = !empty($this->product->sizes) || $this->product->variants->whereNotNull('size')->isNotEmpty();
            $hasColors = !empty($this->product->colors) || $this->product->variants->whereNotNull('color')->isNotEmpty();

            if ($hasSizes && empty($this->selectedSize)) {
                $this->dispatch('toast', message: 'กรุณาเลือกไซส์', type: 'error');
                return;
            }
            if ($hasColors && empty($this->selectedColor)) {
                $this->dispatch('toast', message: 'กรุณาเลือกสี/ลาย', type: 'error');
                return;
            }
        }

        if ($availableStock <= 0 || $this->quantity > $availableStock) {
            $this->dispatch('stock-exceeded');
            return;
        }

        $cart = session('cart', []);
        $productId = $this->product->id;

        // Build options array
        $options = [];
        if ($this->selectedSize) $options['size'] = $this->selectedSize;
        if ($this->selectedColor) $options['color'] = $this->selectedColor;

        // Get variant_id if applicable
        $variantId = null;
        $variant = $this->getSelectedVariant();
        if ($variant) {
            $variantId = $variant->id;
        }

        // Composite cart key: productId or productId_hash for clothing variants
        $cartKey = $productId;
        if (!empty($options)) {
            $cartKey = $productId . '_' . md5(json_encode($options));
        }

        $currentInCart = $cart[$cartKey]['quantity'] ?? 0;

        if (($currentInCart + $this->quantity) > $availableStock) {
            $this->dispatch('stock-exceeded');
            return;
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $currentInCart + $this->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $this->quantity,
                'options' => $options,
            ];
        }

        session(['cart' => $cart]);
        $this->dispatch('cart-updated');

        $optionText = '';
        if ($this->selectedSize) $optionText .= " ไซส์ {$this->selectedSize}";
        if ($this->selectedColor) $optionText .= " สี {$this->selectedColor}";
        $this->dispatch('toast', message: "เพิ่ม \"{$this->product->name}\"{$optionText} x{$this->quantity} ลงตะกร้าแล้ว", type: 'success');
    }

    public function buyNow()
    {
        $this->addToCart();
        return redirect()->route('cart');
    }

    public function toggleReviewForm()
    {
        $this->showReviewForm = !$this->showReviewForm;
    }

    public function setRating($rating)
    {
        $this->reviewRating = max(1, min(5, $rating));
    }

    public function submitReview()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewComment' => 'required|string|min:5|max:1000',
        ], [
            'reviewComment.required' => 'กรุณาเขียนรีวิว',
            'reviewComment.min' => 'รีวิวต้องมีอย่างน้อย 5 ตัวอักษร',
            'reviewComment.max' => 'รีวิวต้องไม่เกิน 1,000 ตัวอักษร',
        ]);

        $userId = auth()->id();

        // Check if already reviewed
        $existing = Review::where('product_id', $this->product->id)->where('user_id', $userId)->first();
        if ($existing) {
            $this->dispatch('toast', message: 'คุณรีวิวสินค้านี้ไปแล้ว', type: 'error');
            return;
        }

        // Check if user has purchased this product (delivered orders only)
        $hasPurchased = OrderItem::where('product_id', $this->product->id)
            ->whereHas('order', fn($q) => $q->where('user_id', $userId)->where('status', 'delivered'))
            ->exists();

        if (!$hasPurchased) {
            $this->dispatch('toast', message: 'คุณต้องซื้อและได้รับสินค้าแล้วจึงจะรีวิวได้', type: 'error');
            return;
        }

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => $userId,
            'rating' => $this->reviewRating,
            'comment' => $this->reviewComment,
        ]);

        $this->product->updateRating();
        $this->product->load('reviews.user');

        $this->reviewRating = 5;
        $this->reviewComment = '';
        $this->showReviewForm = false;

        $this->dispatch('toast', message: 'ขอบคุณสำหรับรีวิว!', type: 'success');
    }

    public function render()
    {
        $canReview = false;
        $hasReviewed = false;

        if (auth()->check()) {
            $userId = auth()->id();
            $hasReviewed = Review::where('product_id', $this->product->id)->where('user_id', $userId)->exists();
            if (!$hasReviewed) {
                $canReview = OrderItem::where('product_id', $this->product->id)
                    ->whereHas('order', fn($q) => $q->where('user_id', $userId)->where('status', 'delivered'))
                    ->exists();
            }
        }

        // Build variant stock map for the blade view
        $variantStockMap = [];
        if ($this->product->isClothing() && $this->product->variants->isNotEmpty()) {
            foreach ($this->product->variants as $v) {
                $key = ($v->size ?: '') . '|' . ($v->color ?: '');
                $variantStockMap[$key] = $v->is_active ? $v->stock : 0;
            }
        }

        return $this->renderWithSeo('livewire.product-detail', [
            'canReview' => $canReview,
            'hasReviewed' => $hasReviewed,
            'variantStockMap' => $variantStockMap,
            'currentVariantStock' => $this->getAvailableStock(),
        ]);
    }
}
