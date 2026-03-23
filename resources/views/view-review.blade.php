<!DOCTYPE html>
<html>

<head>
    <title>Rating Details</title>

    <style>
        body {
            background: #0f172a;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px;
            color: white;
            font-family: Arial;
        }

        .card {
            background: #1e293b;
            padding: 30px;
            border-radius: 12px;
            width: 450px;
            text-align: center;
        }

        .star {
            font-size: 30px;
        }

        .gold {
            color: gold;
        }

        .gray {
            color: gray;
        }

        .review-box {
            background: #0f172a;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            text-align: left;
        }

        .title {
            margin-top: 20px;
            border-bottom: 1px solid #334155;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="card">

        <!-- PRODUCT INFO -->
        <h2>{{ $product->name }}</h2>
        <p>{{ $product->description }}</p>

        @php
            $avg = $product->overallAverageRating() ? round($product->overallAverageRating()) : 0;
            $reviews = $product->reviews;
            $total = count($reviews);
        @endphp

        <!-- ⭐ AVERAGE -->
        <h3>Average Rating</h3>

        <div>
            @for($i = 1; $i <= 5; $i++)
                <span class="star {{ $i <= $avg ? 'gold' : 'gray' }}">★</span>
            @endfor
        </div>

        <p>{{ $avg }} / 5</p>




    </div>

</body>

</html>