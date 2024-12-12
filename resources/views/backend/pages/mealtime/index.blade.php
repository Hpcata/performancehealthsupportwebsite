@extends('backend.layouts.app')

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
                <h3 class="fw-bold mb-0">Meal Times</h3>
                <a href="{{ route('admin.meal-times.create') }}" class="btn btn-primary py-2 px-5 btn-set-task w-sm-100">
                    <i class="icofont-plus-circle me-2 fs-6"></i> Add Meal Time
                </a>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mealTimes as $mealTime)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mealTime->title }}</td>
                        <td>{{ $mealTime->description }}</td>
                        <td>
                            @if ($mealTime->image)
                                <img src="{{ asset('private/public/storage/' . $mealTime->image) }}" alt="Meal Time Image" style="max-height: 50px;">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.meal-times.edit', $mealTime) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.meal-times.destroy', $mealTime) }}" method="POST" class="d-inline-block">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $mealTimes->links() }}
        </div>
    </div>
</div>
@endsection
