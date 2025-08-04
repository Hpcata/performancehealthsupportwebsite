@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">{{ isset($page) ? 'Edit Page' : 'Create Page' }}</h3>
                <a href="{{ route('pages.index') }}" class="btn btn-primary btn-set-task">Back</a>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ isset($page) ? route('pages.update', $page) : route('pages.store') }}" method="POST">
                        @csrf
                        @if (isset($page)) @method('PUT') @endif

                        <div class="row g-3 align-items-center">
                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    value="{{ old('title', $page->title ?? '') }}" required>
                            </div>

                            <!-- Slug Field -->
                            <div class="col-md-12">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                    value="{{ old('slug', $page->slug ?? '') }}" readonly>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($page) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const titleInput = document.getElementById("title");
        const slugInput = document.getElementById("slug");

        const isEdit = "{{ isset($page) ? '1' : '0' }}" === "1";
        console.log(isEdit);
        if (!isEdit) {
            titleInput.addEventListener("input", function () {
                const slug = titleInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[\s\W-]+/g, '_') // replace spaces and special characters with underscore
                    .replace(/^_+|_+$/g, '');  // trim underscores
                slugInput.value = slug;
            });
        }
    });
</script>
@endsection
