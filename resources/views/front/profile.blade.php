@extends(frontView('layouts.app'))

@section('title', 'Profile Page')

@section('content')
    <div class="nutrition-plan-hero bg-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="nutrition-plan-text">
                        <h1>We Take Care Of Your <span class="text-primary">Health</span></h1>
                        <p>Make sure your daily nutrition is sufficient. Consult your Nutrition Supplements Products about nutrition with us.</p>
                        <!-- <a href="#" class="btn btn-primary">
                            <span class="me-1">Get Started</span>
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>
                                <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a> -->
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 ms-lg-auto">
                    <div class="nutrition-plan-img-box">
                        <div class="go-bottom-link d-none">
                            <figure class="top-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M50,45V0H3v0.1h2.1C29.9,0.1,50,20.2,50,45z" fill="#fafafa"/>
                                </svg>
                            </figure>
                            <a href="#nextSection" class="btn btn-primary">
                                <svg width="71" height="72" viewBox="0 0 71 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M50.8233 17.2911C49.0555 17.2911 47.7297 18.6169 47.7297 20.3847L47.7297 43.6603L22.2444 18.175C20.9186 17.1438 18.8562 17.1438 17.6777 18.3223C16.4992 19.5008 16.4992 21.5632 17.6777 22.7417L43.3103 48.3744L20.0347 48.3744C18.2669 48.3744 16.9411 49.7002 16.9411 51.4679C16.9411 53.2357 18.2669 54.5615 20.0347 54.5615H50.9706C51.2653 54.5615 51.7072 54.4142 52.1491 54.2669C52.4438 54.2669 52.7384 53.9723 53.033 53.6777C53.3276 53.383 53.6223 53.0884 53.7696 52.6465C53.9169 52.2045 54.0642 51.7626 54.0642 51.4679L54.0642 20.532C53.9169 18.9116 52.4438 17.4384 50.8233 17.2911Z" fill="white"/>
                                </svg>                                    
                            </a>
                            <figure class="bottom-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M50,45V0H3v0.1h2.1C29.9,0.1,50,20.2,50,45z" fill="#fafafa"/>
                                </svg>
                            </figure>
                        </div>
                        <div class="nutrition-plan-img">
                            <figure style="padding-top:80%">
                                <img src="{!! frontAssets('images/nutrition-supplements.jpg') !!}" alt="images/nutrition-supplements.jpg" alt="">
                            </figure>
                        </div>
                        <div class="nutrition-bottom-box ">
                            <figure class="top-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M0,5v45h47v-0.1h-2.1C20.1,49.9,0,29.8,0,5z" fill="#fafafa"/>
                                </svg>
                            </figure>
                            <div class="nutrition-athlete-box">
                                <figure>
                                    @if(isset($user->profile_image))
                                    <img src="{{ asset('private/public/' .$user->profile_image) }}">
                                    @else
                                    <img src="{{ frontAssets('images/profile-image.jpeg') }}" alt="Profile Image">
                                    @endif
                                    <!-- <img src="{{ frontAssets('images/kerry-oBryan.jpg') }}" alt=""> -->
                                </figure>
                                <div class="nutrition-athlete-info">
                                    <h5>{{ $user->name }} </h5>
                                    <p>{{ $user->designation }}</p>
                                </div>
                                <!-- <div class="nutrition-athlete-info">
                                    <h5>Kerry O'Bryan </h5>
                                    <p>MNutr&Diet, B.Sp.Ex.Sc, IOC Dip Nut</p>
                                </div> -->
                            </div>
                            <figure class="bottom-corner">
                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <path d="M0,5v45h47v-0.1h-2.1C20.1,49.9,0,29.8,0,5z" fill="#fafafa"/>
                                </svg>
                            </figure> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="plan-buttons-link">
            <!-- <div class="container">
                <div class="d-flex flex-wrap align-items-center">
                    <a href="#">Tracker</a>
                    <a href="#">Plan</a>
                    <a href="#">Treatment</a>
                </div>
            </div> -->
        </div>
    </div>
    
    <div class="section bg-lighter pb-1 pt-0" id="nextSection">
        <div class="container">
            <div class="mt-4">
                <div class="row g-4">
                    {{-- Profile Section --}}
                    <div class="col-lg-4 col-md-12">
                        <div class="top-main-box h-100">
                            <h4 class="text-center mb-3">Profile</h4>
                            <div class="profile-box h-100">
                                <figure>
                                    <!-- <img src="{{ asset('private/public/front/images/athlete-sport.jpg') }}" alt=""> -->
                                    @if(isset($user->profile_image))
                                    <img src="{{ asset($user->profile_image) ?? frontAssets('images/profile-image.jpeg') }}" alt="Profile Image">
                                    @else
                                    <img src="{{ frontAssets('images/profile-image.jpeg') }}" alt="Profile Image">
                                    @endif
                                </figure>
                                <button class="btn btn-light edit-icon" data-bs-toggle="modal" data-bs-target="#editImageModal"
                                    data-form-name="profile_image" data-question="Profile Image" data-answer="{{ asset('private/public/storage/' . isset($profileDetails) ? $profileDetails['Profile Image'] : '') }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body nutrition-profile-info">
                                        <h4 class="text-center">
                                            <span class="mx-auto">{{ $profileDetails['Name'] ?? 'Nill' }}</span>
                                            <button class="btn btn-light edit-icon" data-bs-toggle="modal" data-bs-target="#editNameModal"
                                                data-form-name="profile_name" data-question="Name" data-answer="{{ $profileDetails['Name'] ?? 'Nill' }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </h4>
                                        <ul>
                                            <li>Sport: N/A
                                                <!-- <button class="btn btn-light edit-icon add-sport">
                                                    <i class="fas fa-edit"></i>
                                                </button> -->
                                            </li>
                                            <li>Weight: {{ !empty($profileDetails['Current body weight (kg) (if known):']) ? $profileDetails['Current body weight (kg) (if known):'] : 'N/A' }} kg
                                                <!-- <button class="btn btn-light edit-icon edit-details" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-form-name="physical_measures" data-question="Current body weight (kg) (if known):" data-answer="{{ $profileDetails['Current body weight (kg) (if known):'] ?? 'Nill' }}">
                                                    <i class="fas fa-edit"></i>
                                                </button> -->
                                            </li>
                                            <li><a href="#" class="text-decoration-underline" id="weight-tracking">Track Your Weight</a></li>
                                            <li>Height: {{ !empty($profileDetails['Height (cm):']) ? $profileDetails['Height (cm):'] : 'N/A' }} cm
                                                <button class="btn btn-light edit-icon" data-bs-toggle="modal" data-bs-target="#editHeightModal" data-type="physical_measures"
                                                    data-form-name="physical_measures" data-question="Height (cm):" data-answer="{{ $profileDetails['Height (cm):'] ?? 'Nill' }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </li>

                                            <li><a href="#" class="text-decoration-underline report-link" id="blood-report" data-report-type="medical_history">Blood Report</a></li>

                                            <li><a href="#" class="text-decoration-underline report-link" id="body-composition-report" data-report-type="physical_measures">Body Composition Report</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>

                    {{-- Goals Section --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="top-main-box h-100">
                            <h4 class="text-center mb-3">Goals</h4>
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="p-4 card-body goal-card">
                                    <ul>
                                        <li><strong>Nutrition Goals:</strong> {{ $nutritionGoalsDetails['Which of the following nutrition related goals are you interested in working on?'] ?? 'Nill' }}
                                        <div class="btn-list">
                                            <button class="btn btn-light edit-icon add-goal " title="Add Goal" data-type="goal"
                                                data-form-name="nutrition_goals" data-question="Which of the following nutrition related goals are you interested in working on?" data-answer="{{ $nutritionGoalsDetails['Which of the following nutrition related goals are you interested in working on?'] ?? 'Nill' }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button class="btn btn-light edit-icon view-past-goals" title="View Past Goals" data-type="goal"
                                                data-form-name="nutrition_goals" data-question="Which of the following nutrition related goals are you interested in working on?" data-answer="{{ $nutritionGoalsDetails['Which of the following nutrition related goals are you interested in working on?'] ?? 'Nill' }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            </div>
                                        </li>
                                        <li><strong>Nutrition Challenge:</strong> {{ $nutritionGoalsDetails['What is your biggest nutrition challenge?'] ?? 'Nill' }}
                                        <div class="btn-list">
                                            <button class="btn btn-light edit-icon add-goal" title="Add Challenge" data-type="challenge"
                                                data-form-name="nutrition_goals" data-question="What is your biggest nutrition challenge?" data-answer="{{ $nutritionGoalsDetails['What is your biggest nutrition challenge?'] ?? 'Nill' }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button class="btn btn-light edit-icon view-past-goals" title="View Past Challenges" data-type="challenge"
                                                data-form-name="nutrition_goals" data-question="Which of the following nutrition related goals are you interested in working on?" data-answer="{{ $nutritionGoalsDetails['Which of the following nutrition related goals are you interested in working on?'] ?? 'Nill' }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        </li> 
                                    </ul>
                                    <div class="goal-list mt-4 mt-md-5">
                                        <h5 class="mb-3">Training Intensity</h5>
                                        <canvas id="trainingChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Intake Section --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="top-main-box h-100">
                            <h4 class="text-center mb-3">History</h4>
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="p-0 card-body intake-card">
                                    <div class="px-4 py-3 border-bottom">
                                        <div class="position-relative">
                                            @php
                                                $vitaminDetails = $intakeDetails['List any dietary vitamins or supplements you are currently taking (if any):'] ?? null;
                                                $vitaminAnswer = $vitaminDetails['answer'] ?? null;
                                                $vitaminStartDate = isset($vitaminDetails['start_date']) ? \Carbon\Carbon::parse($vitaminDetails['start_date'])->format('d-m-Y') : null;
                                                $vitaminEndDate = isset($vitaminDetails['end_date']) ? \Carbon\Carbon::parse($vitaminDetails['end_date'])->format('d-m-Y') : null;

                                                $supplements = $vitaminAnswer ? explode(',', $vitaminAnswer) : [];

                                                $medications = $intakeDetails['Provide details of any prescription medications (if taking any):'] ?? null;
                                                $medicationStartDate = isset($medications['start_date']) ? \Carbon\Carbon::parse($medications['start_date'])->format('d-m-Y') : null;
                                                $medicationEndDate = isset($medications['end_date']) ? \Carbon\Carbon::parse($medications['end_date'])->format('d-m-Y') : null;
                                            @endphp

                                            <strong>Supplements:</strong>

                                            @if (!empty($supplements))
                                                <ul class="ps-3 mt-3">
                                                    @foreach ($supplements as $item)
                                                        @php $item = trim($item); @endphp
                                                        <li class="d-flex justify-content-between align-items-start mb-1">
                                                            <span>{{ $item }}</span>
                                                            <div class="btn-list ms-2">
                                                                <!-- <button class="btn btn-sm btn-light edit-icon edit-supliment-details" data-bs-toggle="modal" data-bs-target="#editModal"
                                                                    data-form-name="medical_history"
                                                                    data-question="List any dietary vitamins or supplements you are currently taking (if any):"
                                                                    data-answer="{{ $item }}"
                                                                    data-main-ans="{{ $vitaminAnswer }}"
                                                                    data-type="supplement-edit"
                                                                    data-startdate="{{ $vitaminStartDate }}"
                                                                    data-enddate="{{ $vitaminEndDate }}" >
                                                                    <i class="fas fa-pen"></i>
                                                                </button> -->
                                                            </div>
                                                        </li>
                                                        @if ($vitaminStartDate)
                                                            <small>
                                                                Duration: {{ $vitaminStartDate }}
                                                                @if ($vitaminEndDate)
                                                                    to {{ $vitaminEndDate }}
                                                                @endif
                                                            </small>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>Nill</p>
                                            @endif

                                            <div class="btn-list mt-2">
                                                <button class="btn btn-light edit-icon edit-details" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-form-name="medical_history"
                                                    data-question="List any dietary vitamins or supplements you are currently taking (if any):"
                                                    data-answer="{{ $vitaminAnswer ?? 'Nill' }}"
                                                    data-type="supplement">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button class="btn btn-light edit-icon view-past-history"
                                                    title="View Past Supplements"
                                                    data-form-name="medical_history"
                                                    data-question="List any dietary vitamins or supplements you are currently taking (if any):"
                                                    data-answer="{{ $vitaminAnswer ?? 'Nill' }}"
                                                    data-type="supplement">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 border-bottom">
                                        <div class="position-relative">
                                            <strong>Medications:</strong>
                                            <p>{{ $medications['answer'] ?? 'Nill' }} </p>
                                            @if ($medicationStartDate)
                                                <small>
                                                    Duration: {{ $medicationStartDate }}
                                                    @if ($medicationEndDate)
                                                        to {{ $medicationEndDate }}
                                                    @endif
                                                </small>
                                            @endif
                                            <div class="btn-list">
                                                <button class="btn btn-light edit-icon edit-details" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-form-name="medical_history" data-question="Provide details of any prescription medications (if taking any):" data-answer="{{ $medications['answer'] ?? 'Nill' }}" data-type="medication">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button class="btn btn-light edit-icon view-past-history" title="View Past Medications" 
                                                data-form-name="medical_history" data-question="Provide details of any prescription medications (if taking any):" data-answer="{{ $medications['answer'] ?? 'Nill' }}" data-type="medication">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    
                            {{--    <div class="px-4 py-3 border-bottom">
                                        <strong>Favourite Food:</strong>
                                        <p>{{ $intakeDetails['List your favourite foods?'] ?? 'Nill' }}
                                            <button class="btn btn-light edit-icon edit-details" data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-form-name="dietary_information" data-question="List your favourite foods?" data-answer="{{ $intakeDetails['List your favourite foods?'] ?? 'Nill' }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </p>
                                    </div>
                                    <div class="px-4 py-3 border-bottom">
                                        <strong>Foods | Dislike:</strong>
                                        <p>{{ $intakeDetails['Do you avoid/dislike any foods? List below'] ?? 'Nill' }}
                                            <button class="btn btn-light edit-icon edit-details" data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-form-name="dietary_information" data-question="Do you avoid/dislike any foods? List below" data-answer="{{ $intakeDetails['Do you avoid/dislike any foods? List below'] ?? 'Nill' }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </p>
                                    </div>
                                --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-8 ms-auto">
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="card-header bg-white">
                                <h4 class="mt-2">Nutrition plan</h4>
                            </div>
                            <div class="card-body">
                                @foreach($plans as $plan)
                                    @if(in_array($plan->id,$purchasedplans))
                                    <?php
                                        $userPlan = \App\Models\UserPlan::where('user_id', $user->id)->where('plan_id', $plan->id)->where('status', 'active')->first();
                                        $isPlanCreated = $userPlan ? true : false;
                                    ?>
                                    <div class="card rounded-3 shadow-none overflow-hidden">
                                        <div class="card-header bg-white">
                                            <h5 class="mt-2 d-flex align-items-center">
                                                <figure class="title-ico">
                                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19.8333 8.16732C19.8333 6.62022 19.2188 5.13649 18.1248 4.04253C17.0308 2.94857 15.5471 2.33398 14 2.33398C12.4529 2.33398 10.9692 2.94857 9.87521 4.04253C8.78125 5.13649 8.16667 6.62022 8.16667 8.16732V10M5.83333 12.834H22.1667C23.4553 12.834 24.5 13.8787 24.5 15.1673V23.334C24.5 24.6226 23.4553 25.6673 22.1667 25.6673H5.83333C4.54467 25.6673 3.5 24.6226 3.5 23.334V15.1673C3.5 13.8787 4.54467 12.834 5.83333 12.834Z" stroke="#344356" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </figure>
                                                {{ $plan->name }}
                                            </h5>
                                        </div>
                                        <div class="card-footer border-top-0 bg-white">
                                            <div class="d-flex flex-wrap">
                                                <a href="{{ route('front.plans.details', ['id' => $plan->id, 'user_id' => $user->id]) }}" class="btn btn-primary m-2 @if(!$isPlanCreated) disabled @endif" @if(!$isPlanCreated) style="pointer-events: none; opacity: 0.5;" @endif>
                                                View Plan</a>
                                                <a href="javascript:void(0);" class="btn btn-primary m-2 print-plan-btn @if(!$isPlanCreated) disabled @endif" data-user-id="{{ $user->id}}" data-plan-id="{{ $plan->id}}">Print Plan</a>
                                                <a href="#" class="btn btn-primary m-2 @if(!$isPlanCreated) disabled @endif" data-bs-toggle="modal" data-bs-target="#ShoppingModal" id="fetchAllMeals">Shopping List</a>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="card rounded-3 shadow-none overflow-hidden mt-3">
                                        <div class="card-header bg-white">
                                            <h5 class="mt-2 d-flex align-items-center">
                                                <figure class="title-ico">
                                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.16667 12.834V8.16732C8.16667 6.62022 8.78125 5.13649 9.87521 4.04253C10.9692 2.94857 12.4529 2.33398 14 2.33398C15.5471 2.33398 17.0308 2.94857 18.1248 4.04253C19.2188 5.13649 19.8333 6.62022 19.8333 8.16732V12.834M5.83333 12.834H22.1667C23.4553 12.834 24.5 13.8787 24.5 15.1673V23.334C24.5 24.6226 23.4553 25.6673 22.1667 25.6673H5.83333C4.54467 25.6673 3.5 24.6226 3.5 23.334V15.1673C3.5 13.8787 4.54467 12.834 5.83333 12.834Z" stroke="#344356" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>                                                
                                                </figure>
                                                {{ $plan->name }}
                                            </h5>
                                        </div>
                                        <div class="card-footer gray-bg border-top-0">
                                            <div class="d-flex flex-wrap">
                                                <a href="javascript:void(0);" class="btn btn-white m-2 buy-plan-btn" data-user-id="{{ $user->id}}" data-plan-id="{{ $plan->id}}" data-plan-price="{{ $plan->price }}" data-plan-name="{{ $plan->name }}">Buy Plan</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                                <div class="card rounded-3 border-0 shadow-none overflow-hidden mt-3 talk-expert-box">
                                    <div class="card-body p-4 p-md-5">
                                        <h6>Powered by BioHealth<span>Passport</span></h6>
                                        <figure>
                                            <img src="https://booking.biohealthpassport.com.au/public/uploads/front_logo/1727981512_1727875441_logo.png" alt="">
                                        </figure>
                                        <h3>Get answers from a real-life expert. Not a chat bot.</h3>
                                        <!-- <p>Schedule a meeting to help determine where to start on your path to ELITE PERFORMANCE or AGE BETTER than your parents.</p> -->
                                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-white">Book Now 
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.4165 15.5827L15.5832 6.41602M15.5832 6.41602H6.4165M15.5832 6.41602V15.5827" stroke="#124E4D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                        <div class="kerry-info-box">
                                            <figure>
                                                <img src="https://booking.biohealthpassport.com.au/public/uploads/hero01.png" alt="">
                                            </figure>
                                            <div class="kerry-info">
                                                <h5>Kerry O'Bryan</h5>
                                                <p>MNutr&Diet, B.Sp.Ex.Sc, IOC Dip Nut</p>
                                                <p>(Dietitian /Sports Scientist/Strength & Conditioning Coach)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section"></div>
    </div>

    <!-- Modal for Editing Height -->
    <div class="modal" id="editHeightModal" tabindex="-1" aria-labelledby="editHeightModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHeightModalLabel">Edit Height</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editHeightForm">
                        <div class="mb-3">
                            <label for="heightQuestion" class="form-label">Question</label>
                            <input type="text" class="form-control" id="heightQuestion" name="heightQuestion" readonly>
                            <input type="hidden" class="form-control" id="formName" name="formName" >
                        </div>
                        <div class="mb-3">
                            <label for="heightAnswer" class="form-label">Height (cm)</label>
                            <input type="number" class="form-control" id="heightAnswer" name="heightAnswer" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Profile Image Modal -->
    <div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editImageModalLabel">Edit Profile Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editImageForm" method="POST" enctype="multipart/form-data" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 text-center">
                            <img id="imagePreview" src="{{ asset($user->profile_image) }}" alt="Current Profile Image" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                        </div>
                        <div class="mb-3">
                            <label for="profileImageInput" class="form-label">Upload New Image</label>
                            <input type="file" class="form-control" id="profileImageInput" name="profile_image" accept="image/*">
                            <input type="hidden" class="form-control" id="profileId" name="id" value="{{ $user->id }}">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="submitProfileUpdate()">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Profile Name Modal -->
    <div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNameModalLabel">Edit Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editNameForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="profileNameInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="profileNameInput" name="name" value="{{ $user->name }}">
                            <input type="hidden" class="form-control" id="profileId" name="id" value="{{ $user->id }}">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="submitProfileUpdate()">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Weight trak Modal -->
    <div class="modal fade" id="WeightModal" tabindex="-1" aria-labelledby="WeightModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="WeightModalLabel">Record Weight</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <div class="form-group mb-3">
                            <label for="weightGoal">Current Weight</label>
                            <input type="text" class="form-control" id="weight" placeholder="Weight">
                            <input type="hidden" class="form-control" id="userId" value="{{ $user->id }}" placeholder="Weight">
                        </div>   
                        <div class="form-group mb-3">
                            <label for="weightGoal">Weight Goal</label>
                            <input type="text" class="form-control" id="weightGoal" placeholder="Weight Goal">
                        </div>
                        <div class="form-group">
                            <label for="weightGoal">Date</label>
                            <input type="date" class="form-control" id="date" placeholder="Date">
                        </div>   
                    </form>
                </div>
                <div class="modal-footer p-0">
                    <a href="#" class="btn btn-primary m-0 w-100 text-center rounded-0" id="saveWeight">Save</a>
                </div>
            </div>
        </div>
    </div>

    <!--Weight chart Modal -->
    <div class="modal fade" id="WeightGraphModal" tabindex="-1" aria-labelledby="WeightGraphModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="WeightGraphModalLabel">Weight Chart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-3 col-6">
                            <div class="h-100 border px-3 py-2 weight-info">
                                <p class="mb-1 text-black-50">Start</p>
                                <h5 id="start-weight">95.1<span class="text-black-50">KG</span></h5>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="h-100 border px-3 py-2 weight-info">
                                <p class="mb-1 text-black-50">Goal</p>
                                <h5 id="weight-goal">88.0<span class="text-black-50">KG</span></h5>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="h-100 border px-3 py-2 weight-info">
                                <p class="mb-1 text-black-50">Change</p>
                                <h5 id="weight-diff">-3.0<span class="text-black-50">KG</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="weight-filter">
                        <ul>
                            <li><a href="#" class="active">1W</a></li>
                            <li><a href="#">2W</a></li>
                            <li><a href="#">1M</a></li>
                            <li><a href="#">3M</a></li>
                            <li><a href="#">6M</a></li>
                            <li><a href="#">1Y</a></li>
                            <li><a href="#">ALL</a></li>
                        </ul>
                    </div>

                    <div class="graph-img mt-3">
                        <canvas id="line-chart" width="400" height="200"></canvas>
                    </div>
                </div>
                <!-- <div class="modal-footer p-0">
                    <a href="#" class="btn btn-primary m-0 w-100 text-center rounded-0">Close</a>
                </div> -->
            </div>
        </div>
    </div>

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
                        <div id="registration-details">
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="name" value="{{ $user->name }}">
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="email" value="{{ $user->email }}" >
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="phone" value="">
                            </div>
                        </div>
                        <!-- Promo Code Section -->
                        <div id="coupon-details">
                            <div class="mb-3">
                                <label for="promo-code" class="form-label">Enter Coupon Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="promo-code" placeholder="Enter coupon code">
                                    <input type="hidden" class="form-control" id="discount">
                                    <button type="button" class="btn btn-primary" id="apply-promo-code">Apply</button>
                                </div>
                                <small id="promo-message" class="form-text "></small>
                            </div>
                        </div>
                        <div id="payment-details">
                            
                            <!-- Stripe Payment Card Section -->
                            <h6 class="mb-3">Payment Details</h6>
                            <div class="mb-3">
                                <label for="card-element" class="form-label">Credit or Debit Card</label>
                                <div id="card-element" class="border rounded p-3" style="background-color: #f9f9f9;">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                            </div>
                        </div>

                        <button type="submit" id="submit" class="btn btn-primary w-100 mt-3">
                            Buy Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="formName" name="form_name">
                        <input type="hidden" id="formQuestion" name="question">
                        <input type="hidden" id="type" name="type">
                        <input type="hidden" id="mainAns" name="mainAns">

                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <p id="questionText"></p>
                            <input type="hidden" id="question" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Your Answer</label>
                            <textarea id="answer" name="answer" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3 startDate">
                            <label class="form-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-control">
                        </div>

                        <div class="mb-3 endDate">
                            <label class="form-label">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Sport Modal -->
    <div class="modal" id="editSportModal" tabindex="-1" aria-labelledby="editSportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSportModalLabel">Edit Sport</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSportForm" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Sport Name Input -->
                        <div class="mb-3">
                            <label for="sportNameInput" class="form-label">Sport Name</label>
                            <input type="text" class="form-control" id="sportNameInput" name="sport_name" value="{{ $user->sport_name ?? '' }}" required>
                        </div>

                        <!-- Sport Image Upload -->
                        <div class="mb-3">
                            <label for="sportImageInput" class="form-label">Sport Image</label>
                            <input type="file" class="form-control" id="sportImageInput" name="sport_image" accept="image/*">
                        </div>

                        <!-- Preview Uploaded Image -->
                        <div class="mb-3">
                            <!-- <label class="form-label">Current Sport Image</label> -->
                            <div>
                                <img id="sportImagePreview" src="" class="img-fluid rounded" style="max-width: 200px;">
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="submitSportUpdate()">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <!-- Add Goal / Challenge Modal -->
     <div class="modal" id="addGoalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemTitle">Add Nutrition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editItemForm">
                        <input type="hidden" id="itemType">
                        <label id="itemLabel">New Value</label>
                        <input type="text" class="form-control" id="itemInput">
                        <button type="button" class="btn btn-primary mt-3" id="saveGoal">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Past Goals & Challenges Modal -->
    <div class="modal" id="viewPastItemsModal" tabindex="-1" aria-labelledby="viewPastItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPastItemsModalLabel">Past Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="pastItemsList"></ul> <!-- Past goals/challenges will be appended here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Report File Upload Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Upload Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="sportNameInput" class="form-label">Report Name</label>
                        <input type="text" class="form-control" id="file_name" name="file_name" value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="sportImageInput" class="form-label">Upload Report</label>
                        <input type="file" class="form-control" id="fileUpload" name="file" multiple>
                    </div>
                    <input type="hidden" id="report_type" value="">
                    <br>
                    <h6>Existing Reports:</h6>
                    <div id="existingReports"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="uploadBtn">Upload</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Plan Preview Modal -->
    <div class="modal" id="planPreviewModal" tabindex="-1" aria-labelledby="planPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customise your meals before you PRINT plan.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="plan-preview-body">
                    <div class="text-center">Loading preview...</div>
                </div>
                <div class="modal-footer">
                    <form id="downloadPdfForm" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="user_id" value="">
                        <button type="submit" class="btn btn-success">Download PDF</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @php
        $trainingIntensityValue = isset($trainingIntencity[0]) && !empty($trainingIntencity[0]) ? $trainingIntencity[0] : null;
    @endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
    const user = @json($user);
    const userId = user.id;
    const userPrePlan = @json($userPrePlan);

    document.getElementById('profileImageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    function submitProfileUpdate() {
        const formData = new FormData();
        const profileIdInput = document.getElementById('profileId');
        // Add profile image if it exists
        const profileImageInput = document.getElementById('profileImageInput');
        if (profileImageInput.files[0]) {
            formData.append('profile_image', profileImageInput.files[0]);
        }

        // Add name
        const profileNameInput = document.getElementById('profileNameInput');
        if (profileNameInput.value) {
            formData.append('name', profileNameInput.value);
        }

        formData.append('user_id', profileIdInput.value);

        // Send AJAX request
        fetch("{{ route('front.profile.update') }}", {
            method: 'POST', // or 'PUT' if using PUT method
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI elements based on server response
                if (data.new_image_url) {
                    document.getElementById('currentProfileImage').src = data.new_image_url;
                }
                if (data.new_name) {
                    document.querySelector('h4.text-center').textContent = data.new_name;
                }
                alert('Profile updated successfully!');
                // Close all modals
                // Close all modals
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                });

                // Reload the page to reflect all updates (optional)
                setTimeout(() => {
                    location.reload();
                }, 500); 
            } else {
                alert('Error updating profile');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Handle opening the edit modal and populating the fields with data
    $('#editHeightModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var question = button.data('question'); // Extract the question (e.g., "Height (cm):")
        var answer = button.data('answer'); // Extract the answer (Height value)
        var formName = button.data('form-name'); // Extract the answer (Height value)
        
        var modal = $(this);
        modal.find('#heightQuestion').val(question); // Populate the question field (read-only)
        modal.find('#heightAnswer').val(answer); // Populate the height value in the input field
        modal.find('#formName').val(formName); // Populate the height value in the input field
    });

    // Handle form submission to update the height
    $('#editHeightForm').on('submit', function (e) {
        e.preventDefault();
        
        var updatedHeight = $('#heightAnswer').val(); // Get the new height value
        
        // AJAX request to update the height (You should change the URL and method as per your requirement)
        let formData = {
            form_name: $('#formName').val(),
            question: $('#heightQuestion').val(),
            answer: $('#heightAnswer').val(),
            type: "height",
            user_id: userId,
            _token: '{{ csrf_token() }}' // CSRF protection
        };

        $.ajax({
            url: "{{ route('front.sample-plan-details-update') }}", // Laravel route to handle updates
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Updated successfully!');
                    $('#editModal').modal('hide'); // Close modal
                    location.reload(); // Refresh page to reflect changes (or update UI dynamically)
                } else {
                    alert('Error updating!');
                }
            },
            error: function(xhr) {
                alert('Something went wrong!');
            }
        });
    
    });


    $(document).on('change', '.meal-item-checkbox', function () {
        let allItems = $('.meal-item-checkbox'); // All item checkboxes
        let allChecked = allItems.length === allItems.filter(':checked').length; // Check if all are selected

        // Set the global "Select All" checkbox state
        $('#selectAllCheckbox').prop('checked', allChecked);
    });
    
    $(document).on('change', '#selectAllCheckbox', function () {
        let isChecked = $(this).is(':checked'); // Check if "Select All" is checked

        // Toggle all checkboxes based on the state of "Select All"
        $('.meal-item-checkbox').prop('checked', isChecked);
    });

    $(document).on('click', '#fetchAllMeals', function () {
        // Show loader or clear previous content
        $('#ShoppingModal .modal-body').html('<p>Loading...</p>');

        // Fetch all meals with items
        $.ajax({
            url: '{{ route("front.get.meals.items") }}', // Adjust URL if needed
            method: 'GET',
            success: function (response) {
                let meals = response.meals;
                // let selectedItems = response.selectedItems;
                let modalContent = '';
                modalContent += `<div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                                        <label class="form-check-label" for="printPlanCheckbox">Select All</label>
                                    </div>`;                
                // Loop through each meal
                meals.forEach(meal => {
                    modalContent += `<div class="ingredient-list">
                                        <input type="checkbox" class="form-check-input mt-3 mx-3 meal-checkbox" id="id="mealCheckbox${meal.id}"">
                                        <h2 class="d-inline-block px-0" style="border-bottom:none;">${meal.title}</h2>
                                        <hr class="m-0">
                                        <ul>`;
                    
                    // Loop through each item in the meal
                    meal.items.forEach(item => {
                        // let isChecked = selectedItems[meal.id] && selectedItems[meal.id].includes(item.id) ? 'checked' : '';

                        modalContent += `<li>
                                            <div class="ingredient-info">
                                                <div class="form-check">
                                                    <input class="form-check-input meal-item-checkbox" type="checkbox" value="${item.id}" id="Check${item.id}">
                                                    <input type="hidden" id="category" value="${item.category?.name || ''}">
                                                    <label class="form-check-label" for="Check${item.id}">
                                                        <div class="ingredient-img">
                                                            <figure>
                                                                <img src="{{ asset('private/public/storage') }}/${item.image ? item.image : '' }" alt="${item.title}">
                                                            </figure>
                                                        </div>
                                                    </label>
                                                </div>
                                                <span>${item.title}</span>
                                            </div>
                                            <span class="quantity"><strong>QTY:</strong> ${item.pivot.item_qty} ${item.pivot.item_qty_unit}</span>
                                        </li>`;
                    });

                    modalContent += `</ul></div>`;
                });

                // Update modal content
                $('#ShoppingModal .modal-body').html(modalContent);
            },
            error: function (xhr) {
                console.error('Error fetching meals:', xhr);
                $('#ShoppingModal .modal-body').html('<p>Error loading data.</p>');
            }
        });
    });

    $(document).on('change', '.meal-checkbox', function () {
        const mealContainer = $(this).closest('.ingredient-list'); // Find the relevant meal container
        const isChecked = $(this).is(':checked'); // Check if "Meal Checkbox" is selected

        // Select/Deselect all meal items within this meal's container
        mealContainer.find('.meal-item-checkbox').prop('checked', isChecked);
    });
   
    $(document).on('click', '.btn-primary[data-bs-target="#ShippingPrintModal"]', function () {
        let aggregatedItems = {};

        // Collect all checked items
        $('#ShoppingModal .meal-item-checkbox:checked').each(function () {
            const listItem = $(this).closest('li');  // Correct reference for each item
            const itemName = listItem.find('.ingredient-info span').text().trim() || "Unknown Item";
            const quantityText = listItem.find('.quantity').text().trim() || "QTY: 0";
            const category = listItem.find('input[type="hidden"]#category').val().trim() || "Uncategorized";

            // Extract quantity and unit with better regex logic
            const quantityMatch = quantityText.match(/QTY:\s*([\d\/.]+)\s*([a-zA-Z]*)/i);
            let rawQuantity = quantityMatch && quantityMatch[1] ? quantityMatch[1] : "0";
            let unit = quantityMatch && quantityMatch[2] ? quantityMatch[2].trim() : '';

            // Correct conversion for fractional values
            let quantity = 0;
            if (rawQuantity.includes('/')) {
                const [numerator, denominator] = rawQuantity.split('/').map(Number);
                quantity = numerator / denominator;
            } else {
                quantity = parseFloat(rawQuantity);
            }

            // Ensure category exists in the aggregated structure
            if (!aggregatedItems[category]) {
                aggregatedItems[category] = {};
            }

            // Aggregate quantities within the category
            if (aggregatedItems[category][itemName]) {
                aggregatedItems[category][itemName].quantity += quantity;
                aggregatedItems[category][itemName].unit = unit;
            } else {
                aggregatedItems[category][itemName] = { quantity, unit };
            }
        });

        // Generate the HTML for the aggregated list by category
        let printListContent = '';
        for (let [category, items] of Object.entries(aggregatedItems)) {
            printListContent += `<h6>${category}</h6><ul style="list-style-type: none;">`;  // Removed dot style
            for (let [itemName, data] of Object.entries(items)) {
                printListContent += `
                    <li style="margin: 0;">
                        <!-- Right tick mark icon added here -->
                        <span style="margin-right: 2px; font-size: 18px; color: green;">&#10003;</span>  
                        ${itemName} <strong>| QTY:</strong> ${data.quantity} ${data.unit}
                    </li>
                `;
            }
            printListContent += `</ul><br/>`;
        }

        // Populate the print modal with the aggregated list
        $('#ShippingPrintModal .print-list').html(printListContent);
    });

    $(document).on('click', '#ShippingPrintModal .btn-primary', function () {
        // Get the content of the print list
        const content = $('#ShippingPrintModal .print-list').html();
        // Create a container to format the content for PDF
        const pdfContainer = `
            <div style="font-family: Arial, sans-serif; padding: 10px; max-width: 600px; margin: auto;">
                <h3 style="text-align: center;">Shopping List</h3><hr>
                <ul style="list-style: none; padding: 0;">
                    ${content}
                </ul>
            </div>
        `;

        // Use html2pdf to generate the PDF
        const options = {
            margin: 1,
            filename: 'shopping_list.pdf',
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(options).from(pdfContainer).save();
    });

    $(document).ready(function () {
        $(".print-plan-btn").click(function () {
            const planId = $(this).data("plan-id");
            const userId = $(this).data("user-id");
            // Set form action for download button
            $("#downloadPdfForm").attr("action", "{{ route('plans.generatePdf', ':id') }}".replace(':id', planId));

            $("#downloadPdfForm input[name='user_id']").val(userId);

            // Load the preview content from the controller
            $("#plan-preview-body").html('<div class="text-center">Loading preview...</div>');
            fetch("{{ route('plans.preview', ':id') }}".replace(':id', planId) + "?user_id=" + userId)
            .then(res => res.text())
                .then(html => {
                    console.log(html);
                    $("#plan-preview-body").html(html);
                    $("#planPreviewModal").modal("show"); // ✅ show modal
                    console.log('modal show');
                })
                .catch(err => {
                    $("#plan-preview-body").html('<div class="text-danger">Error loading preview</div>');
                });
        });
    });

    $(document).ready(function () {
        let chartInstance = null; // To hold the chart instance
        // Open Weight Modal and Prefill Data
        $('#weight-tracking').on('click', function(e) {
            let userId = $('#userId').val(); // Get the user ID
            let date = new Date().toISOString().slice(0, 10); // Default to today's date
            $('#WeightModal').modal('show');

            $.ajax({
                url: "{{ route('front.fetch.weight.data') }}", // Endpoint to fetch existing data
                method: 'GET',
                data: { user_id: userId },
                success: function (response) {
                    let data = response.latest_weight_tracking
                    let weightString = response.current_weight.trim();
                    // Check raw value and individual characters
                    let cleaned = weightString.replace(/"/g, ''); // Remove double quotes
                    let weight = parseFloat(cleaned);

                    // Prefill fields with fetched data or set defaults
                    $('#weight').val(data?.weight || weight);
                
                    $('#weightGoal').val(data?.weight_goal || ''); // Prefill weight goal
                    $('#date').val(data?.date || date); // Prefill date or default to today
                },
                error: function () {
                    alert('Error fetching existing data');
                }
            });
        });

        // Save or Update Weight
        $('#saveWeight').on('click', function (e) {
            e.preventDefault();

            let weight = $('#weight').val();
            let weightGoal = $('#weightGoal').val();
            let date = $('#date').val();
            let userId = $('#userId').val();

            $.ajax({
                url: "{{ route('front.save.weight') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    weight: weight,
                    weight_goal: weightGoal,
                    date: date,
                    user_id: userId
                },
                success: function (response) {
                    if (response.success) {
                        $('#WeightModal').modal('hide');
                        $('#WeightGraphModal').modal('show');
                        loadChart('1W', userId); // Default to 3 months
                    }
                },
                error: function (xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });
    

        // Load chart data
        function loadChart(filter, userId) {
            $.ajax({
                url: "{{ route('front.fetch.weights') }}",
                method: 'GET',
                data: {
                    filter: filter,
                    user_id: userId
                },
                success: function (data) {
                    renderChart(data);
                    updateModalData(data); // Call the function to update modal values
                },
                error: function () {
                    alert('Error fetching weight data');
                }
            });
        }

        function renderChart(response) {
            // Extract data from the API response
            const weightsData = response.weights;
            const filter = response.filter; // Get the filter value (3M, 6M, 1Y)
            
            // Prepare labels and data for the chart
            const labels = [];
            const dataPointsDate = [];   // For date-based dataset
            const dataPointsWeight = []; // For weight-based dataset
            
            // Function to check if any weight in the month is valid (non-null)
            function hasValidWeight(monthData) {
                return monthData.weights.some(weightEntry => weightEntry.weight !== null);
            }

            if (filter === "3M" || filter === "6M" || filter === "1Y") {
                // For 3M, 6M, 1Y filters, show month labels with corresponding weights directly
                weightsData.forEach(monthData => {
                    const monthName = monthData.month;
                    const isValidMonth = hasValidWeight(monthData); // Check if any date in the month has valid weight

                    let firstValidDateFound = false;  // Track if we have found the first valid date

                    monthData.weights.forEach(weightEntry => {
                        if (isValidMonth) {
                            // For valid months, show the month name on the first valid date, then empty labels
                            if (!firstValidDateFound && weightEntry.weight !== null) {
                                labels.push(monthName); // Label the first valid date with the month name
                                firstValidDateFound = true;  // Ensure we only label the first valid date
                            } else {
                                labels.push("");  // Empty labels for subsequent invalid days
                            }
                        } else {
                            // For invalid months, show the month name on the first date, then empty labels
                            if (!firstValidDateFound) {
                                labels.push(monthName); // Label the first day with the month name
                                firstValidDateFound = true;  // Ensure only the first day gets the month label
                            } else {
                                labels.push(""); // Empty labels for all subsequent days in the month
                            }
                        }

                        dataPointsDate.push({
                            x: weightEntry.date, // Use the actual date for x-axis (Date dataset)
                            y: weightEntry.weight // Weight as the y value for the Date dataset
                        });
                        // console.log('weightEntry.date:', weightEntry.date);

                        dataPointsWeight.push({
                            x: weightEntry.date, // Use the same date for the weight dataset
                            y: weightEntry.weight // Actual weight for the y value (Weight dataset)
                        });
                    });
                });
            } else {
                // For 1W, 2W, 1M filters, show each date with the corresponding weight directly
                weightsData.forEach(monthData => {
                    monthData.weights.forEach(weightEntry => {
                        labels.push(weightEntry.date); // Use the actual date for the label
                        dataPointsDate.push({
                            x: weightEntry.date, // Date for the x-axis
                            y: weightEntry.weight // Weight for the y-axis (Date dataset)
                        });
                        dataPointsWeight.push({
                            x: weightEntry.date, // Use the same date for weight dataset
                            y: weightEntry.weight // Weight for the y-axis (Weight dataset)
                        });
                    });
                });
            }

            const ctx = document.getElementById('line-chart').getContext('2d');

            // Destroy existing chart instance, if any
            if (window.chartInstance) {
                window.chartInstance.destroy();
            }
            // Create new chart instance
            window.chartInstance = new Chart(ctx, {
                type: 'line', // Line chart type
                data: {
                    labels: labels, // X-axis labels (either dates or months)
                    datasets: [
                        {
                            label: 'Date', // Dataset label for Weight Progress
                            data: dataPointsWeight.map(dp => dp.x), // X values for the Weight dataset (dates)
                            borderColor: '#649ef7', // Red color for the line
                            backgroundColor: '#fff', // Light red fill
                            fill: true,
                            // tension: 0.4, // Smooth curves
                            pointRadius: 3, // Highlight points
                            pointBackgroundColor: '#fff'
                        },
                        {
                            label: 'Weight Progress', // Dataset label for Weight Progress
                            data: dataPointsWeight.map(dp => dp.y), // Y values for the Weight dataset
                            borderColor: '#649ef7', // Red color for the line
                            backgroundColor: '#fff', // Light red fill
                            fill: true,
                            // tension: 0.4, // Smooth curves
                            pointRadius: 3, // Highlight points
                            pointBackgroundColor: '#fff'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false, // Hide the legend entirely
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                // Customizing the tooltip content to show both date and weight
                                title: function(tooltipItem) {
                                    const tooltipData = tooltipItem[0]; // Ensure tooltipItem[0] exists
                                    if (tooltipData && tooltipData.parsed) {
                                        console.log(dataPointsDate[tooltipData.parsed.x]);
                                        const date = dataPointsDate[tooltipData.parsed.x] ? dataPointsDate[tooltipData.parsed.x].x : 'Unknown Date'; // 
                                        // Get the date using the index from dataPointsDate
                                        return `Date: ${date}`; 
                                    }
                                    // return 'No Date';  // Fallback if no data is found
                                },
                                label: function(tooltipItem) {
                                    console.log(tooltipItem);
                                    // const tooltipData = tooltipItem[0]; // Ensure tooltipItem[0] exists
                                    if (tooltipItem && tooltipItem.parsed) {
                                        return `Weight: ${tooltipItem.parsed.y} kg`; // Accessing the parsed y value (weight)
                                    }
                                    return 'No Weight';  // Fallback if no data is found
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'category', // Ensure we use category scale for x-axis
                            title: {
                                display: true,
                                text: filter === "1W" || filter === "2W" || filter === "1M" ? 'Date' : 'Month' // X-axis title
                            },
                            ticks: {
                                autoSkip: false, // Prevent Chart.js from auto-skipping labels
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Weight (kg)' // Y-axis title
                            },
                            beginAtZero: false // Start near minimum weight
                        }
                    }
                }
            });
        }

        // Update modal with start-weight, goal-weight and weight-diff
        function updateModalData(data) {
            // Set start-weight, goal-weight, and weight-diff values in the modal
            $('#start-weight').text(data.start_weight + ' KG');
            $('#weight-goal').text(data.goal_weight + ' KG');
            $('#weight-diff').text(data.weight_diff.toFixed(1) + ' KG');

            // Set the color of weight-diff based on the value (positive or negative)
            if (data.weight_diff > 0) {
                $('#weight-diff').addClass('text-success').removeClass('text-danger'); // Positive diff (weight gained)
            } else {
                $('#weight-diff').addClass('text-danger').removeClass('text-success'); // Negative diff (weight lost)
            }
        }

        // Filter click event
        $('.weight-filter ul li a').on('click', function (e) {
            e.preventDefault();
            let filter = $(this).text();
            let userId = $('#userId').val();

            loadChart(filter, userId);
            $('.weight-filter ul li a').removeClass('active');
            $(this).addClass('active');
        });
    
    });

    $(document).ready(function() {
        var stripe = Stripe('pk_test_51QI09cHWqn47bqTGYhGZIsiPSerWujjQgoHf4g0JwygrNt1OMC3RtEnMIjiEWbc8hiaN4umn4TD5zB8sBQEqcjzY0071a4RbUv');
        // var stripe = Stripe('pk_live_51Pfz1YLSisFoEruHvHpdQQZLynQoR3x6BDuBgpb84zTK3EnTlROWMjxVpZhrp1rLmaqCJbusOUNHUoTKBLK7CXru00CkS5tVbt');
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
        $('body').on('click', '.buy-plan-btn', function () {
            // alert('Payment button clicked');
            // e.preventDefault();

            var planId = $(this).data('plan-id');  // Get the plan ID
            var price = $(this).data('plan-price');     // Get the plan price (if needed)
            
            // Update modal title with plan name (optional)
            $('#purchaseModalLabel').text('Purchase ' + $(this).data('plan-name'));

            // Show the modal
            $('#purchaseModal').modal('show');

            // Handle the form submission
            $('#payment-form').submit(function(event) {
                event.preventDefault();

                // Disable the submit button to prevent multiple clicks
                $('#submit').prop('disabled', true);

                // Create a PaymentMethod with Stripe's API
                let discountCode = $('#promo-code').val();
                let discount = $('#discount').val();
                if(discount == 100.00) {
                    $.ajax({
                        url: '{{ route("process.payment") }}',
                        method: 'POST',
                        data: {
                            plan_id: planId,
                            price: price,
                            name: $('#name').val(),
                            email: $('#email').val(),
                            phone: $('#phone').val(),
                            coupon_code: discountCode,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                // Close the modal
                                $('#purchaseModal').modal('hide');

                                var user_id = response.data.user_id;
                                var payment_id = response.data.payment_id;

                                // Redirect the user if a URL is provided
                                if (response.redirect_url) {
                                    var redirectUrlWithUserId = response.redirect_url + '?id=' + payment_id + '&user_id=' + user_id;
                                    setTimeout(function () {
                                        window.location.href = redirectUrlWithUserId;
                                    }, 3000);
                                }
                            } else {
                                // Show error message for failed payment
                                alert('Payment failed: ' + response.message);
                                $('#submit').prop('disabled', false);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Payment error:', error);
                            alert('An error occurred while processing the payment.');
                            $('#submit').prop('disabled', false);
                        }
                    });
                }else {
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
                                    coupon_code: dicountCode,
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
                }
            });
            
            document.getElementById('apply-promo-code').addEventListener('click', function () {
                const promoCode = document.getElementById('promo-code').value.trim();

                if (promoCode === '') {
                    // Show error if promo code is empty
                    document.getElementById('promo-message').textContent = 'Please enter a coupon code.';
                    document.getElementById('promo-message').classList.add('text-danger');
                    document.getElementById('promo-message').classList.remove('text-success');
                    return;
                }

                // AJAX request to validate promo code
                fetch('{{ route("validate.coupon.code") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code: promoCode, plan_id: planId })
                })
                .then(response => response.json())
                .then(data => {
                    let msg = ''; // Declare the message variable outside the conditional blocks

                    if (data.valid) {
                        if(data.type == 'percentage') {
                            msg = `Coupon code applied! ${data.discount}% discount.`;
                            if(data.discount === "100.00" || data.discount == 100.00) {
                                $('#discount').val(data.discount);
                                $('#payment-details').hide();
                            }
                        }else {
                            msg = `Coupon code applied! $${data.discount} discount.`;
                        }
                        $('#discount').val(data.discount);
                        // Promo code is valid
                        document.getElementById('promo-message').textContent = msg;
                        document.getElementById('promo-message').classList.add('text-success');
                        document.getElementById('promo-message').classList.remove('text-danger');
                    } else {
                        // Promo code is invalid or expired
                        document.getElementById('promo-message').textContent = data.message;
                        document.getElementById('promo-message').classList.add('text-danger');
                        document.getElementById('promo-message').classList.remove('text-success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('promo-message').textContent = 'An error occurred. Please try again.';
                    document.getElementById('promo-message').classList.add('text-danger');
                    document.getElementById('promo-message').classList.remove('text-success');
                });
            });
        });
    });

    $(document).ready(function() {
        // Open the edit modal and populate fields when an edit button is clicked
        $('.edit-details').on('click', function() {
            let formName = $(this).data('form-name');
            let question = $(this).data('question');
            let answer = $(this).data('answer');
            let type = $(this).data('type');
            // Set values in modal
            $('#formName').val(formName);
            $('#formQuestion').val(question);
            $('#question').val(question);
            // $('#answer').val(answer);
            $('#questionText').text(question);
            $('#type').val(type);

            // Show modal
            $("#editModalLabel").text(type === "supplement" ? "Add Supplement" : "Add Medication");

            $('#editModal').modal('show');
        });

        // Handle form submission with AJAX
        $('#editForm').on('submit', function(event) {
            event.preventDefault();
            console.log(userId);
            let formData = {
                form_name: $('#formName').val(),
                question: $('#question').val(),
                answer: $('#answer').val(),
                type: $('#type').val(),
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val(),
                user_id: userId,
                main_ans: $('#mainAns'),
                _token: '{{ csrf_token() }}' // CSRF protection
            };

            $.ajax({
                url: "{{ route('front.sample-plan-details-update') }}", // Laravel route to handle updates
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Updated successfully!');
                        $('#editModal').modal('hide'); // Close modal
                        location.reload(); // Refresh page to reflect changes (or update UI dynamically)
                    } else {
                        alert('Error updating!');
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong!');
                }
            });
        });

        $('.edit-supliment-details').on('click', function () {
            // Set modal title
            $('#editModalLabel').text('Edit Supplements');

            // Get data attributes from button
            const formName = $(this).data('form-name');
            const question = $(this).data('question');
            const type = $(this).data('type');
            const answer = $(this).data('answer');
            const startDate = $(this).data('startdate');
            const endDate = $(this).data('enddate');

            // Set values in modal
            $('#formName').val(formName);
            $('#formQuestion').val(question);
            $('#type').val(type);
            $('#mainAns').val(answer);

            $('#questionText').text(question);
            $('#question').val(question);

            $('#answer').val(answer);
            $('#start_date').val(formatDateForInput(startDate));
            $('#end_date').val(formatDateForInput(endDate));
        });

        // Helper function: converts DD-MM-YYYY → YYYY-MM-DD
        function formatDateForInput(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('-'); // ["24", "04", "2025"]
            if (parts.length !== 3) return '';
            return `${parts[2]}-${parts[1]}-${parts[0]}`; // "2025-04-24"
        }
        
        $('.add-sport').on('click', function() {
            $('#editSportModal').modal('show');
        });

        document.getElementById('sportImageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('sportImagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        $(".add-goal").click(function () {
            let type = $(this).attr("data-type");
            $("#itemType").val(type);
            $("#editItemTitle").text(type === "goal" ? "Add Goal" : "Add Challenge");
            $("#itemLabel").text(type === "goal" ? "New Goal" : "New Challenge");
            $("#addGoalModal").modal("show");
        });

        // Update Goal or Challenge via AJAX
        $("#saveGoal").click(function () {
            let type = $("#itemType").val();
            let answer = $("#itemInput").val();

            $.ajax({
                url: "{{ route('front.update.goal') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    answer: answer,
                    user_id: userId
                },
                success: function (response) {
                    if (response.success) {
                        alert(type.charAt(0).toUpperCase() + type.slice(1) + " updated successfully!");
                        location.reload();
                    }
                },
                error: function () {
                    alert("Something went wrong! Please try again.");
                }
            });
        });

        // View Past Goals or Challenges via AJAX
        $(".view-past-goals").click(function () {
            let type = $(this).attr("data-type");
            $.ajax({
                url: "{{ route('front.past.goals') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    user_id: userId
                },
                success: function (data) {
                    let modalTitle = type === "goal" ? "Past Goals" : "Past Challenges";
                    $("#viewPastItemsModalLabel").text(modalTitle);

                    let pastList = $("#pastItemsList");
                    pastList.html(""); // Clear existing list

                    if (data.length > 0) {
                        $.each(data, function (index, item) {
                            pastList.append("<li>" + item.answer + " <small>(Added on: " + new Date(item.created_at).toLocaleDateString() + ")</small></li>");
                        });
                    } else {
                        pastList.append("<li>No past records found.</li>");
                    }

                    $("#viewPastItemsModal").modal("show"); // Show modal with past data
                },
                error: function () {
                    alert("Error fetching past " + type + "s!");
                }
            });
        });

        $(".view-past-history").click(function () {
            let type = $(this).attr("data-type");
            console.log(type);

            $.ajax({
                url: "{{ route('front.past.goals') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    user_id: userId
                },
                success: function (data) {
                    let modalTitle = type === "supplement" ? "Past Supplements" : "Past Medications";
                    $("#viewPastItemsModalLabel").text(modalTitle);

                    let pastList = $("#pastItemsList");
                    pastList.html(""); // Clear existing list

                    if (data.length > 0) {
                        $.each(data, function (index, item) {
                            pastList.append("<li>" + item.answer + " <small>(Added on: " + new Date(item.created_at).toLocaleDateString() + ")</small></li>");
                        });
                    } else {
                        pastList.append("<li>No past records found.</li>");
                    }

                    $("#viewPastItemsModal").modal("show"); // Show modal with past data
                },
                error: function () {
                    alert("Error fetching past " + type + "s!");
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('trainingChart').getContext('2d');

        let response = @json(isset($trainingIntencity[0]) && !empty($trainingIntencity[0]) ? $trainingIntencity[0] : null);
        console.log(response);

        // X-axis: frequency groups
        const daysLabels = ["1-2", "3-4", "5+"];

        // Mapping range to max day
        const dayHeights = {
            "1-2": 2,
            "3-4": 4,
            "5+": 5
        };

        const intensityLabels = ["Low intensity", "Moderate intensity", "High intensity"];

        const colors = {
            "Low intensity": "rgba(75, 192, 192, 0.6)",
            "Moderate intensity": "rgba(255, 159, 64, 0.6)",
            "High intensity": "rgba(255, 99, 132, 0.6)"
        };

        const borderColors = {
            "Low intensity": "rgba(75, 192, 192, 1)",
            "Moderate intensity": "rgba(255, 159, 64, 1)",
            "High intensity": "rgba(255, 99, 132, 1)"
        };

        let datasets = intensityLabels.map((intensity, index) => {
            return {
                label: intensity,
                data: daysLabels.map(label => response[label]?.includes(intensity) ? dayHeights[label] : 0),
                backgroundColor: colors[intensity],
                borderColor: borderColors[intensity],
                borderWidth: 1,
                barPercentage: 0.8,
                categoryPercentage: 0.8
            };
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: daysLabels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: { enabled: true },
                    legend: { display: true }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Training Frequency (Days per Week)'
                        },
                        stacked: false // Show bars side-by-side
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Days'
                        },
                        stacked: false,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        suggestedMax: 6
                    }
                }
            }
        });
    });

    var reportsData = @json($reports);
    // Define the previewImage function
    function previewImage(fileUrl) {
        window.open(fileUrl, '_blank');
    }

    $(document).ready(function () {
        $(".report-link").click(function (e) {
            e.preventDefault();

            var reportType = $(this).data("report-type"); // Get report type (medical_history or physical_measures)
            $("#reportModalLabel").text($(this).text());  // Set modal title dynamically
            $("#existingReports").html("<p>Loading...</p>"); // Show loading text

            // Use the reportsData variable instead of an AJAX call
            displayReports(reportsData, reportType);

            $("#reportModal").modal("show"); // Open modal
        });
        
        // Function to Display Reports
        function displayReports(reportsData, reportType) {
            var reports = reportsData[reportType];
            var html = `
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Report Name</th>
                            <th>Date</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            if (!reports || reports.length === 0) {
                html += "<tr><td colspan='5' class='text-center'>No reports available.</td></tr>";
            } else {
                reports.forEach(function (file, index) {
                    if (!file || !file.file_path) return; // Safeguard against undefined entries

                    let fileUrl = file.file_path;
                    let fileTypeIcon = '';

                    if (fileUrl.toLowerCase().endsWith(".png") || 
                        fileUrl.toLowerCase().endsWith(".jpg") || 
                        fileUrl.toLowerCase().endsWith(".jpeg")) {
                        fileTypeIcon = `<img src="${fileUrl}" class="img-thumbnail" width="100" onclick="previewImage('${fileUrl}')">`;
                    } else if (fileUrl.toLowerCase().endsWith(".pdf")) {
                        fileTypeIcon = `<i class="fa-solid fa-file-pdf text-danger" style="font-size: 1.5rem;"></i>`;
                    } else {
                        fileTypeIcon = `<i class="fa-solid fa-file text-primary" style="font-size: 1.5rem;"></i>`;
                    }

                    html += `
                        <tr data-filename='${file.report_name}'>
                            <td>${index + 1}</td>
                            <td>${file.report_name || 'N/A'}</td>
                            <td>${file.date || 'N/A'}</td>
                            <td>${fileTypeIcon}</td>
                            <td>
                                ${fileUrl.toLowerCase().endsWith(".png") || 
                                fileUrl.toLowerCase().endsWith(".jpg") || 
                                fileUrl.toLowerCase().endsWith(".jpeg")
                                    ? `<button class="btn btn-primary py-2 px-3" onclick="previewImage('${fileUrl}')">
                                        <i class="fa-solid fa-eye text-white"></i>
                                    </button>`
                                    : `<a href="${fileUrl}" download class="btn btn-secondary py-2 px-3">
                                        <i class="fa-solid fa-download"></i>
                                    </a>`
                                }
                                <button class="btn btn-danger py-2 px-3" onclick="deleteFile('${file.report_name}')">
                                    <i class="fa-solid fa-trash text-white"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            html += "</tbody></table>";
            $("#report_type").val(reportType);
            $("#existingReports").html(html);
        }


        $("#uploadBtn").click(function () {
            var files = $("#fileUpload")[0].files; // Get all selected files
            var formData = new FormData();
            var reportType = $('#report_type').val();
            var reportName = $('#file_name').val();
            console.log(reportType);
            if (files.length === 0) {
                alert("Please select at least one file to upload.");
                return;
            }

            for (var i = 0; i < files.length; i++) {
                formData.append("file[]", files[i]); // Append each file to FormData
            }

            formData.append("report_name", reportName);
            formData.append("report_type", reportType);
            formData.append("_token", "{{ csrf_token() }}");
            formData.append("user_pre_plan_id", userPrePlan.id);

            $.ajax({
                url: "{{ route('front.upload.report') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert("Files uploaded successfully!");
                    $("#fileUpload").val(""); // Clear file input
                    $("#report_type").val(""); // Clear file input
                    $('#file_name').val('');
                    $("#reportModal").modal("hide");
                    // displayReports(response, reportType); // Refresh the report list dynamically
                    window.location.reload(); // Reload page to show uploaded files
                },
                error: function () {
                    alert("Error uploading files.");
                }
            });
        });
    }); 

    // Function to handle file deletion
    function deleteFile(fileName) {
        if (confirm("Are you sure you want to delete this file?")) {
            // Perform AJAX request to delete file from the server
            $.ajax({
                url: "{{ route('front.delete.report') }}", // Adjust this route to your controller
                method: "POST",
                data: {
                    file: fileName,
                    _token: "{{ csrf_token() }}", // CSRF token for security
                },
                success: function (response) {
                    if (response.success) {
                        alert("File deleted successfully!");
                        // Remove the deleted file's row from the table
                        $("tr[data-filename='" + fileName + "']").remove();
                    } else {
                        alert("Error deleting file.");
                    }
                },
                error: function () {
                    alert("Error deleting file.");
                }
            });
        }
    }
</script>
@endsection