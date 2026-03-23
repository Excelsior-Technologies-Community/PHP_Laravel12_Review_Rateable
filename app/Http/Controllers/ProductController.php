<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Product list
    public function index()
    {
        $products = Product::latest()->get();
        return view('products', compact('products'));
    }

    // Create page
    public function createPage()
    {
        return view('create-product');
    }

    // Create product (AJAX)
    public function createProduct(Request $request)
    {
        // validation (important)
        if (!$request->name) {
            return response()->json(['message' => 'Name required'], 400);
        }

        $product = Product::create([
            'name' => $request->name,              // ✅ dynamic
            'description' => $request->description // ✅ dynamic
        ]);

        return response()->json([
            'message' => 'Product Created',
            'data' => $product
        ]);
    }

    // Reviews page
    // Add Review Page
    public function addReviewPage($id)
    {
        $product = Product::findOrFail($id);
        return view('add-review', compact('product'));
    }

    // View Review Page
    public function viewReviewPage($id)
    {
        $product = Product::findOrFail($id);
        return view('view-review', compact('product'));
    }

    // Add review
    public function addReviewAjax(Request $request)
    {
        $product = Product::find($request->product_id);

        // ✅ IMPORTANT FIX
        $product->addReview([
            'review' => null,
            'approved' => true,
            'ratings' => [
                'overall' => (int) $request->rating, // 🔥 MUST BE THIS
            ],
        ], 1);

        return response()->json([
            'message' => 'Rating Added'
        ]);
    }

    // Get reviews (product-wise)
    public function getReviews(Request $request)
    {
        $product = Product::find($request->id);

        return response()->json([
            'reviews' => $product->getReviews()
        ]);
    }

    // Average rating
    public function averageRating(Request $request)
    {
        $product = Product::find($request->id);

        return response()->json([
            'average_rating' => $product->overallAverageRating()
        ]);
    }
}