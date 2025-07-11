@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">{{ isset($tag) ? 'Edit Tag' : 'Create Tag' }}</h3>
              
                    <a type="button" href="{{ route('admin.tags.index') }}" class="btn btn-primary btn-set-task">Back</a>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($tag) ? route('admin.tags.update', $tag) : route('admin.tags.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($tag)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- Tag Name Field -->
                            <div class="col-md-12">
                                <label for="name" class="form-label">Tag Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $tag->name ?? '' }}" placeholder="Enter tag name">
                            </div>

                            <!-- Tag Icon Upload Field -->
                            <div class="col-md-12">
                                <label for="icon" class="form-label">Tag Icon</label>
                                <input type="file" name="icon" id="icon" class="form-control" accept="image/*">
                                <small class="text-muted">Upload an image file for the tag icon (JPG, PNG, SVG, etc.).</small>
                            </div>
                            @if(isset($tag) && $tag->icon)
                                <div class="mt-2">
                                    <img src="{{ webAssets('storage/' . $tag->icon) }}" alt="Tag Icon" style="max-height: 50px;">
                                </div>
                            @endif
                            <!-- Tag Icon Field -->
                            <!-- <div class="col-md-6">
                                <label for="tag_icon" class="form-label">Tag Icon</label>
                                <input type="text" name="tag_icon" id="tag_icon" class="form-control" value="{{ $page->tag_icon ?? '' }}" placeholder="e.g. fa-solid fa-star">
                                <small class="text-muted">Use Font Awesome class (e.g., <code>fa-solid fa-star</code>).</small>
                            </div> -->
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($tag) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
