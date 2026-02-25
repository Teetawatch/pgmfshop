<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;

class Navbar extends Component
{
    public $categories = [];
    public $searchQuery = '';
    public $searchSuggestions = [];
    public $showSuggestions = false;

    public function mount()
    {
        $this->categories = Category::withCount('products')->get();
    }

    #[On('cart-updated')]
    public function refreshCart()
    {
        // Re-render triggers cartCount recalculation
    }

    #[On('wishlist-updated')]
    public function refreshWishlist()
    {
        // Re-render triggers wishlistCount recalculation
    }

    public function updatedSearchQuery()
    {
        $query = trim($this->searchQuery);
        if (strlen($query) >= 2) {
            $this->searchSuggestions = Product::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhereJsonContains('authors', $query);
                })
                ->select('id', 'name', 'slug', 'price', 'images')
                ->limit(6)
                ->get()
                ->toArray();
            $this->showSuggestions = true;
        } else {
            $this->searchSuggestions = [];
            $this->showSuggestions = false;
        }
    }

    public function closeSuggestions()
    {
        $this->showSuggestions = false;
    }

    public function search()
    {
        $this->showSuggestions = false;
        if (trim($this->searchQuery)) {
            return redirect()->route('products', ['search' => $this->searchQuery]);
        }
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function render()
    {
        $cartCount = count(session('cart', []));
        $wishlistCount = auth()->check() ? Wishlist::where('user_id', auth()->id())->count() : 0;
        return view('livewire.layout.navbar', [
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount,
        ]);
    }
}
