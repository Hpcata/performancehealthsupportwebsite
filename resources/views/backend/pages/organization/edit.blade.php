@extends(backendView('layouts.app'))

@section('title', 'Organizations')

@section('content')
@include(backendView('includes.alert'))
<div class="container-xxl">
	<div class="row align-items-center">
		<div class="border-0 mb-4">
			<div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
				<h3 class="fw-bold mb-0">Associations</h3>
			</div>
		</div>
	</div>

    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Upload Logos</label>
                            <small class="d-block text-muted mb-2">Portrait or square images (max 2mb; max 2000px)</small>

                            <div class="row pb-3">
                                <div class="col-md-10"></div>
                                <div class="col-md-2">
                                    <div class="mt-3">
                                        <select class="form-select" id="imagePosition">
                                            <option value="top" selected>Top Row</option>
                                            <option value="bottom">Bottom Row</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="imageUpload">
                                <div id="imageUpload" class="dropzone col-12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button class="btn btn-success text-white w-sm-100" id="image-upload">Upload</button>&nbsp
                            <button class="btn btn-danger text-white w-sm-100" id="image-upload-cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
					<h6 class="mb-0 fw-bold ">Images</h6>
				</div>
				<div class="card-body">
                    <div class="row g-3 align-items-center" id="image-list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.8/draggable.bundle.legacy.min.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">
@endpush

@push('scripts')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.8/draggable.bundle.legacy.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
    var userId = "{{ Auth::user()->id }}";
    Dropzone.autoDiscover = false;
    var imageDropZone = new Dropzone('div#imageUpload', {
        addRemoveLinks: true,
        autoProcessQueue: false,
        uploadMultiple: true,
        acceptedFiles: '.jpg, .jpeg, .png, .webp',
        parallelUploads: 100,
        maxFiles: 10,
        paramName: 'files',
        clickable: true,
        url: "{{ route('organizations.media-upload') }}",
        headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
        data: {'id': userId, 'type' : 'image', 'position': $('#imagePosition').val()},
        init: function () {
            var imageDropZone = this;
            this.on('sending', function (file, xhr, formData) {
                formData.append('id', userId);
                formData.append('type', 'image');
                formData.append('position', $('#imagePosition').val());
            });
        },
        error: function (file, response){
            if ($.type(response) === "string")
                var message = response; //dropzone sends it's own error messages in string
            else
                var message = response.message;
            file.previewElement.classList.add("dz-error");
            _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                node = _ref[_i];
                _results.push(node.textContent = message);
            }
            return _results;
        },
        successmultiple: function (file, response) {
            this.removeAllFiles(true);
            getImageList(userId);
        },
        completemultiple: function (file, response) {
            // console.log(file, response, "completemultiple");
        },
        reset: function () {
            this.removeAllFiles(true);
        }
    });

    $(document).on('click', '#image-upload', function() {
        imageDropZone.processQueue();
    });

    $(document).on('click', '#image-upload-cancel', function() {
        imageDropZone.removeAllFiles();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('image-list');
        const draggable = new Draggable.Sortable(container, {
            draggable: '.img-sec',
            handle: '.img',
        });

        draggable.on('sortable:stop', () => {
            setTimeout(() => {
                var mediaIds = [];
                $('.img').each(function() {
                    mediaIds.push($(this).data('media-id'));
                });
                sorting(mediaIds);
            }, 1000);
        });
    });

    function sorting(mediaIds) {
        $.ajax({
            url: "{{ route('organizations.sort-order') }}",
            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
            method: 'POST',
            data: {
                mediaIds: mediaIds,
                id: userId
            },
            success: function(response) {
                getImageList(userId);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });
            }
        });
    }

    function getImageList(userId) {
        $.ajax({
            url: "{{ route('organizations.image-list') }}",
            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
            method: 'POST',
            data: {
                id: userId,
                position: $('#imagePosition').val(),
            },
            success: function(response) {
                var data = response.data;
                var html = '';
                for (let i = 0; i < data.length; i++) {
                    html += '<div class="col-md-2 img-sec"><figure><img class="img-fluid" style="border-radius: 10px;border: 1px solid black;padding: 25px;" src="'+data[i]['path']+'" alt="Image" data-media-id="'+data[i]['media_id']+'" height="200" width="200"></figure>' + '<div class="delete-image-btn"><a href="javascript:void(0);" class="delete-btn" data-media-id="'+data[i]['media_id']+'"><i class="icofont-ui-delete text-danger"></i></a> </div>' + '</div>';
                }

                $('#image-list').html(html);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });
            }
        });
    }

    $(document).ready(function(){
        getImageList(userId);
    });

    function deleteMedia(mediaId) {
        $.ajax({
            url: "{{ route('organizations.image-delete') }}",
            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
            method: 'POST',
            data: {
                media_id: mediaId,
            },
            success: function(response) {
                Swal.fire("Image deleted successfully!", "", "success");
                getImageList(userId);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });
            }
        });
    }

    $(document).on('click', '.delete-btn', function() {
        var mediaId = $(this).data('media-id');

        Swal.fire({
			title: "Are you sure?",
			text: "You want to delete image?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Submit"
			}).then((result) => {
			if (result.isConfirmed) {
				deleteMedia(mediaId);
			}
		});
    });

    $(document).on('change', '#imagePosition', function() {
        getImageList(userId);
    });
</script>
@endpush
