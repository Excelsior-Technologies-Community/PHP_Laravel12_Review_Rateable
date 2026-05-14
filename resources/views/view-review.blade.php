<!DOCTYPE html>
<html>

<head>
    <title>{{ $product->name }} - Reviews</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9edf2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e293b, #334155);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 8px;
        }

        .product-desc {
            color: #64748b;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .average-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .average-stars {
            margin: 12px 0;
        }

        .star {
            font-size: 32px;
            display: inline-block;
        }

        .gold {
            color: #fbbf24;
        }

        .gray {
            color: #cbd5e1;
        }

        .avg-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }

        .avg-label {
            color: #64748b;
            margin-top: 8px;
        }

        .reviews-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 24px 0 16px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reviews-title span {
            background: #e2e8f0;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            color: #475569;
        }

        .review-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }

        .review-card:hover {
            transform: translateX(4px);
            border-color: #cbd5e1;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .review-stars {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .review-stars .review-star {
            font-size: 18px;
            color: #cbd5e1;
        }

        .review-stars .review-star.filled {
            color: #fbbf24;
        }

        .review-date {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .review-text {
            color: #334155;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .empty-reviews {
            text-align: center;
            padding: 40px;
            background: #f8fafc;
            border-radius: 16px;
            color: #64748b;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            padding: 10px 20px;
            border-radius: 40px;
            text-decoration: none;
            color: #475569;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background: #e2e8f0;
            transform: translateX(-4px);
        }

        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }
    </style>
</head>

<body>

<div class="container">
    <a href="/reviews" class="back-button">← Back to Products</a>

    <div class="card">
        <h1>{{ $product->name }}</h1>
        <div class="product-desc">{{ $product->description ?: 'No description provided' }}</div>

        @php
            $avg = $product->overallAverageRating();
            $avgRounded = $avg ? round($avg, 1) : 0;
            $reviews = $product->getReviews();
            $total = count($reviews);
        @endphp

        <div class="average-section">
            <h3 style="color: #475569; margin-bottom: 8px;">Average Rating</h3>
            <div class="average-stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star {{ $i <= round($avgRounded) ? 'gold' : 'gray' }}">★</span>
                @endfor
            </div>
            <div class="avg-number">{{ number_format($avgRounded, 1) }}</div>
            <div class="avg-label">out of 5 • {{ $total }} {{ Str::plural('review', $total) }}</div>
        </div>
    </div>

    <div class="reviews-title">
        📝 Customer Reviews <span>{{ $total }}</span>
    </div>

    @if($total > 0)
        @foreach($reviews as $review)
            <div class="review-card">

                <div class="review-header">

                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="review-star {{ $i <= ($review['ratings']['overall'] ?? 0) ? 'filled' : '' }}">★</span>
                        @endfor

                        {{-- VERIFIED PURCHASE BADGE ADDED HERE --}}
                        @if($review['is_verified_purchase'] ?? false)
                            <span style="color:#22c55e;font-size:12px;margin-left:8px;">
                                ✔ Verified Purchase
                            </span>
                        @endif
                    </div>

                    <div class="review-date">
                        {{ isset($review['created_at']) ? \Carbon\Carbon::parse($review['created_at'])->format('M d, Y') : 'Recently' }}
                    </div>

                </div>

                @if(!empty($review['review']))
                    <div class="review-text">“{{ e($review['review']) }}”</div>
                @else
                    <div class="review-text" style="color: #94a3b8; font-style: italic;">No written review</div>
                @endif

            </div>
        @endforeach
    @else
        <div class="empty-reviews">
            <div style="font-size: 48px; margin-bottom: 12px;">⭐</div>
            <p>No reviews yet. Be the first to review this product!</p>
            <a href="/add-review/{{ $product->id }}" style="color: #22c55e; text-decoration: none; margin-top: 12px; display: inline-block;">Write a review →</a>
        </div>
    @endif
</div>

</body>
</html>