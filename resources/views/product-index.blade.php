@extends('backend.layouts.app')

@section('content')
<div class="container">
    <h1>Search Results</h1>

    @if (!empty($products))
        <ul class="list-group">
            @foreach ($products as $product)
                <li class="list-group-item">
                    <strong>{{ $product['name'] }}</strong><br>
                    Brand: {{ $product['brand'] ?? 'N/A' }}<br>
                    Barcode: {{ $product['barcode'] ?? 'N/A' }}<br>
                    <img src="{{ $product['image'] ?? 'https://via.placeholder.com/150' }}" alt="Product Image" width="150">
                </li>
            @endforeach
        </ul>
    @else
        <p>No products found for your search.</p>
    @endif
</div>
@endsection
