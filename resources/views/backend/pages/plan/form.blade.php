@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($plan) ? 'Edit Plan' : 'Create Plan' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a href="{{ route('admin.plans.index') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($plan) ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($plan)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- User ID -->
                            {{--  <div class="col-md-12">
                                <label for="user_id" class="form-label">User</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $plan->user->name ?? '' }}" required>

                               <select id="user_id" name="user_id" class="form-select" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ isset($plan) && $plan->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select> 
                            </div>--}}

                            <!-- Name -->
                            <div class="col-md-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $plan->name ?? '' }}" required>
                            </div>

                            <!-- Subtitle -->
                            <div class="col-md-12">
                                <label for="subtitle" class="form-label">Subtitle</label>
                                <input type="text" id="subtitle" name="subtitle" class="form-control" value="{{ $plan->subtitle ?? '' }}">
                            </div>

                            <!-- Price -->
                            <div class="col-md-12">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" id="price" name="price" class="form-control" value="{{ $plan->price ?? '' }}" required>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control">{{ $plan->description ?? '' }}</textarea>
                            </div>

                            <!-- Image -->
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" id="image" name="image" class="form-control">
                                @if (isset($plan) && $plan->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $plan->image) }}" alt="Plan Image" width="100">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">{{ isset($plan) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
