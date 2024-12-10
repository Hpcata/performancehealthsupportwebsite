@extends(frontView('layouts.app'))

@section('title', 'Home')

@section('content')
@php
    $showHeader = !empty($user->front_logo) && 
                  !empty($user->front_title) && 
                  !empty($user->front_description) && 
                  !empty($user->about_us_image);
@endphp

    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->type == 'section-1' && $section->enabled == 1)
                <div class="section nutrition-page-banner" style="background-image: url(front/images/banner-img.jpg);">
                    <div class="container">
                        <div class="text-center">
                            <h1 class="text-white">{{ $section->title }}</h1>
                        </div>
                        <div class="text-center banner-text mt-auto pt-5">
                            {!! $section->content !!}
                            <a href="#" class="btn btn-primary">
                                <span class="me-1">Take Free Test</span>
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>
                                    <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if($section->type == 'section-2' && $section->enabled == 1)
                <div class="section power-peak-row bg-white">
                    <div class="container">
                        <div class="row align-items-center g-4">
                            <div class="col-md-6">
                                <div class="">  
                                    <figure class="m-0">
                                        <img class="w-100" src="{!! frontAssets('images/about-new.png') !!}" alt="">
                                    </figure>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="about-content">
                                    <h2>{{ $section->title }}</h2>
                                    <p>{!! $section->content !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach 
    @endif

    <div class="section find-spot-row" style="background-image: url(images/female-athlete.jpg);">
        <div class="container">
            <div class="h1 text-center text-white">Find Your Sport</div>
            <div class="spot-search">
                <form action="#">
                    <div class="row">
                        <div class="col-lg-5 col-md-4">
                            <div class="form-select-box">
                                <select class="form-control">
                                    <option>Select Your Sport</option>
                                    <option>Sport Name 1</option>
                                    <option>Sport Name 2</option>
                                    <option>Sport Name 3</option>
                                    <option>Sport Name 4</option>
                                    <option>Sport Name 5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-4 select-middle">
                            <div class="form-select-box">
                                <select class="form-control">
                                    <option>Choose Your State</option>
                                    <option>State Location 1</option>
                                    <option>State Location 2</option>
                                    <option>State Location 3</option>
                                    <option>State Location 4</option>
                                    <option>State Location 5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;">
                                        <path d="M23.6,22l-4.4-4.4c1.5-1.8,2.4-4.2,2.4-6.7c0-6-4.8-10.8-10.8-10.8C4.8,0,0,4.8,0,10.8c0,6,4.8,10.8,10.8,10.8c2.5,0,4.9-0.9,6.7-2.4l4.4,4.4c0.2,0.2,0.5,0.4,0.8,0.4c0.3,0,0.6-0.1,0.8-0.4C24.1,23.2,24.1,22.4,23.6,22z M2.4,10.8c0-4.6,3.8-8.4,8.4-8.4c4.6,0,8.4,3.8,8.4,8.4c0,2.3-0.9,4.4-2.4,5.9c0,0,0,0,0,0c0,0,0,0,0,0c-1.5,1.5-3.6,2.4-5.9,2.4C6.2,19.2,2.4,15.4,2.4,10.8z"></path>
                                    </svg>
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <h2 class="heading mb-5 d-flex">
                <div class="mt-2 h2">Plan's Build by a Sports Nutrition Expert, Kerry O’Bryan</div>
                <span class="border-heading"></span>
            </h2>
            <div class="spot-plan-row">
                <div class="row g-4">
                @foreach($plans as $plan)
                    <div class="col-lg-3 col-md-6">
                        <div class="spot-plan-box">
                            <h5>{{ $plan->name }}</h5>
                            <div class="spot-plan-img-box">
                                <figure>
                                    <img src="{!! frontAssets('images/about-new.png') !!}" alt="">
                                </figure>
                                <div class="spot-plan-info-box">
                                    <h6>Bundle 1:</h6>
                                    <ul>
                                        <li>High Training Day Plan</li>
                                        <li>Low Training Day Plan</li>
                                        <li>Comp Day Plan</li>
                                    </ul>
                                    <p>Bonus: Life in the day of Pro Plan</p>
                                    <h6>Bundle 2:</h6>
                                    <ul>
                                        <li>High Training Day Plan </li>
                                        <li>Low Training Day Plan</li>
                                        <li>Injury/ Post Surgery Plan</li>
                                    </ul>
                                    <p>Bonus: Life in the day of Pro Plan</p>
                                </div>
                            </div>
                            <!-- <a href="#" class="btn btn-primary">Purchase Now: $200</a> -->
                            <div data-aos="fade-up" class="aos-init aos-animate">
                                <a href="{{ route('front.plans.details', ['id' => $plan->id]) }}" class="btn btn-primary mt-2 w-100">
                                    <span class="me-1">View Details </span>
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">

                                        <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>

                                        <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>

                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    
    <div class="section section-3 pt-3 pb-0" data-aos="fade-up" data-aos-delay="100">
       
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