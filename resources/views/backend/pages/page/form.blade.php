@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">{{ isset($page) ? 'Edit Page' : 'Create Page' }}</h3>
                <div class="col-auto d-flex w-sm-100">
                    <a type="button" href="{{ route('dashboard') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>&nbsp;
                </div>
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
                                <input type="text" name="title" class="form-control" value="{{ $page->title ?? '' }}" required>
                            </div>

                            <div class="col-md-12">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ $page->slug ?? '' }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{ isset($page) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
