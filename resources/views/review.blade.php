<!DOCTYPE html>
<html>

<head>
    <title>Reviews</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            background: #0f172a;
            color: white;
            padding: 30px;
        }

        .card {
            max-width: 500px;
            margin: auto;
        }

        textarea {
            width: 100%;
            padding: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #22c55e;
            border: none;
            color: white;
        }

        .stars span {
            font-size: 30px;
            cursor: pointer;
            color: gray;
        }

        .active {
            color: gold;
        }

        .review {
            background: #1e293b;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="card">

        <h2>{{ $product->name }}</h2>

        <input type="hidden" id="product_id" value="{{ $product->id }}">

        <div class="stars" id="stars">
            <span data-value="1">★</span>
            <span data-value="2">★</span>
            <span data-value="3">★</span>
            <span data-value="4">★</span>
            <span data-value="5">★</span>
        </div>

        <textarea id="review"></textarea>
        <button onclick="submitReview()">Submit</button>

        <h3>Average: <span id="avg">0</span></h3>

        <div id="reviews"></div>

    </div>

    <script>
        let rating = 0;

        document.querySelectorAll('#stars span').forEach((star, i) => {
            star.onclick = () => {
                rating = star.dataset.value;
                document.querySelectorAll('#stars span').forEach(s => s.classList.remove('active'));
                for (let j = 0; j <= i; j++) {
                    document.querySelectorAll('#stars span')[j].classList.add('active');
                }
            }
        });

        function submitReview() {
            fetch('/review-ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: document.getElementById('product_id').value,
                    review: document.getElementById('review').value,
                    rating: rating
                })
            }).then(() => loadData());
        }

        function loadData() {
            let id = document.getElementById('product_id').value;

            fetch('/reviews?id=' + id)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    data.reviews.forEach(r => {
                        html += `<div class="review">⭐ ${r.ratings.overall}<br>${r.review}</div>`;
                    });
                    document.getElementById('reviews').innerHTML = html;
                });

            fetch('/average?id=' + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('avg').innerText = data.average_rating;
                });
        }

        loadData();
    </script>

</body>

</html>