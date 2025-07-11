@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">{{ isset($flag) ? 'Edit Preferences' : 'Create Preferences' }}</h3>
               
                    <a type="button" href="{{ route('admin.flags.index') }}" class="btn btn-primary btn-set-task">Back</a>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($flag) ? route('admin.flags.update', $flag) : route('admin.flags.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($flag)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- Tag Name Field -->
                            <div class="col-md-12">
                                <label for="name" class="form-label">Preferences Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $flag->name ?? '' }}" placeholder="Enter flag name">
                            </div>

                            <!-- <div class="col-md-12">
                                <label for="icon" class="form-label">Tag Icon</label>
                                <input type="file" name="icon" id="icon" class="form-control" accept="image/*">
                                <small class="text-muted">Upload an image file for the flag icon (JPG, PNG, SVG, etc.).</small>
                            </div>
                            @if(isset($flag) && $flag->icon)
                                <div class="mt-2">
                                    <img src="{{ asset('private/public/storage/' . $flag->icon) }}" alt="Tag Icon" style="max-height: 50px;">
                                </div>
                            @endif  -->
                           
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($flag) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
