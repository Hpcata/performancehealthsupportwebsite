@extends('backend.layouts.app')

@section('title', 'Meal List')

@section('content')
<div class="container-xxl">
    <!-- Flash Messages -->
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
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Meal List</h3>
                <a href="{{ route('admin.meals.create') }}" class="btn btn-primary py-2 px-5 btn-set-task w-sm-100">
                    <i class="icofont-plus-circle me-2 fs-6"></i> Add Meal
                </a>
            </div>
        </div>
    </div> <!-- Row end -->

    <div class="row g-3 mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Sub Categories</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($meals as $item)
                            <tr>
                                <td><strong>{{ $item->id }}</strong></td>
                                <td>{{ $item->title }}</td>
                                <td>
                                    @if($item->image)
                                    <img src="{{ asset('private/public/storage/' . $item->image) }}" alt="" width="50">
                                    @else
                                    <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->subCategories->isNotEmpty())
                                        {{ $item->subCategories->pluck('title')->implode(', ') }}
                                    @else
                                        <span class="text-muted">No Subcategories</span>
                                    @endif
                                </td> 
                                <td>{{ Str::limit($item->description, 50, '...') }}</td>
                                <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                                        <a href="{{ route('admin.meals.edit', $item->id) }}" class="btn btn-outline-secondary">
                                            <i class="icofont-edit text-success"></i>
                                        </a>
                                        <form action="{{ route('admin.meals.destroy', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary">
                                                <i class="icofont-ui-delete text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection