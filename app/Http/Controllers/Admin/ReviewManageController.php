<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->latest()->paginate(20);

        $ratingCounts = [
            'all' => Review::count(),
            5 => Review::where('rating', 5)->count(),
            4 => Review::where('rating', 4)->count(),
            3 => Review::where('rating', 3)->count(),
            2 => Review::where('rating', 2)->count(),
            1 => Review::where('rating', 1)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'ratingCounts'));
    }

    public function destroy(Review $review)
    {
        $product = $review->product;
        $review->delete();

        if ($product) {
            $product->updateRating();
        }

        return back()->with('success', 'ลบรีวิวสำเร็จ');
    }
}
