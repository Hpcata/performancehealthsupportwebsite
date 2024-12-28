@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="fw-bold mb-0">Meal Times</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.meal-times.create') }}" class="btn btn-primary">Create Meal Time</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Time</th>
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
                        <td>    {{ $mealTime->time ? \Carbon\Carbon::createFromFormat('H:i:s', $mealTime->time)->format('h:i A') : 'N/A' }}
                        </td>
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
