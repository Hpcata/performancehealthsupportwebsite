@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Create Section</h3>
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
                    <form action="{{ route('sections.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>

                            <div class="col-md-12">

                                <label for="page_id" class="form-label">Select Page</label>
                                <select name="page_id" id="page_id" class="form-control" required>
                                    @foreach ($pages as $p)
                                        <option value="{{ $p->id }}" {{ isset($page) && $page->id == $p->id ? 'selected' : '' }}>
                                            {{ $p->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="type" class="form-label">Section Type</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="header">Header</option>
                                    <option value="section-1">Section 1</option>
                                    <option value="section-2">Section 2</option>
                                    <option value="section-3">Section 3</option>
                                    <option value="section-4">Section 4</option>
                                    <option value="section-5">Section 5</option>
                                    <option value="footer">Footer</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="content" class="form-label">Content</label>
                                <textarea name="content" id="content" class="form-control ckeditor" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="enabled" class="form-label">Enable Section</label>
                                <select name="enabled" id="enabled" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="order" class="form-label">Order</label>
                                <input type="number" name="order" id="order" class="form-control" required>
                            </div>
                            
                            <div class="col-md-12">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Create Section</button>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->
</div>
@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.querySelector('.ckeditor')) {
                CKEDITOR.replace('content', {
                    allowedContent: true, // Allow all valid HTML
                    removePlugins: 'elementspath',
                    resize_enabled: false
                });
            }
        });
    </script>
@endpush
