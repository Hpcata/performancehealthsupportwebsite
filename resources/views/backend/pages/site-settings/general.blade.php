@extends(backendView('layouts.app'))

@section('title', 'Home Page Settings')

@section('page-title')
    {{ __('Home Page Settings') }}
@endsection

@section('content')
	@include(backendView('includes.alert'))
	<div class="container-xxl">
        <div class="row align-items-center">
            <div class="border-0 mb-4">
                <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                    <h3 class="fw-bold mb-0">{{ __('General Setting') }}</h3>
                </div>
            </div>
        </div>

        <div class="row align-item-center">
            <div class="col-md-12">
                <div class="card mb-3 p-3">
                    <div class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-1 ">
                        <h6 class="mb-0 fw-bold ">Header Section</h6>
                    </div>
                    <div class="card-body">
                        <form id="general-header-form" method="POST" action="{{ route('save-site-settings') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name='page' value="general">
                            <input type="hidden" name='section' value="header">
                            <div class="row pb-5">
                                @php
                                    $imageUrl = isset($settings['header']['site']['logo']) ? $settings['header']['site']['logo'] : '';
                                @endphp
                                <div class="col-md-6">
                                    <label for="site-logo" class="form-label">Site Logo</label>
                                    <input type="file" class="form-control" name="general[site_logo]" id="site-logo" accept=".jpeg, .jpg, .png, .gif, .svg">
                                    <img alt="site-logo" src="{{$imageUrl}}" class="form-control {{ $imageUrl ? '' : 'd-none' }}" id="site-logo-preview" width="200" height="200">
                                    @include('backend.layouts.error', ['field' => 'site_logo'])
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 pl-5 pb-3">
                                    <label for="header_topnotification" class="form-label">Top Header Notification</label>
                                    <textarea class="form-control header_topnotification" name="general[topnotification]" id="header_topnotification">{{isset($settings['header']['topnotification']) && $settings['header']['topnotification'] ? $settings['header']['topnotification'] : ''}}</textarea>
                                </div>

                                <div class="col-md-12">
                                    <label for="site-logo" class="form-label">Header Menu</label>

                                    <div id="header-menu-div">
                                        <div class="row">
                                            <div class="col-md-11"></div>
                                            <div class="col-md-1 text-end">
                                                <a class="btn btn-outline-primary add-header-menu">
                                                    <i class="icofont-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <?php //dd($pageList); ?>
                                        @if(isset($settings['header']['headermenu']) && $settings['header']['headermenu'])
                                            @php
                                                $count = 0;
                                            @endphp
                                            @foreach($settings['header']['headermenu'] as $key => $menu)
                                                <div class="row g-3 align-items-center header-menu-row-section-div pb-4">
                                                    <div class="col-md-1 text-end drag-btn">
                                                        <h4><i class="bi bi-grip-vertical"></i></h4>
                                                    </div>
                                                    <div class="col-md-5 pl-5">
                                                        <label for="menutitle_{{$count}}" class="form-label">Menu Title</label>
                                                        <input type="text" class="form-control menutitle" name="general[headermenu][{{ $count }}][title]" id="menutitle_{{$count}}" value="{{ $menu['title'] }}">
                                                    </div>
                                                    <div class="col-md-5 pr-5 pl-5">
                                                        <label for="menutitle_link_{{$count}}" class="form-label">Menu Link</label>
                                                        <select class="form-select menutitle-link" name="general[headermenu][{{ $count }}][link]" id="menutitle_link_{{$count}}" >
                                                       
                                                            @foreach($pageList as $pageKey => $pageData)
                                                                <option value="{{ $pageData['url'] }}" {{ isset($menu['link']) && $pageData['url'] == $menu['link'] ? 'selected' : '' }}>{{ $pageData['title'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 text-end">
                                                        <div class="non-draggable-item col-md-1 <?php echo count($settings['header']['headermenu']) == 1 ? 'd-none' : '' ?>" draggable="false">
                                                            <a class="btn btn-outline-primary remove-header-menu-row non-draggable-item" draggable="false">
                                                                <i class="icofont-minus"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $count++;
                                                @endphp
                                            @endforeach
                                        @else
                                        <div class="row g-3 align-items-center header-menu-row-section-div pb-4">
                                            <div class="col-md-1 text-end drag-btn">
                                                <h4><i class="bi bi-grip-vertical"></i></h4>
                                            </div>
                                            <div class="col-md-5 pl-5">
                                                <label for="menutitle" class="form-label">Menu Title</label>
                                                <input type="text" class="form-control menutitle" name="general[headermenu][0][title]" id="menutitle">
                                            </div>
                                            <div class="col-md-5 pr-5 pl-5">
                                                <label for="menutitle_link" class="form-label">Menu Link</label>
                                                <select class="form-select menutitle-link" name="general[headermenu][0][link]" id="menutitle_link" >
                                                    @foreach($pageList as $pageKey => $pageData)
                                                        <option value="{{ $pageData['url'] }}">{{ $pageData['title'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <div class="non-draggable-item col-md-1 d-none" draggable="false">
                                                    <a class="btn btn-outline-primary remove-header-menu-row non-draggable-item" draggable="false">
                                                        <i class="icofont-minus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <button type="submit" class="submit-btn btn btn-primary mt-4">Save</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-3 p-3">
                    <div class="card-footer py-3 d-flex justify-content-between bg-transparent border-bottom-1 ">
                        <h6 class="mb-0 fw-bold ">Footer Section</h6>
                    </div>
                    <div class="card-body">
                        <form id="general-footer-form" method="POST" action="{{ route('save-site-settings') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name='page' value="general">
                            <input type="hidden" name='section' value="footer">
                            <div class="row pb-5">
                                @php
                                    $imageUrl = isset($settings['footer']['site']['logo']) ? $settings['footer']['site']['logo'] : '';
                                @endphp
                                <div class="col-md-6">
                                    <label for="footer-site-logo" class="form-label">Site Logo</label>
                                    <input type="file" class="form-control" name="general[footer_site_logo]" id="footer-site-logo" accept=".jpeg, .jpg, .png, .gif, .svg">
                                    <img alt="footer-site-logo" src="{{$imageUrl}}" class="form-control {{ $imageUrl ? '' : 'd-none' }}" id="footer-site-logo-preview" width="200" height="200">
                                    @include('backend.layouts.error', ['field' => 'footer_site_logo'])
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <label for="footer-site-logo" class="form-label">Footer Menu</label>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Menu Title</label>
                                            <input type="text" class="form-control footer_menu1title" name="general[footermenu1title]" id="footer_menu1title" value="{{ isset($settings['footer']['footermenu1title']) ? $settings['footer']['footermenu1title'] : '' }}">
                                        </div>
                                    </div>

                                    <div id="footer-menu1-div">
                                        <div class="row">
                                            <div class="col-md-11"></div>
                                            <div class="col-md-1 text-end">
                                                <a class="btn btn-outline-primary add-footer-menu1">
                                                    <i class="icofont-plus"></i>
                                                </a>
                                            </div>
                                        </div>

                                        @if(isset($settings['footer']['footermenu1']) && $settings['footer']['footermenu1'])
                                            @php
                                                $count = 0;
                                            @endphp
                                            @foreach($settings['footer']['footermenu1'] as $key => $menu1)
                                                <div class="row g-3 align-items-center footer-menu1-row-section-div pb-4">
                                                    <div class="col-md-1 text-end drag-btn">
                                                        <h4><i class="bi bi-grip-vertical"></i></h4>
                                                    </div>
                                                    <div class="col-md-5 pl-5">
                                                        <label for="footer_menu1title_{{$count}}" class="form-label">Menu Title</label>
                                                        <input type="text" class="form-control footer_menu1title" name="general[footermenu1][{{ $count }}][title]" id="footer_menu1title_{{$count}}" value="{{ $menu1['title'] }}">
                                                    </div>
                                                    <div class="col-md-5 pr-5 pl-5">
                                                        <label for="footer_menu1title_link_{{$count}}" class="form-label">Menu Link</label>
                                                        <select class="form-select footer_menu1title-link" name="general[footermenu1][{{ $count }}][link]" id="footer_menu1title_link_{{$count}}" >
                                                           
                                                            @foreach($pageList as $pageKey => $pageData)
                                                                <option value="{{ $pageData['url'] }}" {{ isset($menu1['link']) && $pageData['url'] == $menu1['link'] ? 'selected' : '' }}>{{ $pageData['title'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 text-end">
                                                        <div class="non-draggable-item col-md-1 <?php echo count($settings['footer']['footermenu1']) == 1 ? 'd-none' : '' ?>" draggable="false">
                                                            <a class="btn btn-outline-primary remove-footer-menu1-row non-draggable-item" draggable="false">
                                                                <i class="icofont-minus"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $count++;
                                                @endphp
                                            @endforeach
                                        @else
                                        <div class="row g-3 align-items-center footer-menu1-row-section-div pb-4">
                                            <div class="col-md-1 text-end drag-btn">
                                                <h4><i class="bi bi-grip-vertical"></i></h4>
                                            </div>
                                            <div class="col-md-5 pl-5">
                                                <label for="footer_menu1title" class="form-label">Menu Title</label>
                                                <input type="text" class="form-control footer_menu1title" name="general[footermenu1][0][title]" id="footer_menu1title">
                                            </div>
                                            <div class="col-md-5 pr-5 pl-5">
                                                <label for="footer_menu1title_link" class="form-label">Menu Link</label>
                                                <select class="form-select footer_menu1title-link" name="general[footermenu1][0][link]" id="footer_menu1title_link" >
                                                    @foreach($pageList as $pageKey => $pageData)
                                                        <option value="{{ $pageData['url'] }}">{{ $pageData['title'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <div class="non-draggable-item col-md-1 d-none" draggable="false">
                                                    <a class="btn btn-outline-primary remove-footer-menu1-row non-draggable-item" draggable="false">
                                                        <i class="icofont-minus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="footer-site-logo" class="form-label">Footer Menu 1</label>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Menu Title</label>
                                            <input type="text" class="form-control footer_menu2title" name="general[footermenu2title]" id="footer_menu2title"
                                            value="{{ isset($settings['footer']['footermenu2title']) ? $settings['footer']['footermenu2title'] : '' }}">
                                        </div>
                                    </div>

                                    <div id="footer-menu2-div">
                                        <div class="row">
                                            <div class="col-md-11"></div>
                                            <div class="col-md-1 text-end">
                                                <a class="btn btn-outline-primary add-footer-menu2">
                                                    <i class="icofont-plus"></i>
                                                </a>
                                            </div>
                                        </div>

                                        @if(isset($settings['footer']['footermenu2']) && $settings['footer']['footermenu2'])
                                            @php
                                                $count = 0;
                                            @endphp
                                            @foreach($settings['footer']['footermenu2'] as $key => $menu2)
                                                <div class="row g-3 align-items-center footer-menu2-row-section-div pb-4">
                                                    <div class="col-md-1 text-end drag-btn">
                                                        <h4><i class="bi bi-grip-vertical"></i></h4>
                                                    </div>
                                                    <div class="col-md-5 pl-5">
                                                        <label for="footer_menu2title_{{$count}}" class="form-label">Menu Title</label>
                                                        <input type="text" class="form-control footer_menu2title" name="general[footermenu2][{{ $count }}][title]" id="footer_menu2title_{{$count}}" value="{{ $menu2['title'] }}">
                                                    </div>
                                                    <div class="col-md-5 pr-5 pl-5">
                                                        <label for="footer_menu2title_link_{{$count}}" class="form-label">Menu Link</label>
                                                        <select class="form-select footer_menu2title-link" name="general[footermenu2][{{ $count }}][link]" id="footer_menu2title_link_{{$count}}" >
                                                            @foreach($pageList as $pageKey => $pageData)
                                                                <option value="{{ $pageData['url'] }}" {{ isset($menu2['link']) && $pageData['url'] == $menu2['link'] ? 'selected' : '' }}>{{ $pageData['title'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 text-end">
                                                        <div class="non-draggable-item col-md-1 <?php echo count($settings['footer']['footermenu2']) == 1 ? 'd-none' : '' ?>" draggable="false">
                                                            <a class="btn btn-outline-primary remove-footer-menu2-row non-draggable-item" draggable="false">
                                                                <i class="icofont-minus"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $count++;
                                                @endphp
                                            @endforeach
                                        @else
                                        <div class="row g-3 align-items-center footer-menu2-row-section-div pb-4">
                                            <div class="col-md-1 text-end drag-btn">
                                                <h4><i class="bi bi-grip-vertical"></i></h4>
                                            </div>
                                            <div class="col-md-5 pl-5">
                                                <label for="footer_menu2title" class="form-label">Menu Title</label>
                                                <input type="text" class="form-control footer_menu2title" name="general[footermenu2][0][title]" id="footer_menu2title">
                                            </div>
                                            <div class="col-md-5 pr-5 pl-5">
                                                <label for="footer_menu2title_link" class="form-label">Menu Link</label>
                                                <select class="form-select footer_menu2title-link" name="general[footermenu2][0][link]" id="footer_menu2title_link" >
                                                    @foreach($pageList as $pageKey => $pageData)
                                                        <option value="{{ $pageData['url'] }}">{{ $pageData['title'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <div class="non-draggable-item col-md-1 d-none" draggable="false">
                                                    <a class="btn btn-outline-primary remove-footer-menu2-row non-draggable-item" draggable="false">
                                                        <i class="icofont-minus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </div>


                            <div class="row pb-3">
                                <div class="col-md-12">
                                    <label for="footer-site-logo" class="form-label">Address</label>
                                    @php 
                                        $footerAddress = isset($settings['footer']['footeraddress']) ? $settings['footer']['footeraddress'] : old('footeraddress');
                                    @endphp
                                    <textarea name="general[footeraddress]" class="form-control" id="footeraddress" cols="30" rows="10">{{ $footerAddress }}</textarea>
                                </div>
                            </div>

                            <div class="row pb-3">
                                <div class="col-md-12">
                                    <label for="footer-site-logo" class="form-label">Signup for Email</label>
                                    <input type="text" class="form-control footer_signupforemailtitle" name="general[footersignupforemailtitle]" id="footer_signupforemailtitle"
                                            value="{{ isset($settings['footer']['footersignupforemailtitle']) ? $settings['footer']['footersignupforemailtitle'] : '' }}">
                                </div>
                            </div>

                            <div class="row pb-3">
                                <div class="col-md-12">
                                    <label for="footer-site-logo" class="form-label">Signup for Email Text</label>
                                    <textarea type="text" class="form-control footer_signupforemailtext" name="general[footersignupforemailtext]" id="footer_signupforemailtext">{{ isset($settings['footer']['footersignupforemailtext']) ? $settings['footer']['footersignupforemailtext'] : '' }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="footer-site-logo" class="form-label">Copyright text</label>
                                    <textarea type="text" class="form-control footer_copyrighttext" name="general[footercopyrighttext]" id="footer_copyrighttext">{{ isset($settings['footer']['footercopyrighttext']) ? $settings['footer']['footercopyrighttext'] : '' }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="submit-btn btn btn-primary mt-4">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{!! backendAssets('dist/assets/plugin/datatables/dataTables.bootstrap5.min.css') !!}">
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.8/draggable.bundle.legacy.min.css">
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.8/draggable.bundle.legacy.min.js"></script>
<script src="{!! backendAssets('dist/assets/js/general.js') !!}"></script>

<script>
    window.header_menu_count = '<?php echo isset($settings['header']['headermenu']) ? count($settings['header']['headermenu']) : 1; ?>';
    window.footer_menu1_count = '<?php echo isset($settings['footer']['footermenu1']) ? count($settings['footer']['footermenu1']) : 1; ?>';
    window.footer_menu2_count = '<?php echo isset($settings['footer']['footermenu2']) ? count($settings['footer']['footermenu2']) : 1; ?>';
    jQuery(document).ready(function() {
        loadImgPreview('#site-logo', '#site-logo-preview');

        $(document).on('click', '.add-header-menu', function() {
            var container = $('.header-menu-row-section-div:first');
            var clone = container.clone();

            $(clone).find('.menutitle').val('');
            $(clone).find('.menutitle-link').val('').trigger('change');

            $(clone).find('.menutitle').attr('name',' general[headermenu]['+ window.header_menu_count +'][title]');
            $(clone).find('.menutitle').attr('id', 'menutitle_' + window.header_menu_count);
            $(clone).find('.menutitle_label').attr('for', 'menutitle_' + window.header_menu_count);

            $(clone).find('.menutitle-link').attr('name',' general[headermenu]['+ window.header_menu_count +'][link]');
            $(clone).find('.menutitle-link').attr('id', 'menutitle_link_' + window.header_menu_count);
            $(clone).find('.menutitle-link_label').attr('for', 'menutitle_link_' + window.header_menu_count);

            $('#header-menu-div').append(clone);
            window.header_menu_count++;

            $('.remove-header-menu-row').parent().removeClass('d-none');
        });

        $(document).on('click', '.remove-header-menu-row', function() {
            if($('.header-menu-row-section-div').length > 1) {
                $(this).closest('.header-menu-row-section-div').remove(); 
            }

            if($('.header-menu-row-section-div').length == 1) {
                $('.add-header-menu').removeClass('d-none');
                $('.remove-header-menu-row').parent().addClass('d-none');
            }

            var dataToRemove = [];
            dataToRemove.push($(this).closest('.header-menu-row-section-div').find('.offer-id').val());

            // if(dataToRemove.length) {
            //     $('input[name="home[offer][remove]"]').val($('input[name="home[offer][remove]"]').val() + dataToRemove.join(',')); //combine old value also
            // }
        });


        loadImgPreview('#footer-site-logo', '#footer-site-logo-preview');

        $(document).on('click', '.add-footer-menu1', function() {
            var container = $('.footer-menu1-row-section-div:first');
            var clone = container.clone();

            $(clone).find('.footer_menu1title').val('');
            $(clone).find('.footer_menu1title-link').val('').trigger('change');

            $(clone).find('.footer_menu1title').attr('name',' general[footermenu1]['+ window.footer_menu1_count +'][title]');
            $(clone).find('.footer_menu1title').attr('id', 'footer_menu1title_' + window.footer_menu1_count);
            $(clone).find('.footer_menu1title_label').attr('for', 'footer_menu1title_' + window.footer_menu1_count);

            $(clone).find('.footer_menu1title-link').attr('name',' general[footermenu1]['+ window.footer_menu1_count +'][link]');
            $(clone).find('.footer_menu1title-link').attr('id', 'footer_menu1title_link_' + window.footer_menu1_count);
            $(clone).find('.footer_menu1title-link_label').attr('for', 'footer_menu1title_link_' + window.footer_menu1_count);

            $('#footer-menu1-div').append(clone);
            window.footer_menu1_count++;

            $('.remove-footer-menu1-row').parent().removeClass('d-none');
        });

        $(document).on('click', '.remove-footer-menu1-row', function() {
            if($('.footer-menu1-row-section-div').length > 1) {
                $(this).closest('.footer-menu1-row-section-div').remove(); 
            }

            if($('.footer-menu1-row-section-div').length == 1) {
                $('.add-footer-menu1').removeClass('d-none');
                $('.remove-footer-menu1-row').parent().addClass('d-none');
            }

            var dataToRemove = [];
            dataToRemove.push($(this).closest('.footer-menu1-row-section-div').find('.offer-id').val());

            // if(dataToRemove.length) {
            //     $('input[name="home[offer][remove]"]').val($('input[name="home[offer][remove]"]').val() + dataToRemove.join(',')); //combine old value also
            // }
        });


        loadImgPreview('#footer-site-logo', '#footer-site-logo-preview');

        $(document).on('click', '.add-footer-menu2', function() {
            var container = $('.footer-menu2-row-section-div:first');
            var clone = container.clone();

            $(clone).find('.footer_menu2title').val('');
            $(clone).find('.footer_menu2title-link').val('').trigger('change');

            $(clone).find('.footer_menu2title').attr('name',' general[footermenu2]['+ window.footer_menu2_count +'][title]');
            $(clone).find('.footer_menu2title').attr('id', 'footer_menu2title_' + window.footer_menu2_count);
            $(clone).find('.footer_menu2title_label').attr('for', 'footer_menu2title_' + window.footer_menu2_count);

            $(clone).find('.footer_menu2title-link').attr('name',' general[footermenu2]['+ window.footer_menu2_count +'][link]');
            $(clone).find('.footer_menu2title-link').attr('id', 'footer_menu2title_link_' + window.footer_menu2_count);
            $(clone).find('.footer_menu2title-link_label').attr('for', 'footer_menu2title_link_' + window.footer_menu2_count);

            $('#footer-menu2-div').append(clone);
            window.footer_menu2_count++;

            $('.remove-footer-menu2-row').parent().removeClass('d-none');
        });

        $(document).on('click', '.remove-footer-menu2-row', function() {
            if($('.footer-menu2-row-section-div').length > 1) {
                $(this).closest('.footer-menu2-row-section-div').remove(); 
            }

            if($('.footer-menu2-row-section-div').length == 1) {
                $('.add-footer-menu2').removeClass('d-none');
                $('.remove-footer-menu2-row').parent().addClass('d-none');
            }

            var dataToRemove = [];
            dataToRemove.push($(this).closest('.footer-menu2-row-section-div').find('.offer-id').val());

            // if(dataToRemove.length) {
            //     $('input[name="home[offer][remove]"]').val($('input[name="home[offer][remove]"]').val() + dataToRemove.join(',')); //combine old value also
            // }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var container = document.getElementById('header-menu-div');
        var draggable = new Draggable.Sortable(container, {
            draggable: '.header-menu-row-section-div',
            handle: '.drag-btn',
        });

        var container = document.getElementById('footer-menu1-div');
        var draggable = new Draggable.Sortable(container, {
            draggable: '.footer-menu1-row-section-div',
            handle: '.drag-btn',
        });

        var container = document.getElementById('footer-menu2-div');
        var draggable = new Draggable.Sortable(container, {
            draggable: '.footer-menu2-row-section-div',
            handle: '.drag-btn',
        });
    });

    tinymceInit('footeraddress');
</script>
@endpush
