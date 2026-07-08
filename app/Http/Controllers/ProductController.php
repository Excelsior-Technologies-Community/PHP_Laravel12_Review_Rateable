<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Codebyray\ReviewRateable\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('products', compact('products'));
    }

    public function createPage()
    {
        return view('create-product');
    }

    public function createProductPage()
    {
        return view('create-product');
    }

    public function createProduct(Request $request)
    {
        if (!$request->name) {
            return response()->json(['message' => 'Name required'], 400);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Product Created',
            'data' => $product,
        ]);
    }

    public function addReviewPage($id)
    {
        $product = Product::findOrFail($id);

        return view('add-review', compact('product'));
    }

    public function viewReviewPage($id)
    {
        $product = Product::findOrFail($id);
        $reviews = $product->reviews()->with('ratings')->latest()->get();

        return view('view-review', compact('product', 'reviews'));
    }

    public function getProductsData()
    {
        $products = Product::with('reviews')->latest()->get();

        $productsWithRatings = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'avg_rating' => $product->overallAverageRating(),
                'reviews_count' => $product->reviews()->where('approved', true)->count(),
                'created_at' => $product->created_at,
            ];
        });

        return response()->json([
            'products' => $productsWithRatings,
            'total' => $products->count(),
        ]);
    }

    public function addReviewAjax(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);

        $review = $product->addReview([
            'review' => $request->review,
            'approved' => false,
            'ratings' => [
                'overall' => (int) $request->rating,
            ],
        ], 1);

        $review->is_verified_purchase = 1;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Your review has been submitted and is pending admin approval.',
        ]);
    }

    public function getReviews(Request $request)
    {
        $product = Product::findOrFail($request->id);

        return response()->json([
            'reviews' => $product->getReviews(),
        ]);
    }

    public function averageRating(Request $request)
    {
        $product = Product::findOrFail($request->id);

        return response()->json([
            'average_rating' => $product->overallAverageRating(),
        ]);
    }

    public function voteReview(Request $request, Review $review)
    {
        $request->validate([
            'type' => 'required|in:helpful,not_helpful',
        ]);

        $field = $request->input('type') === 'helpful' ? 'helpful_count' : 'not_helpful_count';
        $review->increment($field);

        return response()->json([
            'success' => true,
            'helpful_count' => $review->fresh()->helpful_count,
            'not_helpful_count' => $review->fresh()->not_helpful_count,
        ]);
    }

    public function adminReviews()
    {
        $pendingReviews = Review::where('approved', false)->latest()->get();
        $approvedReviews = Review::where('approved', true)->latest()->get();

        return view('admin-reviews', compact('pendingReviews', 'approvedReviews'));
    }

    public function approveReview(Review $review)
    {
        $review->update(['approved' => true]);

        return back()->with('success', 'Review approved successfully.');
    }

    public function rejectReview(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review removed successfully.');
    }

    public function topRated(Request $request)
    {
        $sort = $request->get('sort', 'rating');

        $products = Product::with('reviews')
            ->get()
            ->map(function ($product) {
                $product->avg_rating = $product->overallAverageRating();
                $product->review_count = $product->reviews()->where('approved', true)->count();

                return $product;
            });

        if ($sort === 'reviews') {
            $products = $products->sortByDesc('review_count')->values();
        } else {
            $products = $products->sortByDesc('avg_rating')->values();
        }

        return view('top-rated', compact('products', 'sort'));
    }
}

