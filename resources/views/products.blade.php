<!DOCTYPE html>
<html>

<head>
    <title>Products - Review System</title>

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

        /* Header with button */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e293b, #334155);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* Search Box */
        .search-container {
            margin-bottom: 25px;
        }

        .search-box {
            display: flex;
            gap: 10px;
            background: white;
            border-radius: 50px;
            padding: 5px 5px 5px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .search-box:focus-within {
            border-color: #22c55e;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.15);
        }

        .search-box input {
            flex: 1;
            padding: 12px 0;
            border: none;
            outline: none;
            font-size: 0.95rem;
            background: transparent;
            font-family: inherit;
        }

        .search-box button {
            padding: 8px 24px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            color: white;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .search-box button:hover {
            transform: scale(1.02);
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
        }

        .clear-search {
            background: #f1f5f9 !important;
            color: #64748b !important;
        }

        .clear-search:hover {
            background: #e2e8f0 !important;
            transform: scale(1.02);
        }

        /* Stats Row */
        .stats-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .result-count {
            background: #f1f5f9;
            padding: 5px 12px;
            border-radius: 20px;
        }

        /* Card */
        .card {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .card p {
            color: #64748b;
            margin-bottom: 15px;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Rating Badge */
        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fef3c7;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 12px;
            font-size: 0.8rem;
        }

        .rating-badge .star-icon {
            color: #fbbf24;
        }

        /* Buttons */
        .button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .review-btn {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .review-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .view-btn {
            background: #f1f5f9;
            color: #475569;
        }

        .view-btn:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .create-btn {
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color: white;
            padding: 10px 24px;
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        }

        a {
            text-decoration: none;
        }

        /* No results */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            color: #64748b;
        }

        .no-results .icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                text-align: center;
            }

            .button-group {
                justify-content: center;
            }

            .search-box {
                flex-direction: column;
                border-radius: 20px;
                padding: 10px;
            }

            .search-box input {
                padding: 10px;
            }

            .search-box button {
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <h1>✨ Products</h1>
            <a href="/create">
                <button class="create-btn">+ Create New Product</button>
            </a>
        </div>

        <!-- SEARCH BOX -->
        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="🔍 Search products by name or description..." autocomplete="off">
                <button onclick="searchProducts()">Search</button>
                <button onclick="clearSearch()" class="clear-search">Clear</button>
            </div>
        </div>

        <!-- STATS -->
        <div class="stats-row">
            <span id="totalCount" class="result-count">Loading...</span>
            <span id="searchTerm"></span>
        </div>

        <!-- PRODUCTS LIST -->
        <div id="productsList">
            <div class="loading">Loading products...</div>
        </div>

    </div>

    <script>
        let allProducts = [];
        let currentSearchTerm = '';

        // Load all products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllProducts();
            
            // Add enter key support
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchProducts();
                }
            });
        });

        function loadAllProducts() {
            // Fetch products from the server
            fetch('/products-data')
                .then(response => response.json())
                .then(data => {
                    allProducts = data.products;
                    displayProducts(allProducts);
                    updateStats(allProducts.length, '');
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('productsList').innerHTML = `
                        <div class="no-results">
                            <div class="icon">⚠️</div>
                            <p>Failed to load products. Please refresh the page.</p>
                        </div>
                    `;
                });
        }

        function searchProducts() {
            const searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
            currentSearchTerm = searchTerm;

            if (searchTerm === '') {
                displayProducts(allProducts);
                updateStats(allProducts.length, '');
                return;
            }

            // Filter products
            const filtered = allProducts.filter(product => {
                return product.name.toLowerCase().includes(searchTerm) ||
                       (product.description && product.description.toLowerCase().includes(searchTerm));
            });

            displayProducts(filtered);
            updateStats(filtered.length, searchTerm);
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            currentSearchTerm = '';
            displayProducts(allProducts);
            updateStats(allProducts.length, '');
        }

        function displayProducts(products) {
            const container = document.getElementById('productsList');

            if (products.length === 0) {
                container.innerHTML = `
                    <div class="no-results">
                        <div class="icon">🔍</div>
                        <h3>No products found</h3>
                        <p>Try a different search term or <a href="/create" style="color: #22c55e;">create a new product</a></p>
                    </div>
                `;
                return;
            }

            let html = '';
            products.forEach(product => {
                // Calculate average rating (if available)
                const avgRating = product.avg_rating || 0;
                const ratingRounded = Math.round(avgRating);
                
                html += `
                    <div class="card">
                        <div class="rating-badge">
                            <span class="star-icon">⭐</span>
                            <span>${avgRating > 0 ? avgRating.toFixed(1) : 'No ratings yet'}</span>
                        </div>
                        <h3>${escapeHtml(product.name)}</h3>
                        <p>${escapeHtml(product.description) || 'No description provided'}</p>
                        <div class="button-group">
                            <a href="/add-review/${product.id}">
                                <button class="review-btn">⭐ Add Review</button>
                            </a>
                            <a href="/view-review/${product.id}">
                                <button class="view-btn">📖 View Reviews</button>
                            </a>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function updateStats(count, searchTerm) {
            const totalSpan = document.getElementById('totalCount');
            const searchSpan = document.getElementById('searchTerm');

            if (searchTerm && searchTerm !== '') {
                totalSpan.innerHTML = `${count} result${count !== 1 ? 's' : ''} found`;
                searchSpan.innerHTML = `for "${escapeHtml(searchTerm)}"`;
                searchSpan.style.display = 'inline';
            } else {
                totalSpan.innerHTML = `${count} product${count !== 1 ? 's' : ''} total`;
                searchSpan.innerHTML = '';
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>

</body>

</html>