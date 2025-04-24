@extends(backendView('layouts.app'))

@section('title', 'My Profile')

@section('content')
    {{-- @include(adminView('includes.alert')) --}}
    <style>
        .day-selection {
            display: flex;
            flex-direction: column;
            max-width: 200px;
            margin-left: 20px;
        }
        .day-selection label {
            margin-bottom: 5px;
        }
    </style>
    <div class="container-xxl">
        <div class="row align-items-center">
            <div class="border-0 mb-4">
                <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                    <h3 class="fw-bold mb-0">My Profile <a href="" style="font-size: 17px"></a></h3>
                    <div class="col-auto d-flex w-sm-100">
                        <a type="button" href="{{ route('dashboard') }}" class="btn btn-primary btn-set-task w-sm-100">Back</a>&nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-item-center">
            <div class="col-md-12">
                <div class="card mb-3">
                    <!-- <div class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
                        <h6 class="mb-0 fw-bold ">Basic Inputs</h6>
                    </div> -->
                    <div class="card-body">
						<form id="admin-user-profile-form" method="POST" action="{{ route('profile-post') }}" enctype="multipart/form-data">
							@csrf
                            <input type="hidden" name="id" value="{{ $adminUser->id ?? '' }}">
                            <div class="accordion" id="userInfoAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingUserInfo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUserInfo" aria-expanded="true" aria-controls="collapseUserInfo">
                                            User Information
                                        </button>
                                    </h2>
                                    <div id="collapseUserInfo" class="accordion-collapse collapse show" aria-labelledby="headingUserInfo" data-bs-parent="#userInfoAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3 align-items-center">
                                                <div class="col-md-6">
                                                    <label for="firt_name" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $adminUser->first_name ?? '') }}" id="first_name">
                                                    @include('backend.layouts.error', ['field' => 'first_name'])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="firt_name" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $adminUser->last_name ?? '') }}" id="last_name">
                                                    @include('backend.layouts.error', ['field' => 'last_name'])
                                                </div>
                                            </div>
                                            <div class="row mt-1 g-3 align-items-center">
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email" value="{{ old('email', $adminUser->email ?? '') }}" readonly disabled>
                                                    @include('backend.layouts.error', ['field' => 'email'])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="business_name" class="form-label">Business Name</label>
                                                    <input type="text" class="form-control" name="business_name" value="{{ old('business_name', $adminUser->business_name ?? '') }}" id="business_name">
                                                    @include('backend.layouts.error', ['field' => 'business_name'])
                                                </div>
                                            </div>
                                            <div class="row mt-1 g-3 align-items-center">
                                                <div class="col-md-6">
                                                    <label for="designation" class="form-label">Designation</label>
                                                    <input type="text" class="form-control" name="designation" value="{{ old('designation', $adminUser->designation ?? '') }}" id="last_name">
                                                    @include('backend.layouts.error', ['field' => 'designation'])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="profile_image" class="form-label">Profile Image</label>
                                                    <input type="file" class="form-control" name="profile_image" value="{{ old('profile_image', $adminUser->profile_image ?? '') }}"  id="profile_image" accept="image/*">
                                                    <img class="avatar lg rounded-circle img-thumbnail"
                                                    src="{{ $adminUser->profile_image ? asset($adminUser->profile_image) : 'https://booking.biohealthpassport.com.au/public/admin/dist/assets/images/profile_av.svg' }}"
                                                    alt="profile">
                                                    <!-- Conditionally show the Remove Image button -->
                                                    @if (!empty($adminUser->profile_image))
                                                    <a type="button" href="{{ route('remove-profile-image', $adminUser->id) }}" class="btn btn-primary btn-set-task w-sm-100">Remove Image</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mt-1 g-3 align-items-center">
                                                <div class="col-md-6">
                                                    <label for="qualification_text" class="form-label">Qualification Text</label>
                                                    <textarea class="form-control" name="qualification_text" id="qualification_text">{{ old('qualification_text', $adminUser->qualification_text ?? '') }}</textarea>
                                                    @include('backend.layouts.error', ['field' => 'qualification_text'])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingModuleConfig">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseModuleConfig" aria-expanded="false" aria-controls="collapseModuleConfig">
                                            Module Configurations
                                        </button>
                                    </h2>
                                    <div id="collapseModuleConfig" class="accordion-collapse collapse" aria-labelledby="headingModuleConfig" data-bs-parent="#userInfoAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3 align-items-center">
                                                <div class="col-md-6">
                                                    <label for="email_signature" class="form-label">Email Signature</label>
                                                    <textarea class="form-control" name="email_signature" id="email_signature">{{ old('email_signature', $adminUser->email_signature ?? '') }}</textarea>
                                                    @include('backend.layouts.error', ['field' => 'email_signature'])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="description_character_count" class="form-label">(i) Information hover count</label>
                                                    <input type="text" class="form-control" name="description_character_count" value="{{ old('description_character_count', $adminUser->description_character_count ?? '') }}" id="description_character_count">
                                                    @include('backend.layouts.error', ['field' => 'description_character_count'])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Configuration of Booking Times and Days Accordion Item -->
                              {{--  <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingBookingConfig">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBookingConfig" aria-expanded="false" aria-controls="collapseBookingConfig">
                                            Configuration of Booking Times and Days
                                        </button>
                                    </h2>
                                    <div id="collapseBookingConfig" class="accordion-collapse collapse" aria-labelledby="headingBookingConfig" data-bs-parent="#userInfoAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3 align-items-center">
                                                <div class="col-md-6">
                                                    <label for="days_selection" class="form-label">Days Selection</label>
                                                    <div class="day-selection">
                                                        @php
                                                            $days = [
                                                                'monday' => 'Monday',
                                                                'tuesday' => 'Tuesday',
                                                                'wednesday' => 'Wednesday',
                                                                'thursday' => 'Thursday',
                                                                'friday' => 'Friday',
                                                                'saturday' => 'Saturday',
                                                                'sunday' => 'Sunday',
                                                            ];
                                                            $selected_days = $bookingConfiguration->selected_days ? $bookingConfiguration->selected_days : [];
                                                        @endphp
                                                        @foreach ($days as $key => $value)
                                                            <label><input class="form-check-input" type="checkbox" name="day[]" value="{{ $key }}" {{ in_array($key, $selected_days) ? 'checked' : '' }}> {{ $value }}</label>
                                                        @endforeach
                                                        @include('backend.layouts.error', ['field' => 'day'])
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="time-range" class="form-label">Time Range</label>
                                                    <div class="time-range">
                                                        @php
                                                            $startTime = $bookingConfiguration->selected_timerange ? $bookingConfiguration->selected_timerange->startTime : '';
                                                            $endTime = $bookingConfiguration->selected_timerange ? $bookingConfiguration->selected_timerange->endTime : '';
                                                        @endphp
                                                        <div class="mb-4">
                                                            <label for="startTime">Start Time:</label>
                                                            <input type="time" id="startTime" name="startTime" class="form-control" value="{{ old('startTime', $startTime ?? '') }}">
                                                            @include('backend.layouts.error', ['field' => 'startTime'])
                                                        </div>
                                                        <div>
                                                            <label for="endTime">End Time:</label>
                                                            <input type="time" id="endTime" name="endTime" class="form-control" value="{{ old('endTime', $endTime ?? '') }}">
                                                            @include('backend.layouts.error', ['field' => 'endTime'])
                                                        </div>
                                                        <div id="output" class="output"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <!-- Configuration of Front Booking Page Accordion Item -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFrontConfig">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFrontConfig" aria-expanded="false" aria-controls="collapseFrontConfig">
                                            Configuration of Front Booking Page
                                        </button>
                                    </h2>
                                    <div id="collapseFrontConfig" class="accordion-collapse collapse" aria-labelledby="headingFrontConfig" data-bs-parent="#userInfoAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3 align-items-center">
                                                <div class="col-md-6">
                                                    <label for="front_logo" class="form-label">Front Page Logo</label>
                                                    <input type="file" class="form-control" name="front_logo" value="{{ old('front_logo', $adminUser->front_logo ?? '') }}" id="front_logo" accept="image/*">
                                                    <img class="avatar lg rounded-circle img-thumbnail"
                                                    src="{{ $adminUser->front_logo ? asset($adminUser->front_logo) : 'https://booking.biohealthpassport.com.au/public/admin/dist/assets/images/profile_av.svg' }}"
                                                    alt="profile">
                                                    @if (!empty($adminUser->front_logo))
                                                    <a type="button" href="{{ route('remove-front-logo', $adminUser->id) }}" class="btn btn-primary btn-set-task w-sm-100">Remove Image</a>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="about_us_image" class="form-label">About Us Logo</label>
                                                    <input type="file" class="form-control" name="about_us_image" value="{{ old('about_us_image', $adminUser->about_us_image ?? '') }}" id="about_us_image" accept="image/*">
                                                    <img class="avatar lg rounded-circle img-thumbnail"
                                                    src="{{ $adminUser->about_us_image ? asset($adminUser->about_us_image) : 'https://booking.biohealthpassport.com.au/public/admin/dist/assets/images/profile_av.svg' }}"
                                                    alt="profile">
                                                    @if (!empty($adminUser->about_us_image))
                                                    <a type="button" href="{{ route('remove-aboutus-image', $adminUser->id) }}" class="btn btn-primary btn-set-task w-sm-100">Remove Image</a>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="front_title" class="form-label">Front Title</label>
                                                    <input type="text" class="form-control" name="front_title" value="{{ old('front_title', $adminUser->front_title ?? '') }}" id="front_title">
                                                    @include('backend.layouts.error', ['field' => 'front_title'])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="front_description" class="form-label">Front Description</label>
                                                    <textarea class="form-control" name="front_description" id="front_description">{{ old('front_description', $adminUser->front_description ?? '') }}</textarea>
                                                    @include('backend.layouts.error', ['field' => 'front_description'])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="about_us_title" class="form-label">About Us Title</label>
                                                    <input type="text" class="form-control" name="about_us_title" value="{{ old('about_us_title', $adminUser->about_us_title ?? '') }}" id="last_name">
                                                    @include('backend.layouts.error', ['field' => 'about_us_title'])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="about_us_description" class="form-label">About Us Description</label>
                                                    <textarea class="form-control" name="about_us_description" id="about_us_description">{{ old('about_us_description', $adminUser->about_us_description ?? '') }}</textarea>
                                                    @include('backend.layouts.error', ['field' => 'about_us_description'])
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="copyright_text" class="form-label">(©) Copyright Text</label>
                                                    <input type="text" class="form-control" name="copyright_text" value="{{ old('copyright_text', $adminUser->copyright_text ?? '') }}" id="copyright_text">
                                                    @include('backend.layouts.error', ['field' => 'copyright_text'])
                                                </div>
                                            </div>
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

@push('custom_scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>
<script>
    

    function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }
    // Initialize CKEditor for 'email_template' field
    ClassicEditor
    .create(document.querySelector('#email_signature'), {
        extraPlugins: [MyCustomUploadAdapterPlugin, 'MediaEmbed'],
        toolbar: [
            'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 
            'blockQuote', 'imageUpload', 'mediaEmbed', '|', 'undo', 'redo'
        ],
        mediaEmbed: {
            previewsInData: true,
        },
    })
    .then(editor => {
        // Function to resize images
        function resizeImages(content) {
            const maxWidth = 100; // Reduced max width
            const maxHeight = 100; // Reduced max height
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;

            tempDiv.querySelectorAll('img').forEach(img => {
                // Force size reduction
                img.style.cssText += `max-width: ${maxWidth}px !important; max-height: ${maxHeight}px !important; width: auto !important; height: auto !important;`;
                
                // Remove width and height attributes
                img.removeAttribute('width');
                img.removeAttribute('height');
            });

            return tempDiv.innerHTML;
        }

        // Resize images when editor content is set
        const originalSetData = editor.data.set;
        editor.data.set = function(data) {
            data = resizeImages(data);
            originalSetData.call(editor.data, data);
        };

        // Resize images when content is pasted
        editor.plugins.get('ClipboardPipeline').on('inputTransformation', (evt, data) => {
            if (data.content) {
                const viewFragment = editor.data.processor.toView(resizeImages(editor.data.processor.toData(data.content)));
                data.content = editor.data.toModel(viewFragment);
            }
        });

        // Initial resize of images
        editor.setData(resizeImages(editor.getData()));

        // Add custom CSS to the editor
        editor.editing.view.change(writer => {
            const root = editor.editing.view.document.getRoot();
            writer.setStyle('font-size', '12px', root);
        });

        // Override the editor's content styles
        const style = document.createElement('style');
        style.innerHTML = `
            .ck-content * { max-width: 100% !important; }
            .ck-content img { max-width: 100px !important; max-height: 100px !important; width: auto !important; height: auto !important; }
        `;
        document.head.appendChild(style);
    })
    .catch(error => {
        console.error(error);
    });

    // Initialize CKEditor for 'email_template' field
    ClassicEditor
    .create(document.querySelector('#about_us_description'), {
        extraPlugins: [MyCustomUploadAdapterPlugin, 'MediaEmbed'],
        toolbar: [
            'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 
            'blockQuote', 'imageUpload', 'mediaEmbed', '|', 'undo', 'redo'
        ],
        mediaEmbed: {
            previewsInData: true,
        },
    })
    .then(editor => {})
    .catch(error => {
        console.error(error);
    });

    $(document).ready(function() {
        $('#endTime, #startTime').on('change', function() {
            var startTime = $('#endTime').val(); //22:46
            var endTime = $('#startTime').val(); //20:44

            // validate if start date is greate the end date then show error
            if (startTime < endTime) {
                // console.log('Start time is greater than end time');
                $('#endTime').addClass('is-invalid');
            } else {
                // console.log('Start time is less than end time');
                $('#endTime').removeClass('is-invalid');
            }
        });
    });
</script>
@endpush