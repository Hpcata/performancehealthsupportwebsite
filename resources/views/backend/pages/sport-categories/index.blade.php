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
            <h3 class="fw-bold mb-0">Sport Categories</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.sports-categories.create') }}" class="btn btn-primary">Create Sport Category</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <a href="{{ route('admin.sports-categories.edit', $category->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="icofont-edit"></i>
                                </a>
                                <form action="{{ route('admin.sports-categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this game?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="icofont-ui-delete"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center align-items-center">No sport categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection