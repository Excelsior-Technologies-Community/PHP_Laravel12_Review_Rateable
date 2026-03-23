<!DOCTYPE html>
<html>

<head>
    <title>Products</title>

    <style>
        body {
            background: #0f172a;
            color: white;
            padding: 30px;
            font-family: Arial;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        /* Header with button */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            background: #1e293b;
            padding: 15px;
            margin: 15px 0;
            border-radius: 10px;
        }

        button {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            background: #22c55e;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .create-btn {
            background: #38bdf8;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <h1>Products</h1>
           <a href="/create">
    <button class="create-btn">+ Create Product</button>
</a>
        </div>

        @foreach($products as $product)
            <div class="card">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>

                <a href="/add-review/{{ $product->id }}">
                    <button>Add Review</button>
                </a>

                <a href="/view-review/{{ $product->id }}">
                    <button>View Rating</button>
                </a>
            </div>
        @endforeach

    </div>

</body>

</html>