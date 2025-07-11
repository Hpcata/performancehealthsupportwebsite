@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row align-items-center">
        <div class="col">
            <h3 class="fw-bold mb-0">Categories</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create Category</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <!-- <th>Time</th> -->
                        <th>Description</th>
                        <th>Order</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->title }}</td>
                        <!-- <td>{{ $category->time ? \Carbon\Carbon::createFromFormat('H:i:s', $category->time)->format('h:i A') : 'N/A' }} -->
                        </td>
                        <td>{{ $category->description }}</td>
                        <td>{{ $category->order }}</td>
                        <td>
                            @if ($category->image)
                                <img src="{{ webAssets('storage/' . $category->image) }}" alt="Meal Time Image" style="max-height: 50px;">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-success btn-sm">
                                <i class="icofont-edit text-success"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline-block">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="icofont-ui-delete text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </div>
</div>
@endsection
