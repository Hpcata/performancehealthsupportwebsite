@extends(backendView('layouts.app'))

@section('title', 'Create Blog')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Create Blog</h3>
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
                    <form action="{{ route('backend.blogs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <!-- Title Field -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Blog Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="col-md-12">
                                <label for="content" class="form-label">Blog Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                            </div>

                            <!-- Content Field -->
                            <div class="col-md-12">
                                <label for="editor" class="form-label">Blog Content</label>
                                 <!-- <div id="editor"> -->
									<!-- <h4>Please add Blog Description here</h4> -->
								<!-- </div> -->
                                <div id="editor" class="form-control" style="min-height: 200px;"></div> <!-- CKEditor will use this div -->
                                <input type="hidden" name="content" id="hiddenContent" />
                            </div>

                            <!-- Author Field -->
                           {{-- <div class="col-md-6">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author" required>
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Author Email</label>
                                <input type="email" class="form-control" id="emailaddress" name="emailaddress" required>
                            </div>

                            <!-- Publish Date Field -->
                            <div class="col-md-6">
                                <label for="publish_date" class="form-label">Publish Date</label>
                                <input type="date" class="form-control" id="publish_date" name="publish_date" required>
                            </div>
                            --}}
                            <!-- Category Field -->
                            <div class="col-md-6">
                                <label for="category" class="form-label">Tags</label>
                                <select class="form-control" id="tags" name="tags[]" multiple="multiple">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->name }}">
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tags Field -->
                        {{--    <div class="col-md-6">
                                <label for="tags" class="form-label">Tags (Comma separated)</label>
                                <input type="text" class="form-control" id="tags" name="tags">
                            </div>
                        --}}
                            <!-- Image Upload Field -->
                            <div class="col-md-6">
                                <label for="image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>

                            <!-- Status Field (Publish or Draft) -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_published" id="status_publish" value="1" checked>
                                    <label class="form-check-label" for="status_publish">
                                        Published
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_published" id="status_draft" value="0">
                                    <label class="form-check-label" for="status_draft">
                                        Draft
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
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
        $('#editor').css('display', 'block');

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