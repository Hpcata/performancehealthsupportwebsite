@extends('backend.layouts.app')

@section('content')
<style>
    .dropzone .dz-preview {
        width: 120px;
        height: 120px;
        margin: 5px;
        position: relative;
        display: inline-block;
    }

    .dropzone .dz-preview .dz-image {
        width: 100%;
        height: 100%;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dropzone .dz-preview .dz-image img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    /* Styling for remove link */
    .dropzone .dz-preview .dz-remove {
        /* position: absolute; */
        bottom: 5px;
        right: 5px;
        background-color: rgba(255, 255, 255, 0.7);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
        color: #dc3545;
        text-decoration: none;
        cursor: pointer;
        z-index: 10;
    }
    .dropzone .dz-preview .dz-filename {
        /* display: none !important; */
    }
</style>

<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">Edit Section</h3>
                <a type="button" href="{{ route('sections.index', ['page' => $page->id]) }}" class="btn btn-primary btn-set-task">Back</a>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form id="sectionForm" action="{{ route('sections.update', $section) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title" value="{{ $section->title }}" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label for="page_id" class="form-label">Select Page</label>
                                <select name="page_id" id="page_id" class="form-control" required>
                                    @foreach ($pages as $p)
                                        <option value="{{ $p->id }}" {{ $section->page_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="section_type" class="form-label">Section Type</label>
                                <select name="section_type" id="section_type" class="form-control" required>
                                    <option value="">Select Section Type</option>
                                    @foreach (\App\Models\Section::getSectionTypes() as $value => $label)
                                        <option value="{{ $value }}" {{ $section->section_type == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Each section type can only be used once across all sections.</small>
                            </div>
                            <div class="form-group">
                                <label for="content" class="form-label">Content</label>
                                <textarea name="content" id="content" class="form-control" rows="5">{{ $section->content }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="enabled" class="form-label">Enable Section</label>
                                <select name="enabled" id="enabled" class="form-control  select2" required>
                                    <option value="1" {{ $section->enabled ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !$section->enabled ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">Upload Banner Images</label>
                                <div id="banner-image-dropzone" class="dropzone"></div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">Upload Images</label>
                                <div id="image-dropzone" class="dropzone"></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Update Section</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">

<script>
    const existingBannerImages = @json($section->banner_image ?? []);
    const existingImages = @json($section->image ?? []);
    const removedBannerImages = [];
    const removedImages = [];

    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('.ckeditor')) {
            CKEDITOR.replace('content', {
                allowedContent: true,
                removePlugins: 'elementspath',
                resize_enabled: false
            });
        }

        // Load used section types and disable them in dropdown (excluding current section)
        fetch('{{ route("sections.used-types") }}')
            .then(response => response.json())
            .then(data => {
                const usedTypes = data.used_types;
                const sectionTypeSelect = document.getElementById('section_type');
                const currentSectionType = '{{ $section->section_type }}';
                
                Array.from(sectionTypeSelect.options).forEach(option => {
                    if (usedTypes.includes(option.value) && option.value !== currentSectionType) {
                        option.disabled = true;
                        option.text += ' (Already Used)';
                    }
                });
            })
            .catch(error => console.error('Error loading used section types:', error));
    });

    Dropzone.autoDiscover = false;

    const bannerDropzone = new Dropzone("#banner-image-dropzone", {
        url: "/", // Dummy
        autoProcessQueue: false,
        addRemoveLinks: true,
        uploadMultiple: true,
        acceptedFiles: 'image/*',
        maxFiles: 10,
        init: function () {
            const dz = this;
            existingBannerImages.forEach((imagePath, i) => {
                const filename = imagePath.split('/').pop();
                const mockFile = { name: filename, size: 12345, dataPath: imagePath };
                dz.emit("addedfile", mockFile);
                dz.emit("thumbnail", mockFile, `{{ asset('storage') }}/${imagePath}`);
                dz.emit("complete", mockFile);
                dz.files.push(mockFile);
            });

            dz.on("removedfile", function(file) {
                if (file.dataPath) {
                    removedBannerImages.push(file.dataPath);
                }
            });
        }
    });

    const imageDropzone = new Dropzone("#image-dropzone", {
        url: "/", // Dummy
        autoProcessQueue: false,
        addRemoveLinks: true,
        uploadMultiple: true,
        acceptedFiles: 'image/*',
        maxFiles: 10,
        init: function () {
            const dz = this;
            existingImages.forEach((imagePath, i) => {
                const filename = imagePath.split('/').pop();
                const mockFile = { name: filename, size: 12345, dataPath: imagePath };
                dz.emit("addedfile", mockFile);
                dz.emit("thumbnail", mockFile, `{{ asset('storage') }}/${imagePath}`);
                dz.emit("complete", mockFile);
                dz.files.push(mockFile);
            });

            dz.on("removedfile", function(file) {
                if (file.dataPath) {
                    removedImages.push(file.dataPath);
                }
            });
        }
    });

    document.getElementById('sectionForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Update CKEditor content
        if (CKEDITOR.instances.content) {
            CKEDITOR.instances.content.updateElement();
        }

        const form = this;
        const formData = new FormData(form);

        // Append removed image paths
        removedImages.forEach((img) => formData.append('removed_images[]', img));
        removedBannerImages.forEach((img) => formData.append('removed_banner_images[]', img));

        // Append new images
        imageDropzone.getAcceptedFiles().forEach((file) => {
            if (!file.dataPath) {
                formData.append('images[]', file);
            }
        });

        bannerDropzone.getAcceptedFiles().forEach((file) => {
            if (!file.dataPath) {
                formData.append('banner_images[]', file);
            }
        });

        // Submit via fetch
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pageId = data.section.page_id;
                const message = encodeURIComponent('Section updated successfully!');
                let redirectUrl = "{{ route('sections.index', ['page' => '__ID__']) }}".replace('__ID__', pageId);
                window.location.href = `${redirectUrl}?message=${message}`;
            } else {
                alert(data.message || 'Something went wrong.');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Update failed.');
        });
    });
</script>

@endpush
