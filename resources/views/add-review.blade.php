<!DOCTYPE html>
<html>

<head>
    <title>Add Rating</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            background: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            font-family: Arial;
        }

        .card {
            background: #1e293b;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            width: 350px;
        }

        /* ⭐ Stars */
        .stars span {
            font-size: 40px;
            cursor: pointer;
            color: gray;
            transition: 0.2s;
        }

        /* ⭐ Active Stars */
        .stars span.active {
            color: gold;
        }

        button {
            margin-top: 15px;
            padding: 10px;
            width: 100%;
            background: #22c55e;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="card">

        <h2>{{ $product->name }}</h2>

        <input type="hidden" id="product_id" value="{{ $product->id }}">

        <!-- ⭐ Stars -->
        <div class="stars" id="stars">
            <span data-index="1">★</span>
            <span data-index="2">★</span>
            <span data-index="3">★</span>
            <span data-index="4">★</span>
            <span data-index="5">★</span>
        </div>

        <p>Selected: <span id="ratingValue">0</span></p>

        <button onclick="submitRating()">Submit Rating</button>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let rating = 0;
            const stars = document.querySelectorAll('#stars span');

            stars.forEach((star, index) => {

                star.addEventListener('click', function () {

                    rating = index + 1;

                    // remove old active
                    stars.forEach(s => s.classList.remove('active'));

                    // add active to selected
                    for (let i = 0; i <= index; i++) {
                        stars[i].classList.add('active');
                    }

                    // update text
                    document.getElementById('ratingValue').innerText = rating;
                });

            });

            // make submit global
            window.submitRating = function () {

                if (rating === 0) {
                    alert("Please select rating");
                    return;
                }

                fetch('/review-ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: document.getElementById('product_id').value,
                        rating: rating
                    })
                })
                    .then(() => {
                        window.location.href = "/view-review/" + document.getElementById('product_id').value;
                    });
            }

        });
    </script>

</body>

</html>