<!DOCTYPE html>
<html>

<head>
    <title>Top Rated Products</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9edf2 100%);
            min-height: 100vh;
            padding: 30px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e293b, #334155);
            -webkit-background-clip: text;
            color: transparent;
        }

        /* FILTER BUTTONS */
        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filters button {
            padding: 8px 14px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            background: #f1f5f9;
            color: #475569;
            font-weight: 500;
        }

        .filters button.active {
            background: #f59e0b;
            color: white;
        }

        .card {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .rating-badge {
            display: inline-flex;
            gap: 5px;
            background: #fef3c7;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        .star { color: #fbbf24; }

        .hide { display: none; }

        .back-btn {
            background: #f1f5f9;
            padding: 10px 18px;
            border-radius: 30px;
            text-decoration: none;
            color: #475569;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <h1>⭐ Top Rated Products</h1>
        <a href="/reviews" class="back-btn">← Back</a>
    </div>

    <!-- FILTER BUTTONS -->
    <div class="filters">
        <button class="active" onclick="filterProducts('all')">All</button>
        <button onclick="filterProducts('5')">5 ★</button>
        <button onclick="filterProducts('4')">4 ★ & Above</button>
    </div>

    <!-- LIST -->
    <div id="productList">

        @if(count($products) > 0)

            @foreach($products as $product)

                <div class="card" data-rating="{{ $product->avg_rating }}">

                    <div class="rating-badge">
                        <span class="star">⭐</span>
                        <span>{{ number_format($product->avg_rating, 1) }}</span>
                    </div>

                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description ?? 'No description available' }}</p>

                </div>

            @endforeach

        @else

            <div class="no-data">
                No Top Rated Products Found
            </div>

        @endif

    </div>

</div>

<!-- FILTER SCRIPT -->
<script>
function filterProducts(type) {

    let cards = document.querySelectorAll('.card');
    let buttons = document.querySelectorAll('.filters button');

    buttons.forEach(btn => btn.classList.remove('active'));

    event.target.classList.add('active');

    cards.forEach(card => {

        let rating = parseFloat(card.getAttribute('data-rating'));

        if(type === 'all') {
            card.style.display = 'block';
        }
        else if(type === '5') {
            card.style.display = rating >= 5 ? 'block' : 'none';
        }
        else if(type === '4') {
            card.style.display = rating >= 4 ? 'block' : 'none';
        }

    });
}
</script>

</body>
</html>