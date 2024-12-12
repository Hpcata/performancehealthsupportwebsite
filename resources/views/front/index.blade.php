@extends(frontView('layouts.app'))

@section('title', 'Home')

@section('content')
@php
    $showHeader = !empty($user->front_logo) && 
                  !empty($user->front_title) && 
                  !empty($user->front_description) && 
                  !empty($user->about_us_image);
@endphp

@if($showHeader)
    <header>
        <div class="container-fluid p-0 overflow-hidden">
            <div class="primary-bg-color main-content">
                <div class="row">
                <div class="col-lg-6 ">
                    <div class="left-aside">
                    <div class="mb-3 powered-by-box aos-init aos-animate" data-aos="fade-up">
                        <p>Powered by &nbsp;<span>BioHealth<span>Passport</span></span></p>
                    </div>
                    <img src="{{ asset('public/' . $user->front_logo) }}" class="logo mb-3" alt="logo-img">
                    <h1>{!! $user->front_title !!}</h1>
                    <p class="content-description mt-4 mb-5">{!! $user->front_description !!}</p>
                    <a class="btn-booking-type booking-type">Booking Types <img src="{{ asset('public/uploads/right-arrow.png') }}" height="13px"
                        width="13px"></a>
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="right-aside position-relative">
                        <img src="{{ asset('public/uploads/image-plane-new.svg') }}" class="img-plane d-none d-lg-block">
                        <div class="position-relative">
                            <img src="{{ asset('public/uploads/laptop.png') }}" alt="image-mockup" class="img-fluid img-mockup">
                            <div class="bg-white main-background" style=" width: 100%;"></div>

                            <div class="profile-details mx-auto d-flex justify-content-center flex-column text-center">
                            <img src="{{ asset('public/uploads/hero01.png') }}" alt="hero-img" class="mx-auto">
                            <h4 class="text-white">{{ $user->name }}</h4>
                            <span class="text-white">{{ $user->designation }}</span>
                            </div>
                            <div class="d-flex know-about">
                                <h2 class="text-black"> {!! $user->about_us_title !!}</h2>
                                <img src="{{ asset('public/' . $user->about_us_image) }}" class=''>
                            </div>
                        </div>
                        <div class="profile-description">
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
                                <a href="javascript:void(0);" id="read-less-btn" class="text-dark fw-bold"><i class="fas fa-chevron-up"></i></a>
                                    <div class="kerry-obryan-section">
                                        {!! $secondPart !!}
                                    </div>
                                @endif
                                <!-- <button id="read-less-btn" class="text-dark fw-bold">Read less</button> -->
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </header>
    @endif
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
                        <!--<img src="{!! frontAssets('images/logo.svg') !!}" alt="Image" class="add-width-logo">-->
                    </div>
                    <h4 data-aos="fade-up" class="text-white">Hello, Legend!</h4>
                    <h1 class="heading" data-aos="fade-up">I'm <br>
                        Kerry O’Bryan</h1>
                    <p class="mb-5" data-aos="fade-up">Health Performance Manager, with backgrounds in strength & conditioning coaching to elite athletes and dietitian to Olympians.</p>

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
            Kerry O’Bryan
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
                            <div class="text-nowrap">Know more <br>
                                about me</div>
                            <svg width="102" height="139" viewBox="0 0 102 139" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M75.9188 138.19C76.1091 138.709 76.6837 138.975 77.2022 138.784L85.651 135.683C86.1695 135.493 86.4355 134.918 86.2453 134.4C86.055 133.881 85.4804 133.615 84.9619 133.806L77.4518 136.562L74.6954 129.052C74.5051 128.534 73.9305 128.268 73.412 128.458C72.8936 128.648 72.6275 129.223 72.8178 129.741L75.9188 138.19ZM0.553559 1.06478C1.91519 5.71829 4.21835 8.91952 7.22049 11.021C10.2057 13.1106 13.7937 14.051 17.6282 14.3517C21.4597 14.6522 25.6033 14.3185 29.748 13.8054C31.8231 13.5485 33.9133 13.2445 35.9709 12.9494C38.0328 12.6537 40.0632 12.3666 42.0388 12.1396C46.0012 11.6844 49.6749 11.4801 52.8435 11.9149C56.0007 12.3481 58.5721 13.4038 60.4326 15.399C62.2942 17.3954 63.564 20.4611 63.8546 25.1563C64.1456 29.8588 63.4481 36.118 61.448 44.3916L63.3921 44.8615C65.4155 36.4912 66.1591 30.0146 65.8508 25.0328C65.542 20.0437 64.1721 16.4766 61.8953 14.0351C59.6175 11.5924 56.552 10.405 53.1154 9.93349C49.6903 9.4635 45.8135 9.69277 41.8106 10.1527C39.8035 10.3832 37.7474 10.6741 35.6869 10.9697C33.6221 11.2658 31.5539 11.5665 29.5023 11.8205C25.3937 12.3292 21.4108 12.6422 17.7846 12.3578C14.1614 12.0737 10.9605 11.1976 8.3674 9.38249C5.79123 7.57921 3.72669 4.78746 2.47307 0.503122L0.553559 1.06478ZM61.448 44.3916C59.3242 53.1775 56.2324 57.7546 53.4864 59.7281C52.1246 60.7068 50.8705 61.0327 49.8459 60.9698C48.8238 60.907 47.9627 60.4554 47.3553 59.7506C46.1578 58.3609 45.8117 55.7575 47.8449 52.967C49.8857 50.1662 54.3445 47.2136 62.6107 45.6082L62.2294 43.6449C53.6955 45.3023 48.6803 48.4243 46.2284 51.7892C43.769 55.1646 43.9117 58.8182 45.8402 61.0561C46.7958 62.165 48.1569 62.8698 49.7233 62.966C51.2871 63.0621 52.9876 62.5495 54.6536 61.3522C57.9632 58.9736 61.2196 53.8486 63.3921 44.8615L61.448 44.3916ZM62.6107 45.6082C73.1671 43.5581 81.485 43.3815 87.4792 45.3843C90.4566 46.3791 92.8475 47.9072 94.6691 50.0057C96.4921 52.106 97.7869 54.8263 98.4962 58.2672C99.922 65.1838 98.9623 74.9145 95.2742 88.009C91.5927 101.08 85.225 117.394 75.9502 137.425L77.765 138.266C87.0613 118.188 93.4772 101.766 97.1993 88.5513C100.915 75.3596 101.979 65.2543 100.455 57.8634C99.6896 54.1506 98.2665 51.0992 96.1794 48.6947C94.0908 46.2885 91.3793 44.5787 88.113 43.4874C81.6195 41.3178 72.8843 41.5756 62.2294 43.6449L62.6107 45.6082Z" fill="#649EF7" />
                            </svg>
                            <span class="border-heading"></span>
                        </h2>
                        <div class="content-wrap">
                            <p>I have been a health performance manager for over 10 years, with backgrounds in strength & conditioning coaching to elite athletes and dietitian/nutritionist to Olympians.</p>
                            <p data-aos="fade-up" data-aos-delay="200">I work primarily with elite and emerging athletes (and parents) who want the best high performance support, as well as men and women (40+ years) seeking individualised healthy ageing solutions.</p>

                            <p data-aos="fade-up" data-aos-delay="300">
                                I have developed high level systems of knowledge and experience training some of Australia’s best Olympic athletes and sports teams, backed by two degrees in Nutrition/Dietetics and Sports Science, whilst also sharing my expertise to students, as a Bond University lecturer. I geek out on any medical advancements and am a qualified Dexa practitioner, who values tracking what matters, when it matters to make the most informed decisions with a persons health.</p>
                            <p data-aos="fade-up" data-aos-delay="400">I create personalised bespoke programs, packed with advanced screening/diagnostics, muscle-focused nutrition, training/movement science & lifestyle tactics - to give you elite support towards peak performance and optimal longevity.</p>

                            <p data-aos="fade-up" data-aos-delay="600" class="text-semibold mb-0"><strong>Kerry O’Bryan</strong> <br>
                                MNutr&Diet, B.Sp.Ex.Sc, IOC Dip Nut</p>
                            <p data-aos="fade-up" data-aos-delay="700">(Dietitian/Sports Scientist/Strength & Conditioning Coach)</p>

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
        <!--	<div class="container position-relative likndin-section"> -->
        <!--<div class="row align-items-center justify-content-center w-100 mx-auto  mb-3">-->
        <!--	<div class="col-lg-12" data-aos="fade-up">-->
        <!--		<h2 class="heading mb-5  d-flex align-items-start" data-aos="fade-up" data-aos-delay="100">-->
        <!--			<div class="text-nowrap">Follow me on -->

        <!--			</div>-->
        <!--			<img src="images/linkedin.webp" class="img-fluid ms-1" width="50"/>-->

        <!--			<span class="border-heading"></span>-->
        <!--		</h2>-->
        <!--	</div>		-->

        <!--</div>-->

        <!--<div class="d-flex align-items-center justify-content-center  mb-5">-->
        <!--	<div class="col-md-10 col-sm-9 col-11">-->
        <!--		<div class="destination-slider-wrap">-->
        <!--			<div class="destination-slider">-->
        <!--				<div class="destination">-->
        <!--					<div class="card-wrap">-->
        <!--						<div class="thumb">-->
        <!--							<img src="images/img-1.webp" alt="Image" class="img-fluid">-->
        <!--						</div>-->
        <!--						<div class="mt-4">-->
        <!--							<a href="https://www.linkedin.com/posts/kerry-obryan_to-be-more-clear-on-my-previous-post-below-activity-7143854793122275328-RNvD" target="_blank" class="btn btn-primary mt-3">-->
        <!--								<span class="me-1">Read More</span>-->
        <!--								<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--									<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
        <!--									<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
        <!--								</svg>-->
        <!--							</a>-->
        <!--						</div>-->
        <!--					</div>-->
        <!--				</div>-->
        <!--				<div class="destination">-->
        <!--					<div class="card-wrap">-->
        <!--						<div class="thumb">-->
        <!--							<img src="images/img-2.webp" alt="Image" class="img-fluid">-->
        <!--						</div>-->
        <!--						<div class="mt-4">-->
        <!--							<a href="https://www.linkedin.com/posts/kerry-obryan_i-am-more-than-excited-to-be-working-with-activity-7143431926471663616-sSSO" target="_blank" class="btn btn-primary mt-3">-->
        <!--								<span class="me-1">Read More</span>-->
        <!--								<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--									<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
        <!--									<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
        <!--								</svg>-->
        <!--							</a>-->
        <!--						</div>-->
        <!--					</div>-->
        <!--				</div>-->
        <!--				<div class="destination">-->
        <!--					<div class="card-wrap">-->
        <!--						<div class="thumb">-->
        <!--							<img src="images/img-2.webp" alt="Image" class="img-fluid">-->
        <!--						</div>-->
        <!--						<div class="mt-4">-->
        <!--							<a href="https://www.linkedin.com/posts/kerry-obryan_i-am-more-than-excited-to-be-working-with-activity-7143431926471663616-sSSO?utm_source=share" class="btn btn-primary mt-3">-->
        <!--								<span class="me-1">Read More</span>-->
        <!--								<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--									<path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>-->
        <!--									<path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>-->
        <!--								</svg>-->
        <!--							</a>-->
        <!--						</div>-->
        <!--					</div>-->
        <!--				</div>-->
        <!--			</div>-->
        <!--		</div>-->
        <!--	</div>-->

        <!--</div>	-->
        <!--	<div id="destination-controls">-->
        <!--		<span class="prev" data-controls="prev">-->
        <!--			<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--				<path d="M0.521729 8.70303C-0.144938 8.31813 -0.144939 7.35588 0.521728 6.97098L12.2446 0.202802C12.9112 -0.182099 13.7446 0.299026 13.7446 1.06883L13.7446 14.6052C13.7446 15.375 12.9112 15.8561 12.2446 15.4712L0.521729 8.70303Z" fill="#649EF7"/>-->
        <!--			</svg>-->
        <!--		</span>-->
        <!--		<span class="next" data-controls="next">-->
        <!--			<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--				<path d="M13.4783 8.70303C14.1449 8.31813 14.1449 7.35588 13.4783 6.97098L1.75545 0.202802C1.08878 -0.182099 0.255445 0.299026 0.255445 1.06883L0.255445 14.6052C0.255445 15.375 1.08878 15.8561 1.75544 15.4712L13.4783 8.70303Z" fill="#649EF7"/>-->
        <!--			</svg>-->
        <!--		</span>-->
        <!--	</div>-->
        <!--		<script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script> <div class="elfsight-app-bbbef70f-dd45-4bf0-9898-71a8bcb0d620" data-elfsight-app-lazy></div>
		</div>-->
    </div>

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
                <!--<div class="col-3 d-none">-->
                <!--	<div class="wide-slider-testimonial-wrap-one">-->
                <!--		<div class="wide-slider-testimonial-one">-->
                <!--			<div class="h-100">-->
                <!--				<div class="d-flex justify-content-end align-items-center" id="using-three">-->
                <!--					<div class="mx-2 blue-bg-add">-->
                <!--						<img src="images/testi-three.jfif" alt="" class="slider-added-imges">-->
                <!--					</div>-->
                <!--					<div class="no-display-half-part d-none">-->
                <!--						<p>Kerry's expertise and exceptional reasoning have proven invaluable within our high-performance team. I enthusiastically recommend his services to anyone seeking to enhance their health and performance.</p>-->
                <!--						<h5 class="mb-0">Cohen Crispin</h5>-->
                <!--						<p class="position mb-0">Strength and Conditioning Coach (ASCA EL3)-->
                <!--							Bachelor of sport & exercise science</p>-->
                <!--					</div>-->
                <!--				</div>-->
                <!--			</div>-->
                <!--			<div class="h-100">-->
                <!--				<div class="d-flex justify-content-end align-items-center" id="using-one">-->
                <!--					<div class="mx-2 blue-bg-add">-->
                <!--						<img src="images/testi-one.jfif" alt="" class="slider-added-imges">-->
                <!--					</div>-->
                <!--					<div class="no-display-half-part d-none">-->
                <!--						<p>Kerry's expertise and exceptional reasoning have proven invaluable within our high-performance team. I enthusiastically recommend his services to anyone seeking to enhance their health and performance.</p>-->
                <!--						<h5 class="mb-0">Cohen Crispin</h5>-->
                <!--						<p class="position mb-0">Strength and Conditioning Coach (ASCA EL3)-->
                <!--							Bachelor of sport & exercise science</p>-->
                <!--					</div>-->
                <!--				</div>-->
                <!--			</div>-->
                <!--			<div class="h-100">-->
                <!--				<div class="d-flex justify-content-end align-items-center" id="using-two">-->
                <!--					<div class="mx-2 blue-bg-add">-->
                <!--						<img src="images/testi-two.jfif" alt="" class="slider-added-imges">-->
                <!--					</div>-->
                <!--					<div class="no-display-half-part d-none">-->
                <!--						<p>Kerry's expertise and exceptional reasoning have proven invaluable within our high-performance team. I enthusiastically recommend his services to anyone seeking to enhance their health and performance.</p>-->
                <!--						<h5 class="mb-0">Cohen Crispin</h5>-->
                <!--						<p class="position mb-0">Strength and Conditioning Coach (ASCA EL3)-->
                <!--							Bachelor of sport & exercise science</p>-->
                <!--					</div>-->
                <!--				</div>-->
                <!--			</div>-->
                <!--		</div>-->
                <!--	</div>-->
                <!--</div>-->
                <div class="col-xl-6 col-lg-8 col-md-10 px-md-0 px-4 mx-auto position-relative">
                    <div class="wide-slider-testimonial-wrap-two">
                        <div class="wide-slider-testimonial-two">
                            <!--<div class="">-->
                            <!--	<div class="d-flex flex-sm-row flex-column align-items-center" id="using-one">-->
                            <!--		<div class="pe-xxl-5 pe-sm-3 mb-sm-0 mb-3 border-custom-left">-->
                            <!--			<img src="images/testi-one.jfif" alt="" class="slider-added-imges" style="">-->
                            <!--		</div>-->
                            <!--		<div class="no-display-half-part">-->
                            <!--			<div class="quote-using">-->
                            <!--				<i class="fa-solid fa-quote-left"></i>-->
                            <!--			</div>-->
                            <!--			<p>Kerry's expertise and exceptional reasoning have proven invaluable within our high-performance team. I enthusiastically recommend his services to anyone seeking to enhance their health and performance.</p>-->
                            <!--			<h5 class="mb-0">Cohen Crispin</h5>-->
                            <!--			<p class="position mb-0">Strength and Conditioning Coach (ASCA EL3)-->
                            <!--				Bachelor of sport & exercise science</p>-->
                            <!--		</div>-->
                            <!--	</div>-->
                            <!--</div>-->
                            <!--<div class="">-->
                            <!--	<div class="d-flex flex-sm-row flex-column align-items-center" id="using-two">-->
                            <!--		<div class="pe-xxl-5 pe-sm-3 mb-sm-0 mb-3 border-custom-left">-->
                            <!--			<img src="images/testi-two.jfif" alt="" class="slider-added-imges" style="">-->
                            <!--		</div>-->
                            <!--		<div class="no-display-half-part">-->
                            <!--			<div class="quote-using">-->
                            <!--				<i class="fa-solid fa-quote-left"></i>-->
                            <!--			</div>-->
                            <!--			<p>Working with Kerry has been amazing. His wealth of nutrition knowledge has given us so many options with easy to follow recommendations. Being able to adjust nutrition easily to best suit our son’s training schedule for performance, recovery and physical development has been great.</p>-->
                            <!--			<h5 class="mb-0">Rhondda Dunne</h5>-->
                            <!--			<p class="position mb-0">Mother of Olympian Breaking Athlete</p>-->
                            <!--		</div>-->
                            <!--	</div>-->
                            <!--</div>-->
                            <!--<div class="">-->
                            <!--	<div class="d-flex flex-sm-row flex-column align-items-center" id="using-three">-->
                            <!--		<div class="pe-xxl-5 pe-sm-3 mb-sm-0 mb-3 border-custom-left">-->
                            <!--			<img src="images/testi-three.jfif" alt="" class="slider-added-imges" style="">-->
                            <!--		</div>-->
                            <!--		<div class="no-display-half-part">-->
                            <!--			<div class="quote-using">-->
                            <!--				<i class="fa-solid fa-quote-left"></i>-->
                            <!--			</div>-->
                            <!--			<p class="mb-1">Kerry has been instrumental in helping me to achieve gains during my off season. I have seen huge strides with how I feel, perform and recover both on the volleyball court and in the weight room.</p>-->
                            <!--			<p class="">Kerry’s wealth of knowledge paired with his friendly and accessible nature has made it an absolute pleasure working with him. I would highly recommend consulting with Kerry for anyone looking to create sustained performance results.</p>-->
                            <!--			<h5 class="mb-0">Enis Besirevic</h5>-->
                            <!--			<p class="position mb-0">AUS Senior National Volleyball Athlete</p>-->
                            <!--		</div>-->
                            <!--	</div>-->
                            <!--</div>-->
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
                <!--<div class="col-3  d-none">-->
                <!--	<div class="wide-slider-testimonial-wrap-three">-->
                <!--		<div class="wide-slider-testimonial-three">-->
                <!--			<div class="h-100">-->
                <!--				<div class="d-flex align-items-center" id="using-two">-->
                <!--					<div class="ms-3 blue-bg-add">-->
                <!--						<img src="images/testi-two.jfif" alt="" class="slider-added-imges">-->
                <!--					</div>-->
                <!--					<div class="no-display-half-part d-none">-->
                <!--						<p>Kerry's expertise and exceptional reasoning have proven invaluable within our high-performance team. I enthusiastically recommend his services to anyone seeking to enhance their health and performance.</p>-->
                <!--						<h5 class="mb-0">Cohen Crispin</h5>-->
                <!--						<p class="position mb-0">Strength and Conditioning Coach (ASCA EL3)-->
                <!--							Bachelor of sport & exercise science</p>-->
                <!--					</div>-->
                <!--				</div>-->
                <!--			</div>-->
                <!--			<div class="h-100">-->
                <!--				<div class="d-flex align-items-center" id="using-three">-->
                <!--					<div class="ms-3 blue-bg-add">-->
                <!--						<img src="images/testi-three.jfif" alt="" class="slider-added-imges">-->
                <!--					</div>-->
                <!--					<div class="no-display-half-part d-none">-->
                <!--						<p>Kerry's expertise and exceptional reasoning have proven invaluable within our high-performance team. I enthusiastically recommend his services to anyone seeking to enhance their health and performance.</p>-->
                <!--						<h5 class="mb-0">Cohen Crispin</h5>-->
                <!--						<p class="position mb-0">Strength and Conditioning Coach (ASCA EL3)-->
                <!--							Bachelor of sport & exercise science</p>-->
                <!--					</div>-->
                <!--				</div>-->
                <!--			</div>-->
                <!--			<div class="h-100">-->
                <!--				<div class="d-flex align-items-center" id="using-one">-->
                <!--					<div class="ms-3 blue-bg-add">-->
                <!--						<img src="images/testi-one.jfif" alt="" class="slider-added-imges">-->
                <!--					</div>-->
                <!--					<div class="no-display-half-part d-none">-->
                <!--						<p>Kerry's expertise and exceptional reasoning have proven invaluable within our high-performance team. I enthusiastically recommend his services to anyone seeking to enhance their health and performance.</p>-->
                <!--						<h5 class="mb-0">Cohen Crispin</h5>-->
                <!--						<p class="position mb-0">Strength and Conditioning Coach (ASCA EL3)-->
                <!--							Bachelor of sport & exercise science</p>-->
                <!--					</div>-->
                <!--				</div>-->
                <!--			</div>-->
                <!--		</div>-->
                <!--	</div>-->
                <!--</div>-->
                <div class="col-12 text-center mt-5" style="text-align: center !important;">

                </div>
            </div>
        </div>
    </section>

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
            <!--<div class="client-slider-wrap mb-3 mt-5">-->
            <!--	<div class="client-slider-marque  d-flex justify-content-between my-3">-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-1.webp" alt="client" class="img-fluid mb-3 " width="250" />-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-2.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-3.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-4.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-5.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-1.webp" alt="client" class="img-fluid mb-3 " width="250" />-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-2.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-3.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-4.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-5.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-1.webp" alt="client" class="img-fluid mb-3 " width="250" />-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-2.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-3.web" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-4.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-5.webp" alt="client" class="img-fluid " width="250"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-3 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-1.webp" alt="client" class="img-fluid mb-3 " width="250" />-->
            <!--				</div>-->
            <!--		</div>-->

            <!--	</div>-->
            <!--	<div class="client-slider-marque-rev  d-flex justify-content-center  my-3">-->

            <!--		<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-6.webp" alt="client" class="img-fluid " height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->

            <!--		<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-8.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-9.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-10.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-11.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-12.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-9.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-10.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-11.webp" alt="client" class="img-fluid" height="150" />-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-12.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-6.webp" alt="client" class="img-fluid " height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->

            <!--		<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-8.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-9.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-10.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-11.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--		<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-8.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-9.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-10.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-11.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->
            <!--			<div class="col-md-2 text-center mx-1 mb-3">-->
            <!--			<div class="client-wrap">-->
            <!--				<img src="images/client-12.webp" alt="client" class="img-fluid" height="150"/>-->
            <!--				</div>-->
            <!--		</div>-->

            <!--	</div>-->

            <!--</div>-->
        </div>
        <article class="wrapper">
            <div class="marquee-main">
                <!--<div class="marquee">-->
                <!--    <div class="marquee__group">-->
                <!--        <div>-->
                <!--            <span><img src="images/client-1.webp" alt="client" class="img-fluid mb-3 " width="250" /></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-2.webp" alt="client" class="img-fluid " width="250"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-3.webp" alt="client" class="img-fluid " width="250"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-4.webp" alt="client" class="img-fluid " width="250"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-5.webp" alt="client" class="img-fluid " width="250"/></span>-->
                <!--        </div>-->
                <!--<div>-->
                <!--    <span>6</span>-->
                <!--</div>-->
                <!--<div>-->
                <!--    <span>7</span>-->
                <!--</div>-->
                <!--<div>-->
                <!--    <span>8</span>-->
                <!--</div>-->
                <!--    </div>-->

                <!--    <div aria-hidden="true" class="marquee__group">-->
                <!--        <div>-->
                <!--            <span><img src="images/client-1.webp" alt="client" class="img-fluid mb-3 " width="250" /></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-2.webp" alt="client" class="img-fluid " width="250"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-3.webp" alt="client" class="img-fluid " width="250"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-4.webp" alt="client" class="img-fluid " width="250"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-5.webp" alt="client" class="img-fluid " width="250"/></span>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="marquee marquee--reverse">-->
                <!--    <div class="marquee__group">-->
                <!--        <div>-->
                <!--            <span><img src="images/client-6.webp" alt="client" class="img-fluid " height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-8.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-9.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-10.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-11.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-12.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--<div>-->
                <!--    <span>7</span>-->
                <!--</div>-->
                <!--<div>-->
                <!--    <span>8</span>-->
                <!--</div>-->
                <!--    </div>-->

                <!--    <div aria-hidden="true" class="marquee__group">-->
                <!--        <div>-->
                <!--            <span><img src="images/client-6.webp" alt="client" class="img-fluid " height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-8.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-9.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-10.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-11.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--        <div>-->
                <!--            <span><img src="images/client-12.webp" alt="client" class="img-fluid" height="150"/></span>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
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

    <div class="section section-contact pb-0 pt-0" id="contact">
        <div class="container">
            <div class="contact-wrap position-relative">
                <h2 class="heading mb-5  d-flex align-items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-nowrap">Have more questions<br>about to getting started?</div>
                    <span class="border-heading"></span>
                </h2>
                <div class="row justify-content-between align-items-start">

                    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                        <p>Let us know your concerns and we will get back to you with</p>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" placeholder="Your name">
                            <label for="floatingInput">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" placeholder="Mobile number">
                            <label for="floatingInput">Mobile number</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" rows="3"></textarea>
                            <label for="floatingTextarea">What is your question?</label>
                        </div>


                        <!-- <div class="col-12">
							<input type="submit" value="Send Message" class="btn btn-primary">
						</div> -->
                        <p class="my-4" data-aos="fade-up" data-aos-delay="200"><a href="#" class="btn btn-primary">Submit
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white" />
                                    <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a></p>
                    </div>
                </div>
                <img src="{!! frontAssets('images/contact.png') !!}" alt="Image" class="img-fluid img-contact">

            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
@endpush