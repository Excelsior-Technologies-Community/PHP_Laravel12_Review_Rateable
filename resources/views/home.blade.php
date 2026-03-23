<!DOCTYPE html>
<html>

<head>
    <title>Home</title>

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #020617);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
            font-family: Arial;
        }

        .card {
            background: #1e293b;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            width: 300px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        }

        button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background: #22c55e;
            color: white;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="card">
        <h1>⭐ Review System</h1>

        <a href="/create-product"><button>Create Product</button></a>
        <a href="/reviews"><button>View Products</button></a>
    </div>

</body>

</html>