<?php

namespace Tests\Feature;

use App\Models\Product;
use Codebyray\ReviewRateable\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_review_is_saved_pending_for_admin_approval(): void
    {
        $product = Product::create([
            'name' => 'Demo Product',
            'description' => 'Great product',
        ]);

        $response = $this->postJson('/review-ajax', [
            'product_id' => $product->id,
            'rating' => 5,
            'review' => 'This is an excellent product',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reviews', [
            'reviewable_id' => $product->id,
            'reviewable_type' => Product::class,
            'review' => 'This is an excellent product',
            'approved' => false,
        ]);
    }

    public function test_admin_can_approve_review_and_users_can_vote_helpful(): void
    {
        $product = Product::create([
            'name' => 'Vote Product',
            'description' => 'Needs feedback',
        ]);

        $review = $product->addReview([
            'review' => 'Helpful review',
            'approved' => false,
            'ratings' => ['overall' => 4],
        ], 1);

        $approveResponse = $this->post('/admin/reviews/' . $review->id . '/approve');
        $approveResponse->assertRedirect();
        $review->refresh();
        $this->assertTrue((bool) $review->approved);

        $voteResponse = $this->postJson('/reviews/' . $review->id . '/vote', [
            'type' => 'helpful',
        ]);

        $voteResponse->assertStatus(200);
        $this->assertSame(1, (int) $review->fresh()->helpful_count);
    }

    public function test_pending_review_is_shown_on_product_page(): void
    {
        $product = Product::create([
            'name' => 'Pending Product',
            'description' => 'Will be reviewed',
        ]);

        $product->addReview([
            'review' => 'This rating should appear',
            'approved' => false,
            'ratings' => ['overall' => 5],
        ], 1);

        $response = $this->get('/view-review/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('This rating should appear');
        $response->assertSee('Pending Approval');
    }
}
