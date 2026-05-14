<!DOCTYPE html>
<html>

<head>
    <title>Add Rating & Review</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9edf2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #1e293b;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 1.5rem;
            text-align: center;
            width: 400px;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
            font-weight: 600;
            background: linear-gradient(135deg, #1e293b, #334155);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .product-desc {
            color: #64748b;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        /* ⭐ Stars */
        .stars-container {
            margin: 1.5rem 0;
        }

        .stars span {
            font-size: 48px;
            cursor: pointer;
            color: #cbd5e1;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .stars span:hover {
            transform: scale(1.1);
        }

        .stars span.active {
            color: #fbbf24;
            text-shadow: 0 0 10px rgba(251, 191, 36, 0.3);
        }

        .review-input {
            width: 100%;
            padding: 12px 16px;
            margin: 15px 0;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-family: inherit;
            font-size: 0.95rem;
            resize: vertical;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        .review-input:focus {
            outline: none;
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
        }

        .rating-value {
            background: #f1f5f9;
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #475569;
        }

        button {
            margin-top: 20px;
            padding: 12px 24px;
            width: 100%;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            color: white;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(34, 197, 94, 0.3);
        }

        .label {
            text-align: left;
            font-weight: 500;
            margin-bottom: 5px;
            margin-top: 10px;
            color: #334155;
        }
    </style>
</head>

<body>

    <div class="card">

        <h2>{{ $product->name }}</h2>
        <div class="product-desc">{{ $product->description ?: 'No description provided' }}</div>

        <input type="hidden" id="product_id" value="{{ $product->id }}">

        <div class="label">Your Rating ⭐</div>
        <!-- ⭐ Stars -->
        <div class="stars-container">
            <div class="stars" id="stars">
                <span data-index="1">★</span>
                <span data-index="2">★</span>
                <span data-index="3">★</span>
                <span data-index="4">★</span>
                <span data-index="5">★</span>
            </div>
            <p><span class="rating-value" id="ratingValue">0 / 5</span></p>
        </div>

        <div class="label">Your Review (Optional)</div>
        <textarea id="reviewText" class="review-input" rows="3" placeholder="Share your experience with this product..."></textarea>

        <button onclick="submitRating()">Submit Rating & Review</button>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let rating = 0;
            const stars = document.querySelectorAll('#stars span');

            stars.forEach((star, index) => {

                star.addEventListener('click', function() {

                    rating = index + 1;

                    // remove old active
                    stars.forEach(s => s.classList.remove('active'));

                    // add active to selected
                    for (let i = 0; i <= index; i++) {
                        stars[i].classList.add('active');
                    }

                    // update text
                    document.getElementById('ratingValue').innerText = rating + ' / 5';
                });

            });

            // make submit global
            window.submitRating = function() {

                if (rating === 0) {
                    alert("Please select a rating");
                    return;
                }

                const reviewText = document.getElementById('reviewText').value;

                fetch('/review-ajax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            product_id: document.getElementById('product_id').value,
                            rating: rating,
                            review: reviewText
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "/view-review/" + document.getElementById('product_id').value;
                        } else {
                            alert(data.message || 'Something went wrong');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to submit rating');
                    });
            }

        });
    </script>

</body>

</html>