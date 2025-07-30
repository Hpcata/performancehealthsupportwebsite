@extends('backend.layouts.app')

@section('title', 'Foods List')

@section('content')
    <style>
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
    <div class="container-xxl">
        <div class="row align-items-center">
            <div class="border-0 mb-4">
                <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                    <h3 class="fw-bold mb-0">Woolworths Product Search</h3>
                    <a href="{{ route('admin.items.create') }}" class="btn btn-primary py-2 px-2 btn-set-task">
                        Back
                    </a>
                </div>
            </div>
        </div> <!-- Row end -->

        <div class="row align-item-center">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <form action="{{ route('woolworths-product-search') }}" method="GET">
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="query" placeholder="Search for a product..." value="{{ $query ?? '' }}" required>
                                <button class="btn btn-primary mt-3" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if($results)
                            <h3>Search Results:</h3>
                            <table class="table table-hover align-middle mb-0" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Barcode</th>
                                        <th>Price</th>
                                        <th>Size</th>
                                        <th>Protein</th>
                                        <th>Carbohydrate</th>
                                        <th>Fat</th>
                                        <th>Energy</th>
                                        <th>Category</th>
                                        <th>Image</th>
                                        <th>Add Food</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $product)
                                        <tr>
                                            <td>{{ $product['name'] }}</td>
                                            <td>{{ $product['barcode'] }}</td>
                                            <td>${{ $product['price'] }}</td>
                                            <td>{{ $product['size'] }}</td>
                                            <td>{{ $product['nutrition']['protein'] ?? 'N/A' }}</td>
                                            <td>{{ $product['nutrition']['carbohydrate'] ?? 'N/A' }}</td>
                                            <td>{{ $product['nutrition']['fat'] ?? 'N/A' }}</td>
                                            <td>{{ $product['nutrition']['energy'] ?? 'N/A' }}</td>
                                            <td>{{ $product['category'] }}</td>
                                            <td>
                                                <img src="{{ $product['image'] }}" alt="Product Image" width="50" height="50">
                                            </td>
                                            <td>
                                                <button
                                                    class="btn btn-primary add-food-btn"
                                                    data-name="{{ $product['name'] }}"
                                                    data-image="{{ $product['image'] }}"
                                                    data-protein="{{ $product['nutrition']['protein'] ?? '0' }}"
                                                    data-carbs="{{ $product['nutrition']['carbohydrate'] ?? '0' }}"
                                                    data-serving-pack="{{ $product['nutrition']['serving_per_pack'] ?? '0' }}"
                                                    data-serving-size="{{ $product['nutrition']['serving_size'] ?? '0' }}"
                                                    data-fat="{{ $product['nutrition']['fat'] ?? '0' }}"
                                                    data-energy="{{ $product['nutrition']['energy'] ?? '0' }}"
                                                    data-saturated="{{ $product['nutrition']['saturated'] ?? '0' }}"
                                                    data-sugars="{{ $product['nutrition']['sugars'] ?? '0' }}"
                                                    data-dietary-fibre="{{ $product['nutrition']['dietary_fibre'] ?? '0' }}"
                                                    data-sodium="{{ $product['nutrition']['sodium'] ?? '0' }}"
                                                    data-category="{{ $product['category'] ?? '' }}">
                                                    Add Food
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No results found.</p>
                        @endif
                    </div>
                    <div class="pagination">
                        @if(isset($pagination['current_page']))
                            @if($pagination['current_page'] > 1)
                                <a href="{{ route('woolworths-product-search') }}?query={{ $query }}&page={{ $pagination['current_page'] - 1 }}">Previous</a>
                            @endif

                            <span>Page {{ $pagination['current_page'] }} of {{ $pagination['total_pages'] }}</span>

                            @if($pagination['current_page'] < $pagination['total_pages'])
                                <a href="{{ route('woolworths-product-search') }}?query={{ $query }}&page={{ $pagination['current_page'] + 1 }}">Next</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="loader">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                <div class="spinner"></div>
                <p>Loading, please wait...</p>
            </div>
        </div>
    </div>
@endsection

@push('custom_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const links = document.querySelectorAll('a');
            const loader = document.getElementById('loader');
            const addFoodButtons = document.querySelectorAll('.add-food-btn');

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
    <script>
        $(document).ready(function () {
            const loader = $('#loader');

            // Attach click event to all Add Food buttons
            $('.add-food-btn').on('click', function () {
                const name = $(this).data('name');
                const image = $(this).data('image');
                const protein = $(this).data('protein');
                const carbs = $(this).data('carbs');
                const fat = $(this).data('fat');
                const energy = $(this).data('energy');
                const saturated = $(this).data('saturated');
                const sugars = $(this).data('sugars');
                const dietary_fibre = $(this).data('dietary-fibre');
                const sodium = $(this).data('sodium');
                const category = $(this).data('category');
                const serving_per_pack = $(this).data('serving-pack');
                const serving_size = $(this).data('serving-size');
                // Show loader
                loader.show();
                console.log(serving_per_pack);
                // Make AJAX POST request
                $.ajax({
                    url: '{{ route("add-food") }}',
                    type: 'POST',
                    data: {
                        name: name,
                        image: image,
                        protein: protein,
                        carbs: carbs,
                        fat: fat,
                        energy: energy,
                        saturated: saturated,
                        sugars: sugars,
                        dietary_fibre: dietary_fibre,
                        sodium: sodium,
                        category: category,
                        serving_per_pack: serving_per_pack,
                        serving_size: serving_size,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Food added successfully!');
                            const editUrl = '{{ route("admin.items.edit", ":id") }}'.replace(':id', response.food.id);
                            window.location.href = editUrl;
                        } else {
                            alert('Failed to add food: ' + (response.message || 'Unknown error.'));
                        }
                    },
                    error: function (xhr) {
                        alert('Error: ' + xhr.responseText || 'An unknown error occurred.');
                    },
                    complete: function () {
                        // Hide loader
                        loader.hide();
                    }
                });
            });
        });
    </script>
@endpush