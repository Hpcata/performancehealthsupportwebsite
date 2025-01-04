@extends(frontView('layouts.app'))

@section('title', '$page->title')

@section('content')
<style>
    #thankYouModal .modal-content {
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    #thankYouModal .icon-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #thankYouModal .modal-body {
        padding: 2rem;
    }
    #thankYouModal .btn {
        border-radius: 25px;
        font-weight: 600;
    }

</style>
@php
    $showHeader = !empty($user->front_logo) && 
                  !empty($user->front_title) && 
                  !empty($user->front_description) && 
                  !empty($user->about_us_image);

    if($isAuthenticated){
        $user = Auth::user();
        $planIds = DB::table('payments')->where('email', $user->email)->where('status', 'succeeded')->pluck('plan_id')->toArray();
    }else {
        $planIds = [];
    }
@endphp

    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->type == 'section-1' && $section->enabled == 1)
                <div class="section nutrition-page-banner" style="background-image: url(private/public/front/images/banner-img.jpg);">
                    <div class="container">
                        <div class="text-center">
                            <h1 class="text-white">{{ $section->title }}</h1>
                        </div>
                        <div class="text-center banner-text mt-auto pt-5">
                            {!! $section->content !!}
                            <a href="#" class="btn btn-primary" id="takeFreeTest">
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

    <div class="section find-spot-row" style="background-image: url(private/public/front/images/female-athlete.jpg);">
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
                                @if($isAuthenticated && in_array($plan->id, $planIds))
                                    <?php
                                        $userPlan = \App\Models\UserPlan::where('user_id', Auth::user()->id)->where('plan_id', $plan->id)->where('status', 'active')->first();
                                        $isPlanCreated = $userPlan ? true : false;
                                    ?>
                                    <a href="{{ route('front.plans.details', ['id' => $plan->id]) }}" class="btn btn-primary mt-2 w-100 @if(!$isPlanCreated) disabled @endif" @if(!$isPlanCreated) style="pointer-events: none; opacity: 0.5;" @endif>
                                        <span class="me-1">View Details </span>
                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">

                                            <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>

                                            <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>

                                        </svg>
                                    </a>
                                    @elseif(!$isAuthenticated && in_array($plan->id, $planIds))
                                        <div data-aos="fade-up" class="aos-init aos-animate">
                                            <button type="button" class="btn btn-primary mt-2 w-100" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#loginModal">
                                                View Details
                                            </button>
                                        </div>
                                    @else
                                        <div data-aos="fade-up" class="aos-init aos-animate">
                                            <button type="button" class="btn btn-primary mt-2 w-100 purchase-now-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#purchaseModal"
                                                    data-plan-id="{{ $plan->id }}" 
                                                    data-plan-name="{{ $plan->name }}" 
                                                    data-plan-price="{{ $plan->price }}">
                                                Purchase Now: $ {{ $plan->price }}
                                            </button>
                                        </div>
                                    @endif
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

    <!-- Purchase Modal -->
    <!-- Sign-Up Modal (Purchase Modal) -->
    <div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseModalLabel">Purchase Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- User info form -->
                    <form id="payment-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" required>
                        </div>

                        <!-- New Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Stripe Payment Card Section -->
                        <h6 class="mb-3">Payment Details</h6>
                        <div class="mb-3">
                            <label for="card-element" class="form-label">Credit or Debit Card</label>
                            <div id="card-element" class="border rounded p-3" style="background-color: #f9f9f9;">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="button" id="view-sample-plan" class="btn btn-primary w-100 mt-3">
                            View Sample Plan
                        </button>
                        <button type="submit" id="submit" class="btn btn-primary w-100 mt-3">
                            Purchase
                        </button>
                    </form>

                    <!-- Sign In Link -->
                    <div class="mt-3 text-center">
                        <small>Already have an account? <a href="#" id="show-login-modal">Sign In</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sign-In Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div id="login-error" class="text-danger"></div> <!-- This will display the error message -->
                    <!-- Sign In Form -->
                    <form id="login-form">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="login-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="login-password" required>
                        </div>

                        <!-- Sign In Button -->
                        <button type="submit" id="login-submit" class="btn btn-primary w-100 mt-3">
                            Sign In
                        </button>
                    </form>

                    <!-- Sign Up Link -->
                    <div class="mt-3 text-center">
                        <small>Don't have an account? <a href="#" id="show-signup-modal">Sign Up</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="testLoginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div id="login-error" class="text-danger"></div> <!-- This will display the error message -->
                    <!-- Sign In Form -->
                    <form id="test-login-form">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="test-login-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="test-login-password" required>
                        </div>

                        <!-- Sign In Button -->
                        <button type="submit" id="login-submit" class="btn btn-primary w-100 mt-3">
                            Sign In
                        </button>
                    </form>

                    <!-- Sign Up Link -->
                    <div class="mt-3 text-center">
                        <small>Don't have an account? <a href="#" class="register-link">Sign Up</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseModalLabel">Sign Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- User info form -->
                    <form id="register-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="register-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="register-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" id="register-phone" required>
                        </div>

                        <!-- New Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="register-password" required>
                        </div>

                        <button type="submit" id="submit" class="btn btn-primary w-100 mt-3">
                            Sign Up
                        </button>
                    </form>

                    <!-- Sign In Link -->
                    <div class="mt-3 text-center">
                        <small>Already have an account? <a href="#" class="login-link">Sign In</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Thank You Modal -->
    <div class="modal show" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="icon-container mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="modal-title mb-2" id="thankYouModalLabel">Thank You!</h2>
                    <p class="mb-2" id="thankYouMessage">Your payment was successful.</p>
                    <a href="#" id="planUrlLink" class="btn btn-primary mt-2">Order Your Personalised Plan</a>

                    <!-- <button type="button" class="btn btn-primary w-50 mt-3" data-bs-dismiss="modal">Close</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="samplePlanModal" tabindex="-1" aria-labelledby="samplePlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="samplePlanModalLabel">Sample Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="samplePlanModalBody">
                    <!-- Plan details will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="TakeTestModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="TakeTestModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-start pe-5">
                <h4 class="modal-title mb-1" id="testModalLabel">Nutrition Knowledge Questions</h4>
                <p>Answers to the 4 questions below will assist in providing targeted information. </p>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="steps-list mb-4">
                    <div class="wizard-inner">
                        <a class="tab-steps active" href="#"><span class="round-tab">1</span> <i>Step 1</i></a>
                        <a class="tab-steps" href="#"><span class="round-tab">2</span> <i>Step 2</i></a>
                        <a class="tab-steps" href="#"><span class="round-tab">3</span> <i>Step 3</i></a>
                        <a class="tab-steps" href="#"><span class="round-tab">4</span> <i>Step 4</i></a>
                    </div>
                </div>

                <div class="tab-main-box">
                    <div class="step-tab-box" id="div1" style="display: block;">
                        <div class="card">
                            <div class="p-3 card-header bg-white">
                                <h5 class="m-0">1. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">carbohydrate</strong>? (click on <strong class="text-primary">one</strong> box per food)</h5>
                                <input type="hidden" name="questions[foods_carbohydrate]" value="Do you think these foods are high or low in carbohydrate?" />
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="text-center">High</th>
                                                <th class="text-center">Low</th>
                                                <th class="text-center">Unsure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Chicken</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][chicken]" value="High" id="Chicken-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][chicken]" value="Low" id="Chicken-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][chicken]" value="Unsure" id="Chicken-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Baked beans</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][baked_beans]" value="High" id="Bakedbeans-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][baked_beans]" value="Low" id="Bakedbeans-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][baked_beans]" value="Unsure" id="Bakedbeans-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Grain bread</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][grain_bread]" value="High" id="GrainBread-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][grain_bread]" value="Low" id="GrainBread-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][grain_bread]" value="Unsure" id="GrainBread-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Avocado</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][avocado]" value="High" id="Avocado-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][avocado]" value="Low" id="Avocado-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][avocado]" value="Unsure" id="Avocado-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Weet-bix</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][weet_bix]" value="High" id="Weet-bix-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][weet_bix]" value="Low" id="Weet-bix-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][weet_bix]" value="Unsure" id="Weet-bix-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Fruit yoghurt</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][fruit_yoghurt]" value="High" id="FruitYoghurt-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][fruit_yoghurt]" value="Low" id="FruitYoghurt-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][fruit_yoghurt]" value="Unsure" id="FruitYoghurt-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Crumpets</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][crumpets]" value="High" id="Crumpets-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][crumpets]" value="Low" id="Crumpets-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][crumpets]" value="Unsure" id="Crumpets-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Cream</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][cream]" value="High" id="Cream-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][cream]" value="Low" id="Cream-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_carbohydrate][cream]" value="Unsure" id="Cream-3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="2">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box" id="div2" style="display: block;">
                        <div class="card">
                            <div class="p-3 card-header bg-white">
                                <h5 class="m-0">2. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">protein</strong>? (click on <strong class="text-primary">one</strong> box per food)</h5>
                                <input type="hidden" name="questions[foods_protein]" value="Do you think these foods are high or low in protein?" />
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="text-center">High</th>
                                                <th class="text-center">Low</th>
                                                <th class="text-center">Unsure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Salmon</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][salmon]" value="High" id="Salmon-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][salmon]" value="Low" id="Salmon-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][salmon]" value="Unsure" id="Salmon-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Baked beans</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][baked_beans]" value="High" id="Bakedbeans-11"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][baked_beans]" value="Low" id="Bakedbeans-12"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][baked_beans]" value="Unsure" id="Bakedbeans-13"></td>
                                            </tr>
                                            <tr>
                                                <td>Fruit</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][fruit]" value="High" id="Fruit-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][fruit]" value="Low" id="Fruit-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][fruit]" value="Unsure" id="Fruit-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Hummus</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][hummus]" value="High" id="Hummus-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][hummus]" value="Low" id="Hummus-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][hummus]" value="Unsure" id="Hummus-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Cornflakes cereal</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][cornflakes_cereal]" value="High" id="CornflakesCereal-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][cornflakes_cereal]" value="Low" id="CornflakesCereal-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][cornflakes_cereal]" value="Unsure" id="CornflakesCereal-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Almonds</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][almonds]" value="High" id="Almonds-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][almonds]" value="Low" id="Almonds-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][almonds]" value="Unsure" id="Almonds-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Flavoured milk</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][flavoured_milk]" value="High" id="FlavouredMilk-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][flavoured_milk]" value="Low" id="FlavouredMilk-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][flavoured_milk]" value="Unsure" id="FlavouredMilk-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Ice cream</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][ice_cream]" value="High" id="IceCream-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][ice_cream]" value="Low" id="IceCream-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][ice_cream]" value="Unsure" id="IceCream-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Almond/oat milk</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][almond_oat_milk]" value="High" id="Almond-oat-milk-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][almond_oat_milk]" value="Low" id="Almond-oat-milk-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_protein][almond_oat_milk]" value="Unsure" id="Almond-oat-milk-3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="1" >Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="3">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box" id="div3" style="display: block;">
                        <div class="card">
                            <div class="p-3 card-header bg-white">
                                <h5 class="m-0">3. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">fat</strong>? (click on <strong class="text-primary">one</strong> box per food)</h5>
                                <input type="hidden" name="questions[foods_fat]" value="Do you think these foods are high or low in fat?" />
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="text-center">High</th>
                                                <th class="text-center">Low</th>
                                                <th class="text-center">Unsure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Avocado</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][avocado]" value="High" id="Avocado-11"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][avocado]" value="Low" id="Avocado-12"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][avocado]" value="Unsure" id="Avocado-13"></td>
                                            </tr>
                                            <tr>
                                                <td>Baked beans</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][backed_beans]" value="High" id="BakedBeans-21"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][backed_beans]" value="Low" id="BakedBeans-22"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][backed_beans]" value="Unsure" id="BakedBeans-23"></td>
                                            </tr>
                                            <tr>
                                                <td>Cottage cheese</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][cottage_cheese]" value="High" id="CottageCheese-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][cottage_cheese]" value="Low" id="CottageCheese-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][cottage_cheese]" value="Unsure" id="CottageCheese-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Peanut butter</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][peanut_butter]" value="High" id="PeanutButter-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][peanut_butter]" value="Low" id="PeanutButter-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][peanut_butter]" value="Unsure" id="PeanutButter-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Crumpets</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][crumpets]" value="High" id="Crumpets-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][crumpets]" value="Low" id="Crumpets-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][crumpets]" value="Unsure" id="Crumpets-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Cheddar/Tatsy cheese</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][cheddar_tatsy_cheese]" value="High" id="CheddarTatsyCheese-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][cheddar_tatsy_cheese]" value="Low" id="CheddarTatsyCheese-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_fat][cheddar_tatsy_cheese]" value="Unsure" id="CheddarTatsyCheese-3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="2" >Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="4">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box" id="div4" style="display: block;">
                        <div class="card">
                            <div class="p-3 card-header bg-white">
                                <h5 class="m-0">4. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">healthy fats</strong>? (click on <strong class="text-primary">one</strong> box per food)</h5>
                                <input type="hidden" name="questions[foods_healthy_fat]" value="Do you think these foods are high or low in healthy fat?" />
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="text-center">High</th>
                                                <th class="text-center">Low</th>
                                                <th class="text-center">Unsure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Butter</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][butter]" value="High" id="Butter-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][butter]" value="Low" id="Butter-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][butter]" value="Unsure" id="Butter-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Extra virgin olive oil</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][extra_virgin_olive_oil]" value="High" id="OliveOil-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][extra_virgin_olive_oil]" value="Low" id="OliveOil-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][extra_virgin_olive_oil]" value="Unsure" id="OliveOil-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Whole milk</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][whole_milk]" value="High" id="WholeMilk-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][whole_milk]" value="Low" id="WholeMilk-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][whole_milk]" value="Unsure" id="WholeMilk-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Potato crisps</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][potato_crisps]" value="High" id="PotatoCrisps-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][potato_crisps]" value="Low" id="PotatoCrisps-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][potato_crisps]" value="Unsure" id="PotatoCrisps-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Salmon</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][salmon]" value="High" id="Salmon-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][salmon]" value="Low" id="Salmon-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][salmon]" value="Unsure" id="Salmon-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Dark chocolate</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][dark_chocolate]" value="High" id="DarkChocolate-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][dark_chocolate]" value="Low" id="DarkChocolate-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][dark_chocolate]" value="Unsure" id="DarkChocolate-3"></td>
                                            </tr>
                                            <tr>
                                                <td>Macadamia nuts</td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][macadamia_nuts]" value="High" id="MacadamiaNuts-1"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][macadamia_nuts]" value="Low" id="MacadamiaNuts-2"></td>
                                                <td class="text-center"><input class="form-check-input" type="radio" name="ans[foods_healthy_fat][macadamia_nuts]" value="Unsure" id="MacadamiaNuts-3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="3" >Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto submit-free-test">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    
    $(document).ready(function () {
        $('#takeFreeTest').on('click', function () {
            $('#TakeTestModel').modal('show');
        });
        // Initialize all the necessary variables
        const stepCircles = document.querySelectorAll('.tab-steps');
        const stepTabs = document.querySelectorAll(".step-tab-box");
        const showStepButtons = document.querySelectorAll('.showStepTab');
        const submitButton = document.querySelector(".submit-free-test"); // Submit button for final submission
        const currentModal = $("#TakeTestModel"); // Current modal for test steps
        const registerModal = $("#registerModal"); // Registration/Login modal
        const loginModal = $("#testLoginModal"); // Registration/Login modal
        const registerForm = $("#register-form"); // Registration form
        const loginForm = $("#test-login-form"); // Login form
        const loginLink = $(".login-link"); // Login link in Register modal
        const registerLink = $(".register-link"); // Register link in Login modal
        const stepsData = {}; // Object to store all steps data

        let currentStep = 0; // Track the active step index

        // Initially, show only the first step-tab-box
        stepTabs.forEach((tab, index) => {
            tab.style.display = index === 0 ? "block" : "none";
        });

        // Function to validate fields in the current step
        function validateStep(stepIndex) {
            const stepTab = stepTabs[stepIndex]; // Get current step tab
            const inputs = stepTab.querySelectorAll('input, textarea, select');
            let isValid = true;
            const errorMessage = "* Please select an answer for this question.";

            // Loop through each input to validate
            inputs.forEach(input => {
                // Reset border color before validation
                input.style.border = "";

                if (input.type === "radio" || input.type === "checkbox") {
                    // Validation for radio/checkbox inputs
                    if (input.name && !document.querySelector(`input[name="${input.name}"]:checked`)) {
                        isValid = false;
                        // Apply red border to all unchecked inputs in the group
                        document.querySelectorAll(`input[name="${input.name}"]`).forEach(el => {
                            el.style.border = "1px solid red";
                        });
                    } else {
                        // Reset border for valid radio/checkbox group
                        document.querySelectorAll(`input[name="${input.name}"]`).forEach(el => {
                            el.style.border = "";
                        });
                    }
                } else if (input.value.trim() === "") {
                    // Validation for text fields, textarea, and select
                    input.style.border = "1px solid red";
                    isValid = false;
                } else {
                    // Reset border for valid inputs
                    input.style.border = "";
                }
            });

            // Display error message if validation fails
            const cardBody = stepTab.querySelector('.card-body');
            let errorMessageSpan = cardBody.querySelector('.general-error-message');

            if (!errorMessageSpan) {
                // Create error message span if not present
                errorMessageSpan = document.createElement("span");
                errorMessageSpan.className = "text-danger general-error-message m-3";
                cardBody.appendChild(errorMessageSpan);
            }

            errorMessageSpan.textContent = errorMessage;
            errorMessageSpan.style.display = isValid ? "none" : "block";

            return isValid;
        }

        // Function to collect data for the current step
        function collectStepData(currentStep) {
            const form = document.querySelector(`#div${currentStep}`);
            const stepData = {};
            const questionInput = form.querySelector("input[type='hidden'][name^='questions']");
            const questionText = questionInput ? questionInput.value : "";

            if (questionText) {
                stepData[questionText] = {};
            }

            const rows = form.querySelectorAll("tbody tr");
            rows.forEach(row => {
                const foodName = row.querySelector("td:first-child").textContent.trim();
                const selectedAnswer = row.querySelector("input[type='radio']:checked");

                if (foodName) {
                    stepData[questionText][foodName] = selectedAnswer ? selectedAnswer.value : "No answer selected";
                }
            });

            return stepData;
        }

        // Event listener for step navigation buttons (previous/next steps)
        showStepButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetStep = parseInt(button.getAttribute('target'), 10) - 1;

                // If moving forward, validate the current step
                if (targetStep > currentStep && !validateStep(currentStep)) {
                    return; // Stop progression if validation fails
                }

                // Update step tabs visibility and active step
                stepCircles.forEach((step, index) => {
                    step.classList.toggle('active', index <= targetStep);
                });

                stepTabs.forEach((tab, index) => {
                    tab.style.display = index === targetStep ? "block" : "none";
                });

                currentStep = targetStep;
            });
        });

        // Event listener for the Submit button (final submit after all steps)
        submitButton.addEventListener("click", () => {
            alert("Submit button clicked");
            // Validate all steps before final submission
            if (!validateStep(currentStep)) {
                return; // Stop submission if validation fails
            }

            // Collect step data for all steps and store them in local storage
            for (let step = 1; step <= 4; step++) {
                const stepData = collectStepData(step);
                localStorage.setItem(`step-${step}-data`, JSON.stringify(stepData));
                Object.assign(stepsData, stepData);  // Merge collected data into stepsData object
            }

            // Save all the collected test data to localStorage
            localStorage.setItem("testStepsData", JSON.stringify(stepsData));

            // Close the current modal and open the registration/login modal
            if (currentModal) {
                currentModal.modal('hide'); // Close the current modal using jQuery
            }

            if (registerModal) {
                registerModal.modal('show'); // Open the register modal using jQuery
            }
        });

        // Registration form submit handler
        registerForm.submit(function (event) {
            event.preventDefault();

            // Capture the registration form data (name, email, password)
            const name = $("#register-name").val();
            const email = $("#register-email").val();
            const phone = $("#register-phone").val();
            const password = $("#register-password").val();

            // Get test data from localStorage
            const testData = JSON.parse(localStorage.getItem("testStepsData"));

            // Prepare data for submission
            const registrationData = {
                name,
                email,
                password,
                phone
            };

            // Simulate API request to register the user
            $.ajax({
                url: "{{ route('front.register') }}",
                method: "POST",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify(registrationData),
                success: function (data) {
                    if (data.success) {
                        const userId = data.user.id;

                        // Now, associate the user ID with the test form data and save it to the database
                        $.ajax({
                            url: "{{ route('front.submit-free-test') }}",
                            method: "POST",
                            contentType: "application/json",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: JSON.stringify({ userId, name, email, phone, testData }),
                            success: function () {
                                // alert("Registration and Test Data Submission Successful!");

                                // Clear localStorage and close the modal
                                localStorage.removeItem("testStepsData");
                                registerModal.modal('hide'); // Close the register modal
                                showThankYouModal();
                            },
                            error: function () {
                                alert("Error submitting test data.");
                            }
                        });
                    } else {
                        alert("Registration failed.");
                    }
                },
                error: function () {
                    alert("Error registering.");
                }
            });
        });

        // Login form submit handler
        loginForm.submit(function (event) {
            event.preventDefault();

            const testData = JSON.parse(localStorage.getItem("testStepsData"));

            // Capture the login form data (email, password)
            const email = $("#test-login-email").val();
            const password = $("#test-login-password").val();
            console.log(email);
            console.log(password);
            // Prepare data for login submission
            const loginData = {
                email,
                password
            };

            // Simulate API request to log the user in
            $.ajax({
                url: "{{ route('front.login') }}",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(loginData),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.success) {
                        const userId = data.user.id;
                        const name = data.user.name;
                        // Handle success (store user data, etc.)
                        $.ajax({
                            url: "{{ route('front.submit-free-test') }}",
                            method: "POST",
                            contentType: "application/json",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: JSON.stringify({ userId, name, email, testData }),
                            success: function () {
                                // alert("Registration and Test Data Submission Successful!");

                                // Clear localStorage and close the modal
                                localStorage.removeItem("testStepsData");
                                loginModal.modal('hide'); // Close the register modal
                                showThankYouModal();
                            },
                            error: function () {
                                alert("Error submitting test data.");
                            }
                        });

                        // Close the login modal
                        loginModal.modal('hide');
                    } else {
                        alert("Login failed.");
                    }
                },
                error: function () {
                    alert("Error logging in.");
                }
            });
        });

        // Switch to the login modal from the register modal
        loginLink.click(function () {
            registerModal.modal('hide');
            loginModal.modal('show');
        });

        // Switch to the register modal from the login modal
        registerLink.click(function () {
            loginModal.modal('hide');
            registerModal.modal('show');
        });

        function showThankYouModal() {
            // Set dynamic content
            const thankYouMessage = "We make around 300 food decisions a day... to perform at your best order your Personalised plan today.";
            const planUrl = "https://performancehealthsupport.com/action-sport-nutrition-plan";
            // Set the modal message
            $('#thankYouMessage').text(thankYouMessage);
            
            // Set the URL for the plan button dynamically
            $('#planUrlLink').attr('href', planUrl); // Set the plan URL dynamically
            $('#thankYouModal').modal('show');
        }
    });

    // Add this JavaScript code to your page
    $(document).ready(function() {
        var stripe = Stripe('pk_test_51QI09cHWqn47bqTGYhGZIsiPSerWujjQgoHf4g0JwygrNt1OMC3RtEnMIjiEWbc8hiaN4umn4TD5zB8sBQEqcjzY0071a4RbUv');
        var elements = stripe.elements();
        var style = {
            base: {
                color: '#32325d',
                border:'1px solid #32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create card element
        var card = elements.create('card', { style: style });
        var cardErrors = document.getElementById('card-errors');
        card.mount('#card-element');

        // Handle card input changes
        card.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Event listener for the 'Purchase Now' button
        $('body').on('click', '.purchase-now-btn', function () {
            // alert('Payment button clicked');
            // e.preventDefault();

            var planId = $(this).data('plan-id');  // Get the plan ID
            var price = $(this).data('plan-price');     // Get the plan price (if needed)
            
            // Update modal title with plan name (optional)
            $('#purchaseModalLabel').text('Purchase ' + $(this).closest('.spot-plan-box').find('h5').text());

            // Show the modal
            $('#purchaseModal').modal('show');

            // Handle the form submission
            $('#payment-form').submit(function(event) {
                event.preventDefault();

                // Disable the submit button to prevent multiple clicks
                $('#submit').prop('disabled', true);

                // Create a PaymentMethod with Stripe's API
                
                stripe.createPaymentMethod({
                    type: 'card',
                    card: card,
                    billing_details: {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        phone: $('#phone').val(),
                    },
                }).then(function(result) {
                    if (result.error) {
                        // Display error in the card element
                        cardErrors.textContent = result.error.message;
                        $('#submit').prop('disabled', false);
                    } else {
                        // Call the server to create the PaymentIntent
                        $.ajax({
                            url: '{{ route("process.payment") }}', // Define the route to process the payment
                            method: 'POST',
                            data: {
                                payment_method_id: result.paymentMethod.id,
                                plan_id: planId,
                                price: price,
                                name: $('#name').val(),
                                email: $('#email').val(),
                                phone: $('#phone').val(),
                                password: $('#password').val(),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Handle successful payment
                                    // alert('Payment successful!');
                                    $('#purchaseModal').modal('hide');
                                    // $('#thankYouModal').modal('show');

                                    var user_id = response.data.user_id;  // Assuming the backend sends the user_id
                                    var payment_id = response.data.payment_id;  // Assuming the backend sends the user_id

                                    // Check if there's a redirect URL provided
                                    if (response.redirect_url) {

                                        var redirectUrlWithUserId = response.redirect_url + '?id=' + payment_id +'&user_id='+ user_id;
                                        // Redirect the user to the provided URL after a delay (optional)
                                        setTimeout(function() {
                                            window.location.href = redirectUrlWithUserId;
                                        }, 3000); // 3-second delay before redirecting (adjust as needed)
                                    }

                                } else {
                                    // Handle failed payment
                                    alert('Payment failed: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Payment error:', error);
                                alert('An error occurred while processing the payment.');
                                $('#submit').prop('disabled', false);
                            }
                        });
                    }
                });
            });
            
            $('#view-sample-plan').click(function () {
                // var planId = $(this).data('plan-id');
                
                $('#samplePlanModalLabel').text('Loading...');
                $('#samplePlanModalBody').html('<p>Loading details...</p>');
                $('#samplePlanModal').modal('show');

                $.ajax({
                    url: '{{ route("front.get-default-plan-details", ":id") }}'.replace(':id', planId),
                    method: 'GET',
                    success: function (response) {
                        if (response.error) {
                            $('#samplePlanModalBody').html('<p>' + response.error + '</p>');
                            return;
                        }

                        // Build the modal content for main plan
                        const mainPlan = response.mainPlan;
                        let modalContent = `<h5>${mainPlan.name}</h5>`;
                        // modalContent += `<p>Price: $${mainPlan.price}</p>`;
                        modalContent += buildMealTimeHtml(mainPlan.mealTimes);

                        // Build the modal content for subPlans
                        if (response.subPlans.length > 0) {
                            modalContent += `<h5></h5>`;
                            response.subPlans.forEach(function (subPlan) {
                                modalContent += `<div class="mt-3"><h6>Sub Plan: ${subPlan.name}</h6>`;
                                modalContent += `<p>Price: $${subPlan.price}</p>`;
                                modalContent += buildMealTimeHtml(subPlan.mealTimes);
                                modalContent += `</div>`;
                            });
                        }

                        $('#samplePlanModalLabel').text('Plan Details: ' + mainPlan.name);
                        $('#samplePlanModalBody').html(modalContent);
                    },
                    error: function () {
                        $('#samplePlanModalBody').html('<p>Error fetching plan details. Please try again later.</p>');
                    }
                });
            });

            // Function to build HTML for mealTimes, categories, meals, and items
            function buildMealTimeHtml(mealTimes) {
                let html = `<ul>`;
                mealTimes.forEach(function (mealTime) {
                    html += `<li><strong>${mealTime.title}</strong> (Meal Time)<ul>`;

                    mealTime.categories.forEach(function (category) {
                        html += `<li><strong>${category.name}</strong> (Category)<ul>`;

                        category.meals.forEach(function (meal) {
                            html += `<li><strong>${meal.name}</strong> (Meal)<ul>`;

                            meal.items.forEach(function (item) {
                                html += `<li>${item.name} (Food)<ul>`;

                                item.swapItems.forEach(function (swapItem) {
                                    html += `<li>${swapItem.name} (Swap Food)</li>`;
                                });

                                html += `</ul></li>`;
                            });

                            html += `</ul></li>`;
                        });

                        html += `</ul></li>`;
                    });

                    html += `</ul></li>`;
                });
                html += `</ul>`;
                return html;
            }


        });
    });

    // Submit Login Form
    $('#login-form').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting the normal way

        // Disable the Submit Button to avoid multiple clicks
        $('#login-submit').prop('disabled', true);

        // Get the form data
        var email = $('#login-email').val();
        var password = $('#login-password').val();

        // Send the data to the backend for validation
        $.ajax({
            url: '{{ route("front.login") }}', // This is the route for handling login (update with your actual route if different)
            method: 'POST',
            data: {
                email: email,
                password: password,
                _token: '{{ csrf_token() }}' // CSRF token for protection
            },
            success: function(response) {
                if (response.success) {
                    // If login is successful, redirect to the given URL
                    window.location.href = response.redirect_url;
                }
            },error: function(xhr) {
                var response = xhr.responseJSON;

                // Show error messages for validation errors
                if (response.message) {
                    $('#login-error').text(response.message); // Display error message in #login-error div
                } else {
                    $('#login-error').text('An error occurred. Please try again.'); // General error message
                }

                $('#login-submit').prop('disabled', false); // Re-enable submit button
            }
        });
    });

    // Show Sign-In Modal when clicking "Sign In" link in the Sign-Up Modal
    $('#show-login-modal').click(function(e) {
        e.preventDefault(); // Prevent default link action
        $('#purchaseModal').modal('hide'); // Hide the sign-up modal
        $('#loginModal').modal('show'); // Show the sign-in modal
    });

    // Show Sign-Up Modal when clicking "Sign Up" link in the Sign-In Modal
    $('#show-signup-modal').click(function(e) {
        e.preventDefault(); // Prevent default link action
        $('#loginModal').modal('hide'); // Hide the sign-in modal
        $('#registerModal').modal('hide'); // Show the sign-up modal
        $('#purchaseModal').modal('show'); // Show the sign-up modal
    });

</script>   

@endsection

@push('scripts')
<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
@endpush