@extends(frontView('layouts.app'))

@section('title', 'Home')

@section('content')

    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close">
                <span class="icofont-close js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    <div class="hero overlay">

        <div class="img-bg rellax">
            <img src="{!! frontAssets('images/hero_1.webp') !!}" alt="Image" class="img-fluid">
        </div>

        <div class="container">
            <div class="row align-items-center justify-content-start">
                <div class="col-lg-5">
                    <div class="brand mb-5" data-aos="fade-up">
                        <!-- <img src="{{ asset($user->front_logo) ?? frontAssets('images/logo.svg') }}" alt="Image" class="add-width-logo"> -->
                    </div>
                    <h4 data-aos="fade-up" class="text-white">Hello, Legend!</h4>
                    <h1 class="heading" data-aos="fade-up">I'm <br>
                    {!! $user->name !!}</h1>
                    <p class="mb-5" data-aos="fade-up">{!! $user->front_description !!}</p>

                    <div data-aos="fade-up">
                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary">
                            <span class="me-1">Book Here</span>
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white" />
                                <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="shpae-divider">
            <div class="container-fluid p-0">
                <img src="{!! frontAssets('images/Vector-shape.svg') !!}" alt="Image" class="img-fluid" />
            </div>
        </div>
    </div>

    <div class="section position-relative">
        <div class="side-phrase muted-left fs-50">
            {{ $user->name }}
        </div>
        <div class="container">
            <div class="row ">
                <div class="col-lg-5  mb-md-2 mb-lg-0 mb-sm-0 mb-0">
                    <div class="image-stack mb-5 mb-lg-0">
                        <div class="image-stack__item image-stack__item--bottom" data-aos="fade-up">
                            <img src="{!! frontAssets('images/about-new.png') !!}" alt="Image" class="img-fluid " style="border-radius: 20px;">
                        </div>

                    </div>
                </div>
                <div class="col-lg-7" style="z-index: 9;">
                    <div>
                        <h2 class="heading mb-3 heading-absolute d-flex" data-aos="fade-up" data-aos-delay="100">
                            <div class="text-nowrap">{!! $user->about_us_title !!}</div>
                            <svg width="102" height="139" viewBox="0 0 102 139" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M75.9188 138.19C76.1091 138.709 76.6837 138.975 77.2022 138.784L85.651 135.683C86.1695 135.493 86.4355 134.918 86.2453 134.4C86.055 133.881 85.4804 133.615 84.9619 133.806L77.4518 136.562L74.6954 129.052C74.5051 128.534 73.9305 128.268 73.412 128.458C72.8936 128.648 72.6275 129.223 72.8178 129.741L75.9188 138.19ZM0.553559 1.06478C1.91519 5.71829 4.21835 8.91952 7.22049 11.021C10.2057 13.1106 13.7937 14.051 17.6282 14.3517C21.4597 14.6522 25.6033 14.3185 29.748 13.8054C31.8231 13.5485 33.9133 13.2445 35.9709 12.9494C38.0328 12.6537 40.0632 12.3666 42.0388 12.1396C46.0012 11.6844 49.6749 11.4801 52.8435 11.9149C56.0007 12.3481 58.5721 13.4038 60.4326 15.399C62.2942 17.3954 63.564 20.4611 63.8546 25.1563C64.1456 29.8588 63.4481 36.118 61.448 44.3916L63.3921 44.8615C65.4155 36.4912 66.1591 30.0146 65.8508 25.0328C65.542 20.0437 64.1721 16.4766 61.8953 14.0351C59.6175 11.5924 56.552 10.405 53.1154 9.93349C49.6903 9.4635 45.8135 9.69277 41.8106 10.1527C39.8035 10.3832 37.7474 10.6741 35.6869 10.9697C33.6221 11.2658 31.5539 11.5665 29.5023 11.8205C25.3937 12.3292 21.4108 12.6422 17.7846 12.3578C14.1614 12.0737 10.9605 11.1976 8.3674 9.38249C5.79123 7.57921 3.72669 4.78746 2.47307 0.503122L0.553559 1.06478ZM61.448 44.3916C59.3242 53.1775 56.2324 57.7546 53.4864 59.7281C52.1246 60.7068 50.8705 61.0327 49.8459 60.9698C48.8238 60.907 47.9627 60.4554 47.3553 59.7506C46.1578 58.3609 45.8117 55.7575 47.8449 52.967C49.8857 50.1662 54.3445 47.2136 62.6107 45.6082L62.2294 43.6449C53.6955 45.3023 48.6803 48.4243 46.2284 51.7892C43.769 55.1646 43.9117 58.8182 45.8402 61.0561C46.7958 62.165 48.1569 62.8698 49.7233 62.966C51.2871 63.0621 52.9876 62.5495 54.6536 61.3522C57.9632 58.9736 61.2196 53.8486 63.3921 44.8615L61.448 44.3916ZM62.6107 45.6082C73.1671 43.5581 81.485 43.3815 87.4792 45.3843C90.4566 46.3791 92.8475 47.9072 94.6691 50.0057C96.4921 52.106 97.7869 54.8263 98.4962 58.2672C99.922 65.1838 98.9623 74.9145 95.2742 88.009C91.5927 101.08 85.225 117.394 75.9502 137.425L77.765 138.266C87.0613 118.188 93.4772 101.766 97.1993 88.5513C100.915 75.3596 101.979 65.2543 100.455 57.8634C99.6896 54.1506 98.2665 51.0992 96.1794 48.6947C94.0908 46.2885 91.3793 44.5787 88.113 43.4874C81.6195 41.3178 72.8843 41.5756 62.2294 43.6449L62.6107 45.6082Z" fill="#649EF7" />
                            </svg>
                            <span class="border-heading"></span>
                        </h2>
                        <div class="content-wrap">
                            @php
                                $fullDescription = $user->about_us_description;
                                $shortDescription = Str::limit(strip_tags($fullDescription), 200, '');
                            @endphp
                        
                            <div id="short-description">
                                <p>{!! $shortDescription !!}</p>
                                @if (strlen(strip_tags($fullDescription)) > 200)
                                    <!-- <button id="read-more-btn" class="text-dark fw-bold">Read more</button> -->
                                    <a href="javascript:void(0);" id="read-more-btn" class="text-dark fw-bold">Read more...</a>
                                @endif
                            </div>
                            
                            <div id="full-description" style="display: none;">
                                @php
                                $parts = preg_split('/solutions\./i', $fullDescription, 2, PREG_SPLIT_DELIM_CAPTURE);
    
                                if (count($parts) > 1) {
                                    $firstPart = $parts[0] . 'solutions.'; // Include "solutions." in the first part
                                    $secondPart = $parts[1];
                                } else {
                                    $firstPart = $fullDescription;
                                    $secondPart = '';
                                }
                                @endphp
                                {!! $firstPart !!}
                                @if ($secondPart)
                                <div class="kerry-obryan-section">
                                    {!! $secondPart !!}
                                </div>
                                <a href="javascript:void(0);" id="read-less-btn" class="text-dark fw-bold"><i class="fas fa-chevron-up"></i> Read less...</a>
                                @endif
                                <!-- <button id="read-less-btn" class="text-dark fw-bold">Read less</button> -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="section pt-3 pb-5 booking-type-main-div">
        <div class="container">
            <div class="row">
                <div class="col-12" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="heading mb-5  d-flex" data-aos="fade-up" data-aos-delay="100">
                        <div class="text-nowrap mt-2 h2">Booking Types</div>
                        <span class="border-heading"></span>
                    </h2>
                </div>
            </div>
            <div class="service-sec d-flex" id="bookingtypecontainer"></div>
            <!--<div class="service-sec d-flex">-->
            <!--	<div class="media-entry p-0" data-aos="fade-up" data-aos-delay="100">-->
            <!--		<div class="text-services">-->
            <!--			<h3 class="text-center"><a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="">Athlete - developing <br>through to elite</a></h3>-->
            <!--		</div>-->
            <!--		<a href="https://booking.biohealthpassport.com.au/kerry-obryan">-->
            <!--			<img src="images/img_v_1.webp" alt="Image" class="img-fluid">-->
            <!--		</a>-->
            <!--		<div data-aos="fade-up">-->
            <!--			<a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary mt-2 w-100">-->
            <!--				<span class="me-1">Book A Call </span>-->
            <!--				<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
            <!--					<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
            <!--					<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
            <!--				</svg>-->
            <!--			</a>-->
            <!--		</div>-->
            <!--	</div>	-->
            <!--	<div class="media-entry p-0" data-aos="fade-up" data-aos-delay="200">-->
            <!--		<div class="text-services">-->
            <!--			<h3 class="text-center"><a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="">Individual interested<br> in health &amp; longevity</a></h3>-->
            <!--		</div>-->
            <!--		<a href="https://booking.biohealthpassport.com.au/kerry-obryan">-->
            <!--			<img src="images/service-2.jpg" alt="Image" class="img-fluid">-->
            <!--		</a>-->
            <!--		<div data-aos="fade-up">-->
            <!--			<a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary mt-2 w-100">-->
            <!--				<span class="me-1">Book A Call </span>-->
            <!--				<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
            <!--					<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
            <!--					<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
            <!--				</svg>-->
            <!--			</a>-->
            <!--		</div>-->
            <!--	</div>	-->
            <!--	<div class="media-entry p-0" data-aos="fade-up" data-aos-delay="300">-->
            <!--		<div class="text-services">-->
            <!--			<h3 class="text-center"><a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="">Healthy cooking classes <br>for groups/teams</a></h3>-->
            <!--		</div>-->
            <!--		<a href="https://booking.biohealthpassport.com.au/kerry-obryan">-->
            <!--			<img src="images/service-3.jpg" alt="Image" class="img-fluid">-->
            <!--		</a>-->
            <!--		<div data-aos="fade-up">-->
            <!--			<a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary mt-2 w-100">-->
            <!--				<span class="me-1">Book A Call </span>-->
            <!--				<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
            <!--					<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
            <!--					<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
            <!--				</svg>-->
            <!--			</a>-->
            <!--		</div>-->
            <!--	</div>	-->
            <!--	<div class="media-entry p-0" data-aos="fade-up" data-aos-delay="400">-->
            <!--		<div class="text-services">-->
            <!--			<h3 class="text-center"><a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="">Guest speaker for <br>groups &amp; event</a></h3>-->
            <!--		</div>-->
            <!--		<a href="https://booking.biohealthpassport.com.au/kerry-obryan">-->
            <!--			<img src="images/service-4.jpg" alt="Image" class="img-fluid">-->
            <!--		</a>-->
            <!--		<div data-aos="fade-up">-->
            <!--			<a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary mt-2 w-100">-->
            <!--				<span class="me-1">Book A Call </span>-->
            <!--				<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
            <!--					<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
            <!--					<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
            <!--				</svg>-->
            <!--			</a>-->
            <!--		</div>-->
            <!--	</div>	-->
            <!--	<div class="media-entry p-0" data-aos="fade-up" data-aos-delay="500">-->
            <!--		<div class="text-services">-->
            <!--			<h3 class="text-center"><a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="">Follow up Nutrition <br>consultation</a></h3>-->
            <!--		</div>-->
            <!--		<a href="https://booking.biohealthpassport.com.au/kerry-obryan">-->
            <!--			<img src="images/service-5.jpg" alt="Image" class="img-fluid">-->
            <!--		</a>-->
            <!--		<div data-aos="fade-up">-->
            <!--			<a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary mt-2 w-100">-->
            <!--				<span class="me-1">Book A Call </span>-->
            <!--				<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
            <!--					<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
            <!--					<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
            <!--				</svg>-->
            <!--			</a>-->
            <!--		</div>-->
            <!--	</div>	-->
            <!--	<div class="media-entry p-0" data-aos="fade-up" data-aos-delay="500">-->
            <!--		<div class="text-services">-->
            <!--			<h3 class="text-center"><a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="">First Nutrition <br>Consultation</a></h3>-->
            <!--		</div>-->
            <!--		<a href="https://booking.biohealthpassport.com.au/kerry-obryan">-->
            <!--			<img src="images/service-6.jpg" alt="Image" class="img-fluid">-->
            <!--		</a>-->
            <!--		<div data-aos="fade-up">-->
            <!--			<a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary mt-2 w-100">-->
            <!--				<span class="me-1">Book A Call </span>-->
            <!--				<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
            <!--					<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
            <!--					<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
            <!--				</svg>-->
            <!--			</a>-->
            <!--		</div>-->
            <!--	</div>-->
            <!--	<div class="media-entry p-0" data-aos="fade-up" data-aos-delay="500">-->
            <!--		<div class="text-services">-->
            <!--			<h3 class="text-center"><a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="">Dexa Body <br>Composition Scan</a></h3>-->
            <!--		</div>-->
            <!--		<a href="https://booking.biohealthpassport.com.au/kerry-obryan">-->
            <!--			<img src="images/service-7.png" alt="Image" class="img-fluid">-->
            <!--		</a>-->
            <!--		<div data-aos="fade-up">-->
            <!--			<a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary mt-2 w-100">-->
            <!--				<span class="me-1">Book A Call </span>-->
            <!--				<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
            <!--					<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
            <!--					<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
            <!--				</svg>-->
            <!--			</a>-->
            <!--		</div>-->
            <!--	</div>-->

            <!--</div>-->
            <div class="col-12 text-center mt-5" style="text-align: center !important;">
                <div id="prevnext-service-one">
                    <span class="prev me-1" data-controls="prev">
                        <svg width="14" height="16" viewBox="0 0 14 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.521729 8.70303C-0.144938 8.31813 -0.144939 7.35588 0.521728 6.97098L12.2446 0.202802C12.9112 -0.182099 13.7446 0.299026 13.7446 1.06883L13.7446 14.6052C13.7446 15.375 12.9112 15.8561 12.2446 15.4712L0.521729 8.70303Z"
                                fill="#649EF7" />
                        </svg>
                    </span>
                    <span class="next" data-controls="next">
                        <svg width="14" height="16" viewBox="0 0 14 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.4783 8.70303C14.1449 8.31813 14.1449 7.35588 13.4783 6.97098L1.75545 0.202802C1.08878 -0.182099 0.255445 0.299026 0.255445 1.06883L0.255445 14.6052C0.255445 15.375 1.08878 15.8561 1.75544 15.4712L13.4783 8.70303Z"
                                fill="#649EF7" />
                        </svg>
                    </span>
                </div>
            </div>

        </div>
    </div>


    <div class="section section-3 pt-3 pb-0" data-aos="fade-up" data-aos-delay="100">
       
    </div>
    @if($testimonials)
        <section class="section pb-3 pt-4 my-3 testimonial-section-main-div">
            <div class="col-lg-12 text-center mb-5" data-aos="fade-up" style="text-align: center !important;">
                <h2 class="heading mb-5  d-flex align-items-center justify-content-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="border-heading-top position-relative"></div>
                    <div class="text-nowrap">Testimonials</div>
                    <div class="border-heading d-block position-relative using-my-heading mt-0"></div>
                </h2>
                <div class="heading-content" data-aos="fade-up">
                    <h2><span class="d-block">What Clients are Saying?</span></h2>
                    <p>People around the world are embracing inside tracker's data driven approach to health span
                        optimization.</p>
                </div>
            </div>
            <div class="col-12 mx-auto">
                <div class="row">
                    <div class="col-xl-6 col-lg-8 col-md-10 px-md-0 px-4 mx-auto position-relative">
                        <div class="wide-slider-testimonial-wrap-two">
                            <div class="wide-slider-testimonial-two">
                                @foreach($testimonials as $tId => $testimonial)
                                <?php // dd($testimonial); ?>
                                <div class="">
                                    <div class="d-flex flex-sm-row flex-column align-items-center" id="using-{{ $tId }}">
                                        <div class="pe-xxl-5 pe-sm-3 mb-sm-0 mb-3 border-custom-left">
                                            <img src="{{ $testimonial['image'] }}" alt="" class="slider-added-imges" style="">
                                        </div>
                                        <div class="no-display-half-part">
                                            <div class="quote-using">
                                                <i class="fa-solid fa-quote-left"></i>
                                            </div>
                                            <p class="mb-3">{{ $testimonial['review'] }}</p>
                                            <h5 class="mb-0">{{ $testimonial['name'] }}</h5>
                                            <p class="position mb-0">{{ $testimonial['designation'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="prevnext-testimonial-one">
                            <span class="prev me-1" data-controls="prev">
                                <svg width="14" height="16" viewBox="0 0 14 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M0.521729 8.70303C-0.144938 8.31813 -0.144939 7.35588 0.521728 6.97098L12.2446 0.202802C12.9112 -0.182099 13.7446 0.299026 13.7446 1.06883L13.7446 14.6052C13.7446 15.375 12.9112 15.8561 12.2446 15.4712L0.521729 8.70303Z"
                                        fill="#649EF7" />
                                </svg>
                            </span>
                            <span class="next" data-controls="next">
                                <svg width="14" height="16" viewBox="0 0 14 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.4783 8.70303C14.1449 8.31813 14.1449 7.35588 13.4783 6.97098L1.75545 0.202802C1.08878 -0.182099 0.255445 0.299026 0.255445 1.06883L0.255445 14.6052C0.255445 15.375 1.08878 15.8561 1.75544 15.4712L13.4783 8.70303Z"
                                        fill="#649EF7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="section py-5 client-sec">
        <div class="container">
            <div class="col-lg-12" data-aos="fade-up">
                <h2 class="heading mb-3  d-flex align-items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-nowrap">Teams and organisations<br>
                        who I’ve worked with
                    </div>

                    <span class="border-heading"></span>
                </h2>
            </div>
           
        </div>
        <article class="wrapper">
            <div class="marquee-main">
              
                <div class="marquee" id="marquee-top">
                    <div class="marquee__group"></div>
                    <div aria-hidden="true" class="marquee__group"></div>
                </div>

                <div class="marquee marquee--reverse" id="marquee-bottom">
                    <div class="marquee__group"></div>
                    <div aria-hidden="true" class="marquee__group"></div>
                </div>

            </div>
        </article>
    </div>

    <div class="section section-contact pb-0 pt-0">
        <div class="container">
            <form id="query-form" method="POST" action="{{ route('save-query') }}" autocomplete="on">
                @csrf
                <!-- show message from flash -->
                @if (session('confirmmsg'))
                    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <svg width="100" height="100" viewBox="0 0 100 100" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_96_828)">
                                            <path
                                                d="M62.9187 5.43774C61.2364 3.71612 59.227 2.34812 57.0084 1.41414C54.7899 0.480153 52.4071 -0.000976563 50 -0.000976562C47.5929 -0.000976562 45.21 0.480153 42.9915 1.41414C40.773 2.34812 38.7635 3.71612 37.0812 5.43774L33.1937 9.42524L27.6312 9.35649C25.2234 9.328 22.8342 9.78123 20.6041 10.6895C18.374 11.5979 16.348 12.9429 14.6453 14.6456C12.9426 16.3483 11.5976 18.3743 10.6893 20.6044C9.78096 22.8345 9.32773 25.2237 9.35623 27.6315L9.41873 33.194L5.44373 37.0815C3.7221 38.7638 2.3541 40.7733 1.42012 42.9918C0.486134 45.2103 0.00500488 47.5931 0.00500488 50.0002C0.00500488 52.4074 0.486134 54.7902 1.42012 57.0087C2.3541 59.2272 3.7221 61.2367 5.44373 62.919L9.42498 66.8065L9.35623 72.369C9.32773 74.7768 9.78096 77.166 10.6893 79.3961C11.5976 81.6262 12.9426 83.6522 14.6453 85.3549C16.348 87.0576 18.374 88.4026 20.6041 89.3109C22.8342 90.2193 25.2234 90.6725 27.6312 90.644L33.1937 90.5815L37.0812 94.5565C38.7635 96.2781 40.773 97.6461 42.9915 98.5801C45.21 99.5141 47.5929 99.9952 50 99.9952C52.4071 99.9952 54.7899 99.5141 57.0084 98.5801C59.227 97.6461 61.2364 96.2781 62.9187 94.5565L66.8062 90.5752L72.3687 90.644C74.7765 90.6725 77.1657 90.2193 79.3958 89.3109C81.6259 88.4026 83.6519 87.0576 85.3546 85.3549C87.0573 83.6522 88.4024 81.6262 89.3107 79.3961C90.219 77.166 90.6722 74.7768 90.6437 72.369L90.5812 66.8065L94.5562 62.919C96.2778 61.2367 97.6458 59.2272 98.5798 57.0087C99.5138 54.7902 99.9949 52.4074 99.9949 50.0002C99.9949 47.5931 99.5138 45.2103 98.5798 42.9918C97.6458 40.7733 96.2778 38.7638 94.5562 37.0815L90.575 33.194L90.6437 27.6315C90.6722 25.2237 90.219 22.8345 89.3107 20.6044C88.4024 18.3743 87.0573 16.3483 85.3546 14.6456C83.6519 12.9429 81.6259 11.5979 79.3958 10.6895C77.1657 9.78123 74.7765 9.328 72.3687 9.35649L66.8062 9.41899L62.9187 5.43774ZM64.7125 42.8377L45.9625 61.5877C45.6722 61.8788 45.3273 62.1097 44.9477 62.2672C44.568 62.4247 44.161 62.5058 43.75 62.5058C43.3389 62.5058 42.9319 62.4247 42.5523 62.2672C42.1726 62.1097 41.8278 61.8788 41.5375 61.5877L32.1625 52.2127C31.8719 51.9222 31.6414 51.5773 31.4842 51.1976C31.327 50.818 31.246 50.4111 31.246 50.0002C31.246 49.5893 31.327 49.1825 31.4842 48.8028C31.6414 48.4232 31.8719 48.0783 32.1625 47.7877C32.453 47.4972 32.798 47.2667 33.1776 47.1095C33.5572 46.9522 33.9641 46.8713 34.375 46.8713C34.7859 46.8713 35.1927 46.9522 35.5724 47.1095C35.952 47.2667 36.2969 47.4972 36.5875 47.7877L43.75 54.9565L60.2875 38.4127C60.8743 37.826 61.6701 37.4963 62.5 37.4963C63.3298 37.4963 64.1257 37.826 64.7125 38.4127C65.2993 38.9995 65.6289 39.7954 65.6289 40.6252C65.6289 41.4551 65.2993 42.251 64.7125 42.8377Z"
                                                fill="#155E5C" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_96_828">
                                                <rect width="100" height="100" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <h3 class="my-4">Thank you! <br>
                                    Your inquiry has been received. We'll get back to you shortly.</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="contact-wrap position-relative">
                    <h2 class="heading mb-5  d-flex align-items-start" data-aos="fade-up" data-aos-delay="100">
                        <div class="text-nowrap">Have more questions<br>about getting started?</div>
                        <span class="border-heading"></span>
                    </h2>
                    <div class="row justify-content-between align-items-start">
                        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                            <p>Reach out and we will get back to you ASAP</p>
                            <input type="hidden" name="slug" value="{{ $slug }}">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" autocomplete="name" placeholder="Your name"
                                    name="name">
                                <label for="name">Name</label>
                                @include('front.layouts.errors', ['field' => 'name'])
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email"  autocomplete="email" placeholder="name@example.com"
                                    name="email">
                                <label for="email">Email address</label>
                                @include('front.layouts.errors', ['field' => 'email'])
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="mobile_number"
                                    placeholder="Mobile number"  autocomplete="tel" name="mobile_number">
                                <label for="mobile_number">Mobile number</label>
                                @include('front.layouts.errors', ['field' => 'mobile_number'])
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="message" rows="3" name="message"></textarea>
                                <label for="message">What is your question?</label>
                                @include('front.layouts.errors', ['field' => 'message'])
                            </div>
                            <div class="my-4" data-aos="fade-up" data-aos-delay="200">
                                <button type="submit" class="btn btn-primary btn-green fw-semibold px-sm-5 px-4"
                                    id="submit-contact-all-content">
                                    Submit
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>
                                        <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <img src="{{ frontAssets('images/contact.webp') }}" alt="Image"
                        class="img-fluid img-contact">
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
@endpush
<script>
    function initReadMore() {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }

        var $shortDesc = $('#short-description');
        var $fullDesc = $('#full-description');
        var $readMoreBtn = $('#read-more-btn');
        var $readLessBtn = $('#read-less-btn');

        $readMoreBtn.on('click', function() {
            $shortDesc.hide();
            $fullDesc.show();
        });

        $readLessBtn.on('click', function() {
            $fullDesc.hide();
            $shortDesc.show();
        });
    }

    // Try to initialize when the DOM is ready
    document.addEventListener('DOMContentLoaded', initReadMore);

    // Fallback: If jQuery loads late, try again after a short delay
    setTimeout(initReadMore, 1000);
</script>
