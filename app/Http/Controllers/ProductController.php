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

    // Create product page (alternative route)
    public function createProductPage()
    {
        return view('create-product');
    }

    // Create product (AJAX)
    public function createProduct(Request $request)
    {
        // validation
        if (!$request->name) {
            return response()->json(['message' => 'Name required'], 400);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Product Created',
            'data' => $product
        ]);
    }

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


    public function getProductsData()
{
    $products = Product::with('reviews')->latest()->get();
    
    $productsWithRatings = $products->map(function($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'avg_rating' => $product->overallAverageRating(),
            'reviews_count' => $product->reviews()->count(),
            'created_at' => $product->created_at,
        ];
    });
    
    return response()->json([
        'products' => $productsWithRatings,
        'total' => $products->count()
    ]);
}
    // Add review with rating AND text comment
    public function addReviewAjax(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        $product = Product::find($request->product_id);

        // Add review with rating and optional comment
        $product->addReview([
            'review' => $request->review,
            'approved' => true,
            'ratings' => [
                'overall' => (int) $request->rating,
            ],
        ], 1);

        return response()->json([
            'success' => true,
            'message' => 'Rating & Review Added Successfully'
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