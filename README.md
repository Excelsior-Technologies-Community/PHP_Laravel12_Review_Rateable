# PHP_Laravel12_Review_Rateable


## Project Description

PHP_Laravel12_Review_Rateable is a simple Laravel 12 application that demonstrates how to implement a product review and rating system using the laravel-review-rateable package.

Users can create products, add star ratings, and view average ratings.

This project is beginner-friendly and helps understand how to integrate third-party packages in Laravel.



## Features

- Create Product (Name + Description)
- Add Star Rating (1 to 5)
- View Average Rating
- Display Product List
- Real-time AJAX Requests (No page reload)
- Dark Mode UI Design
- Package-based Review System



## Technologies Used

- PHP 8+
- Laravel 12
- MySQL
- JavaScript (Fetch API - AJAX)
- HTML + CSS (Dark UI)
- Composer (Dependency Manager)



## How It Works

1. Admin/User creates a product.
2. Each product can receive ratings (1–5 stars).
3. Ratings are stored using the laravel-review-rateable package.
4. The system calculates the average rating dynamically.
5. Users can view ratings in real-time using AJAX (no page reload).



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Review_Rateable "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Review_Rateable

```

#### Explanation:

This installs a fresh Laravel 12 project and moves into the project folder.



## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Review_Rateable
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Review_Rateable

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Connects Laravel with MySQL and creates default tables in the database.




## STEP 3: Install Package

### Run:

```
composer require codebyray/laravel-review-rateable:^2.0

```

#### Explanation:

Installs the Review & Rating package to handle reviews and star ratings easily.




## STEP 4: Publish Files

### Run: 

```
php artisan vendor:publish --provider="Codebyray\ReviewRateable\ReviewRateableServiceProvider" --tag=config

```

### Run:

```
php artisan vendor:publish --provider="Codebyray\ReviewRateable\ReviewRateableServiceProvider" --tag=migrations

```

#### Explanation:

Publishes package config and database tables (reviews + ratings).





### STEP 5: Create Product Model + Migration

### Run:

```
php artisan make:model Product -m

```

### Open: database/migrations/xxxx_create_products_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};


```



### Then Run:

```
php artisan migrate

```


### app/Models/Product.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Codebyray\ReviewRateable\Traits\ReviewRateable;

class Product extends Model
{
    use ReviewRateable;

    protected $fillable = [
        'name',
        'description'
    ];
}

```

#### Explanation:

Creates Product model and table to store product data.

Adds review & rating functionality to the Product model.




### STEP 6: Create Controller

### Run:

```
php artisan make:controller ProductController

```

### Open: app/Http/Controllers/ProductController.php

```
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Product list
    public function index()
    {
        $products = Product::latest()->get();
        return view('products', compact('products'));
    }

    // Create page
    public function createPage()
    {
        return view('create-product');
    }

    // Create product (AJAX)
    public function createProduct(Request $request)
    {
        // validation (important)
        if (!$request->name) {
            return response()->json(['message' => 'Name required'], 400);
        }

        $product = Product::create([
            'name' => $request->name,              // ✅ dynamic
            'description' => $request->description // ✅ dynamic
        ]);

        return response()->json([
            'message' => 'Product Created',
            'data' => $product
        ]);
    }

    // Reviews page
    // Add Review Page
    public function addReviewPage($id)
    {
        $product = Product::findOrFail($id);
        return view('add-review', compact('product'));
    }

    // View Review Page
    public function viewReviewPage($id)
    {
        $product = Product::findOrFail($id);
        return view('view-review', compact('product'));
    }

    // Add review
    public function addReviewAjax(Request $request)
    {
        $product = Product::find($request->product_id);

        // ✅ IMPORTANT FIX
        $product->addReview([
            'review' => null,
            'approved' => true,
            'ratings' => [
                'overall' => (int) $request->rating, // 🔥 MUST BE THIS
            ],
        ], 1);

        return response()->json([
            'message' => 'Rating Added'
        ]);
    }

    // Get reviews (product-wise)
    public function getReviews(Request $request)
    {
        $product = Product::find($request->id);

        return response()->json([
            'reviews' => $product->getReviews()
        ]);
    }

    // Average rating
    public function averageRating(Request $request)
    {
        $product = Product::find($request->id);

        return response()->json([
            'average_rating' => $product->overallAverageRating()
        ]);
    }
}

```

#### Explanation:

Handles all logic like product create, review add, and rating display.





## STEP 7: Add Routes

### routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/create', [ProductController::class, 'createPage']);

Route::get('/add-review/{id}', [ProductController::class, 'addReviewPage']);
Route::get('/view-review/{id}', [ProductController::class, 'viewReviewPage']);

Route::post('/product', [ProductController::class, 'createProduct']);
Route::post('/review-ajax', [ProductController::class, 'addReviewAjax']);
Route::get('/reviews', [ProductController::class, 'getReviews']);
Route::get('/average', [ProductController::class, 'averageRating']);

```

#### Explanation:

Defines URLs for pages like product list, create, add review, and view rating.




## STEP 8: Blade File

### resources/views/home.blade.php

```
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

```


### resources/views/create-product.blade.php

```
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

```


### resources/views/products.blade.php

```
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

```


### resources/views/add-review.blade.php

```
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

```



### resources/views/review.blade.php

```
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

```



### resources/views/view-review.blade.php

```
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

```




## STEP 9: Run the App  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Starts local server to run Laravel project in browser.



## Expected Output:

### Product Page:


<img src="screenshots/Screenshot 2026-03-23 112615.png" width="900">


### Create Product Page:


<img src="screenshots/Screenshot 2026-03-23 112637.png" width="900">


### Product List Page:


<img src="screenshots/Screenshot 2026-03-23 112647.png" width="900">


### Add Review Page:


<img src="screenshots/Screenshot 2026-03-23 112710.png" width="900">


### View Review Page:


<img src="screenshots/Screenshot 2026-03-23 112732.png" width="900">



---

## Project Folder Structure:

```
PHP_Laravel12_Review_Rateable/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ProductController.php
│   │
│   └── Models/
│       └── Product.php
│
├── bootstrap/
│
├── config/
│   └── reviewrateable.php   (published config)
│
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_products_table.php
│   │   ├── xxxx_create_reviews_table.php
│   │   └── xxxx_create_ratings_table.php
│   │
│   └── seeders/
│
├── public/
│   └── index.php
│
├── resources/
│   └── views/
│       ├── home.blade.php
│       ├── products.blade.php
│       ├── create-product.blade.php
│       ├── add-review.blade.php
│       ├── review.blade.php
│       └── view-review.blade.php
│
├── routes/
│   └── web.php
│
├── storage/
│
├── tests/
│
├── vendor/
│
├── .env
├── artisan
├── composer.json
└── README.md

```
