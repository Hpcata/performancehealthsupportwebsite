<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        a { color: #007BFF; text-decoration: none; }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #007bff;
        }

        .pagination a:hover {
            background-color: #f0f0f0;
        }

        .pagination span {
            padding: 8px 16px;
            margin: 0 5px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div id="loader">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div class="spinner"></div>
            <p>Loading, please wait...</p>
        </div>
    </div>

    <h1>Product Search</h1>

    <form action="{{ route('search-product') }}" method="GET">
        <input type="text" name="query" placeholder="Search for a product..." value="{{ $query ?? '' }}" required>
        <button type="submit">Search</button>
    </form>

        <h2>Search Results:</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Barcode</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Link</th>
                    <th>Order</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $product)
                    <tr>
                        <td>{{ $product['product_name'] }}</td>
                        <td>{{ $product['barcode'] }}</td>
                        <td>{{ $product['product_brand'] }}</td>
                        <td>${{ number_format($product['current_price'], 2) }}</td>
                        <td>{{ $product['product_size'] }}</td>
                        <td><a href="{{ $product['url'] }}" target="_blank">View Product</a></td>
                        <td><form method="POST" action="{{ route('add-to-cart') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product['barcode'] }}">
                            <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form></td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    <!-- Pagination controls -->
    <!-- <div class="pagination">
        @if(isset($pagination['current_page']))
        @if($pagination['current_page'] > 1)
            <a href="{{ route('search-product') }}?query={{ $query }}&page={{ $pagination['current_page'] - 1 }}">Previous</a>
        @endif

        <span>Page {{ $pagination['current_page'] }} of {{ $pagination['total_pages'] }}</span>

        @if($pagination['current_page'] < $pagination['total_pages'])
            <a href="{{ route('search-product') }}?query={{ $query }}&page={{ $pagination['current_page'] + 1 }}">Next</a>
        @endif
        @endif
    </div> -->
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const links = document.querySelectorAll('a');
            const loader = document.getElementById('loader');

            form.addEventListener('submit', function () {
                loader.style.display = 'block'; // Show loader
            });

            links.forEach(link => {
                link.addEventListener('click', function () {
                    loader.style.display = 'block'; // Show loader
                });
            });

            window.addEventListener('load', function () {
                loader.style.display = 'none'; // Hide loader when the page finishes loading
            });
        });
    </script>
</body>
</html>
