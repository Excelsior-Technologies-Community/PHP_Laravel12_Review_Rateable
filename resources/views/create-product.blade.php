<!DOCTYPE html>
<html>

<head>
    <title>Create Product</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #020617);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
        }

        .card {
            background: #1e293b;
            padding: 30px;
            border-radius: 12px;
            width: 350px;
            text-align: center;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: none;
        }

        button {
            width: 100%;
            padding: 10px;
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
        <h2>Create Product</h2>

        <input type="text" id="name" placeholder="Product Name">
        <textarea id="desc" placeholder="Description"></textarea>

        <button onclick="createProduct()">Save</button>
    </div>

    <script>
        function createProduct() {
            fetch('/product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    description: document.getElementById('desc').value
                })
            })
                .then(res => res.json())
                .then(() => {
                    alert("Created!");
                    window.location.href = "/";
                });
        }
    </script>

</body>

</html>