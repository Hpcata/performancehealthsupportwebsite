@extends(backendView('layouts.app'))

@section('title', 'Edit Blog')

@section('content')
<style>
.ck-editor__editable {
    min-height: 300px; /* Adjust height here */
}
</style>
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Edit Blog</h3>
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
                    <!-- Form for Edit Blog -->
                    <form action="{{ route('backend.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- This tells Laravel we're updating an existing resource -->
                        <div class="row g-3 align-items-center">
                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Blog Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                            </div>

                            <div class="col-md-12">
                                <label for="content" class="form-label">Blog Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $blog->description) }}</textarea>
                            </div>

                            <!-- Content Field -->
                            <div class="col-md-12">
                                <label for="content" class="form-label">Blog Content</label>
                                <div id="editor" class="form-control" style="min-height: 200px;">{{ old('content', $blog->content) }}</div> <!-- CKEditor will use this div -->
                                <input type="hidden" name="content" value="{{ old('content', $blog->content) }}" id="hiddenContent" />
                                <!-- <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content', $blog->content) }}</textarea> -->
                            </div>

                            <!-- Category Field -->
                            <div class="col-md-6">
                                <label for="category" class="form-label">Tags</label>
                                <select class="form-control" id="tags" name="tags[]" multiple="multiple">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->name }}" 
                                            {{ isset($blog) && $blog->tags->pluck('name')->contains($tag->name) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Image Upload Field (only if image exists) -->
                            <div class="col-md-6">
                                <label for="image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @if($blog->image)
                                    <div class="mt-2">
                                        <img src="{{ asset($blog->image) }}" alt="Featured Image" style="max-width: 100px;">
                                    </div>
                                @endif
                            </div>

                            <!-- Status Field (Publish or Draft) -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_published" id="status_publish" value="1" {{ $blog->is_published == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_publish">
                                        Published
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_published" id="status_draft" value="0" {{ $blog->is_published == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_draft">
                                        Draft
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- plugin css file  -->
<link rel="stylesheet" href="{!! backendAssets('dist/assets/plugin/parsleyjs/css/parsley.css') !!}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

@endpush

@push('custom_styles')
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>
<!-- Plugin Js-->
<script src="{!! backendAssets('dist/assets/plugin/parsleyjs/js/parsley.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
		//Ch-editer
		ClassicEditor
			.create(document.querySelector('#editor'))
            .then(editor => {
            // When the form is submitted, transfer the content of CKEditor to the hidden field
                $('form').on('submit', function() {
                    $('#hiddenContent').val(editor.getData());
                });
            })
			.catch(error => {
				console.error(error);
			});
		//Deleterow
		$("#tbproduct").on('click', '.deleterow', function() {
			$(this).closest('tr').remove();
		});

	});
    $(document).ready(function() {
        $('#tags').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });
    });
</script>
@endpush

@push('custom_scripts')
<script>
    $(function() {
        // initialize after multiselect
        $('#basic-form').parsley();
    });
</script>
@endpush

@push('modals')
@endpush
