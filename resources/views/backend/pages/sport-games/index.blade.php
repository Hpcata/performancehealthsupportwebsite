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
            <h3 class="fw-bold mb-0">Sport Games</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.sport-games.create') }}" class="btn btn-primary">Create Sport Game</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Game Name</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($games as $index => $game)
                        <tr>
                            <td>{{ $game->id }}</td>
                            <td>{{ $game->name }}</td>
                            <td>
                                @if($game->categories->isNotEmpty())
                                    {{ $game->categories->pluck('name')->join(', ') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if(isset($game))
                                    @php
                                        $categoryId = old('sport_category_id', $game->categories->first()->id ?? null);
                                        $pivot = $categoryId ? $game->categories->find($categoryId)?->pivot : null;
                                    @endphp
                                    @if($pivot && $pivot->image_path)
                                        <img src="{{ webAssets('storage/' . $pivot->image_path) }}" width="80" height="80">
                                    @else
                                        <span>No image</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.sport-games.edit', $game->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="icofont-edit"></i></a>

                                <form action="{{ route('admin.sport-games.destroy', $game->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this game?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="icofont-ui-delete"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center align-items-center">No sport games found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection