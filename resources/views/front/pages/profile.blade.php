@extends(frontView('layouts.app'))

@section('title', 'Profile Page')

@section('content')
<style>
    /* Remove border from accordion when opened */
    .accordion-button {
        color: inherit !important; /* Prevent orange color */
        background-color: #fff !important; /* Keep white background */
        box-shadow: none !important; /* Remove any shadow */
        border: none !important; /* Remove border */
    }

    /* Keep title styling consistent on open/close */
    .accordion-button:not(.collapsed) {
        color: inherit !important;
        background-color: #fff !important;
    }

    /* Optional: Remove blue border highlight on focus */
    .accordion-button:focus {
        box-shadow: none !important;
        border: none !important;
    }
    /* Blur the rest of the page when the modal is open */
    .blur-background {
        filter: blur(5px); /* Adjust the blur value */
        transition: filter 0.3s ease-in-out;
    }
    .coupon-link           { text-decoration:none; cursor:pointer; color:#000; text-decoration:underline;}
    .coupon-link.active    { color:#000; text-decoration:underline; }

    /* Simple fix to prevent page scroll on link click */
    #weight-tracking {
        cursor: pointer;
    }
    /* Let Bootstrap handle modal positioning naturally */
    .modal {
        z-index: 1055;
    }
    .modal-backdrop {
        z-index: 1050;
    }
</style>
    <div class="nutrition-plan-hero bg-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="nutrition-plan-text">
                        <h1>We Take Care Of Your <span class="text-primary">Health</span></h1>
                        <p>Fuel your performance with daily nutrition that works. Get expert advice and customised plans - without the guess work.</p>
                        <!-- <a href="#" class="btn btn-primary">
                            <span class="me-1">Pre Plan Details</span>
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>
                                <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a> -->
                        @php
                            $Plan = $plans->first();
                            $userPlan = \App\Models\UserPlan::where('user_id', $user->id)->where('plan_id', $Plan->id)->where('status', 'active')->first();
                            $isMailSend = 0;
                            if($userPlan){
                                $isMailSend = $userPlan->is_mail_sent ?? 0;
                            }
                        @endphp
                        @if($profileSetUp == 0)
                            <a href="{{ route('front.pre-plan-details') }}?id={{ $payment->id }}&user_id={{ $user->id }}"
                            class="btn btn-danger btn-outline-danger mt-3 px-3 text-white"
                            >
                            Complete Your Profile
                            </a> 
                            <p class="mt-3 text-danger">* Finish Questionnaire to Continue</p>
                        @elseif(($profileSetUp == 1 || $profileSetUp == 0) && $user->email === 'zachtennis7@icloud.com')
                            <a href="{{ route('front.pre-plan-details') }}?id={{ $payment->id }}&user_id={{ $user->id }}"
                            class="btn btn-danger btn-outline-danger mt-3 px-3 text-white @if($isMailSend) d-none @endif"
                            >
                            Complete Your Profile
                            </a> 
                            <p class="mt-3 text-danger @if($isMailSend) d-none @endif">* Finish Questionnaire to Continue</p>

                        @endif
                        @if(isset($user->email) && $user->email === 'chloecovell2010@gmail.com')
                            <a href="{{ route('front.overseas_travel_nutrition_plan') }}" target="_blank" class="btn btn-primary mt-3 mx-2">
                                <span class="me-1">Overseas Travel Nutrition Plan</span>
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"></path>
                                    <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        @endif
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
                            <figure style="">
                                <img src="{!! frontAssets('images/nutrition-supplements-1.jpg') !!}" alt="images/nutrition-supplements.jpg" alt="">
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
                                    <img src="{{ webAssets($user->profile_image) }}">
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
                            <h4 class="mb-3 text-center">Profile</h4>
                            <div class="card profile-box h-100 border-0 shadow-sm">
                                <figure>
                                    <!-- <img src="{{ asset('private/public/front/images/athlete-sport.jpg') }}" alt=""> -->
                                    @if(isset($user->profile_image))
                                    <img src="{{ webAssets($user->profile_image) ?? frontAssets('images/profile-image.jpeg') }}" alt="Profile Image">
                                    @else
                                    <img src="{{ frontAssets('images/profile-image.jpeg') }}" alt="Profile Image">
                                    @endif
                                </figure>
                                <button class="btn btn-light edit-icon edit-profile-image" data-bs-target="#editImageModal"
                                    data-form-name="profile_image" data-question="Profile Image" data-answer="{{ webAssets('storage/' . (isset($profileDetails) ? $profileDetails['Profile Image'] : '')) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <div class="card border-0 shadow-none">
                                    <div class="card-body nutrition-profile-info">
                                        <h4 class="text-center">
                                            <span class="mx-auto">{{ $profileDetails['Name'] ?? 'Nill' }}</span>
                                            <button class="btn btn-light edit-icon" id="editNameButton"
                                                data-form-name="profile_name" data-question="Name" data-answer="{{ $profileDetails['Name'] ?? 'Nill' }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </h4>

                                        <ul class="mb-3">
                                            <li>Sport: {{ $profileDetails['Sport'] }}
                                            </li>
                                        </ul>

                                        <a data-bs-toggle="collapse" href="#profileDetailsCollapse" role="button" aria-expanded="false" aria-controls="profileDetailsCollapse" class="text-decoration-none mt-3"> Health Data : 
                                        </a>
                                        <!-- Collapsible section -->
                                        <div class="collapse" id="profileDetailsCollapse">
                                            <ul>
                                                <li>
                                                    Weight: {{ !empty($profileDetails['Current body weight (kg) (if known):']) ? $profileDetails['Current body weight (kg) (if known):'] : 'N/A' }} kg
                                                </li>
                                                <li><a href="#" class="text-decoration-underline" id="weight-tracking">Track Your Weight</a></li>
                                                <li>
                                                    Height: {{ !empty($profileDetails['Height (cm):']) ? $profileDetails['Height (cm):'] : 'N/A' }} cm
                                                    <button class="btn btn-light edit-icon" id="editHeightButton"
                                                        data-type="physical_measures" data-form-name="physical_measures"
                                                        data-question="Height (cm):" data-answer="{{ $profileDetails['Height (cm):'] ?? 'Nill' }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </li>
                                                <li>
                                                    <a href="#" class="text-decoration-underline report-link" id="blood-report" data-report-type="medical_history">Blood Report</a>
                                                </li>
                                                <li>
                                                    <a href="#" class="text-decoration-underline report-link" id="body-composition-report" data-report-type="physical_measures">Body Composition Report</a>
                                                </li>
                                            </ul>
                                        </div>
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
                                       
                                        <li><strong>Nutrition Goals:</strong> {{ $nutritionGoalsDetails['Which of these do you want help with?'] ?? 'Nill' }}
                                        <div class="btn-list">
                                            <button class="btn btn-light edit-icon add-goal" title="Add Goal" data-type="goal"
                                                data-form-name="nutrition_goals" data-question="Which of the following nutrition related goals are you interested in working on?" data-answer="{{ $nutritionGoalsDetails['Which of these do you want help with?'] ?? 'Nill' }}"
                                                onclick="openAddGoalModal('goal')">
                                                <i class="fas fa-plus"></i>
                                            </button>

                                            <button class="btn btn-light edit-icon view-past-goals" title="View Past Goals" data-type="goal"
                                                data-form-name="nutrition_goals" data-question="Which of these do you want help with?" data-answer="{{ $nutritionGoalsDetails['Which of these do you want help with?'] ?? 'Nill' }}"
                                                onclick="openViewPastGoalsModal('goal')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            </div>
                                        </li>
                                      
                                        <li><strong>Nutrition Challenge:</strong> {{ $nutritionGoalsDetails["What's your biggest nutrition challenge?"] ?? 'Nill' }}
                                        <div class="btn-list">
                                            <button class="btn btn-light edit-icon add-goal" title="Add Challenge" data-type="challenge"
                                                data-form-name="nutrition_goals" data-question="What's your biggest nutrition challenge?" data-answer="{{ $nutritionGoalsDetails["What's your biggest nutrition challenge?"] ?? 'Nill' }}"
                                                onclick="openAddGoalModal('challenge')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button class="btn btn-light edit-icon view-past-goals" title="View Past Challenges" data-type="challenge"
                                                data-form-name="nutrition_goals" data-question="What's your biggest nutrition challenge?" data-answer="{{ $nutritionGoalsDetails["What's your biggest nutrition challenge?"] ?? 'Nill' }}"
                                                onclick="openViewPastGoalsModal('challenge')">
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
                                                $vitaminStartDateRaw = $vitaminDetails['start_date'] ?? '';
                                                $vitaminEndDateRaw = $vitaminDetails['end_date'] ?? '';
                                                $vitaminStartDates = array_map('trim', explode(',', $vitaminStartDateRaw));
                                                $vitaminEndDates = array_map('trim', explode(',', $vitaminEndDateRaw));
                                                $formatDate = fn($date, $fallback = null) => $date && strtolower($date) !== 'null'
                                                                ? \Carbon\Carbon::parse($date)->format('d-m-Y')
                                                                : $fallback;    
                                                $supplements = $vitaminAnswer ? array_map('trim', explode(',', $vitaminAnswer)) : [];
                                                $supplementCount = count($supplements);

                                                $vitaminStartDates = array_map('trim', explode(',', $vitaminStartDateRaw));
                                                $vitaminEndDates = array_map('trim', explode(',', $vitaminEndDateRaw));

                                                // If only one date is provided, apply it to all supplements
                                                if (count($vitaminStartDates) === 1 && $supplementCount > 1) {
                                                    $vitaminStartDates = array_fill(0, $supplementCount, $vitaminStartDates[0]);
                                                }
                                                if (count($vitaminEndDates) === 1 && $supplementCount > 1) {
                                                    $vitaminEndDates = array_fill(0, $supplementCount, $vitaminEndDates[0]);
                                                }

                                                // Separate current and past supplements
                                                $currentSupplements = $pastSupplements = $currentSupplementDates = $pastSupplementDates = [];
                                                
                                                foreach ($supplements as $index => $item) {
                                                    $endDate = $vitaminEndDates[$index] ?? null;
                                                    $startDate = $vitaminStartDates[$index] ?? null;
                                                    
                                                    // Only check for past if end date exists and is not null
                                                    if ($endDate && strtolower($endDate) !== 'null' && !empty(trim($endDate))) {
                                                        try {
                                                            $endDateCarbon = \Carbon\Carbon::parse($endDate);
                                                            $today = \Carbon\Carbon::today();
                                                            
                                                            if ($endDateCarbon->lt($today)) {
                                                                // Past supplement - end date is less than today
                                                                $pastSupplements[] = $item;
                                                                $pastSupplementDates[] = [
                                                                    'start' => $startDate,
                                                                    'end' => $endDate
                                                                ];
                                                            } else {
                                                                // Current supplement - end date is in future
                                                                $currentSupplements[] = $item;
                                                                $currentSupplementDates[] = [
                                                                    'start' => $startDate,
                                                                    'end' => $endDate
                                                                ];
                                                            }
                                                        } catch (\Exception $e) {
                                                            // If date parsing fails, treat as current
                                                            $currentSupplements[] = $item;
                                                            $currentSupplementDates[] = [
                                                                'start' => $startDate,
                                                                'end' => $endDate
                                                            ];
                                                        }
                                                    } else {
                                                        // No end date or empty/null - always keep as current
                                                        $currentSupplements[] = $item;
                                                        $currentSupplementDates[] = [
                                                            'start' => $startDate,
                                                            'end' => $endDate
                                                        ];
                                                    }
                                                }

                                                $medicationDetails = $intakeDetails['Provide details of any prescription medications (if taking any):'] ?? null;
                                                $medicationAnswer = $medicationDetails['answer'] ?? null;
                                                $medicationStartDateRaw = $medicationDetails['start_date'] ?? '';
                                                $medicationEndDateRaw = $medicationDetails['end_date'] ?? '';

                                                $medicationStartDates = array_map('trim', explode(',', $medicationStartDateRaw));
                                                $medicationEndDates = array_map('trim', explode(',', $medicationEndDateRaw));

                                                $formatDate = fn($date, $fallback = null) => $date && strtolower($date) !== 'null'
                                                    ? \Carbon\Carbon::parse($date)->format('d-m-Y')
                                                    : $fallback;

                                                $medications = $medicationAnswer ? array_map('trim', explode(',', $medicationAnswer)) : [];
                                                $medicationCount = count($medications);

                                                // If only one date is provided, apply it to all medications
                                                if (count($medicationStartDates) === 1 && $medicationCount > 1) {
                                                    $medicationStartDates = array_fill(0, $medicationCount, $medicationStartDates[0]);
                                                }
                                                if (count($medicationEndDates) === 1 && $medicationCount > 1) {
                                                    $medicationEndDates = array_fill(0, $medicationCount, $medicationEndDates[0]);
                                                }

                                                // Separate current and past medications
                                                $currentMedications = $pastMedications = $currentMedicationDates = $pastMedicationDates = [];
                                                
                                                foreach ($medications as $index => $item) {
                                                    $endDate = $medicationEndDates[$index] ?? null;
                                                    $startDate = $medicationStartDates[$index] ?? null;
                                                    
                                                    // Only check for past if end date exists and is not null
                                                    if ($endDate && strtolower($endDate) !== 'null' && !empty(trim($endDate))) {
                                                        try {
                                                            $endDateCarbon = \Carbon\Carbon::parse($endDate);
                                                            $today = \Carbon\Carbon::today();
                                                            
                                                            if ($endDateCarbon->lt($today)) {
                                                                // Past medication - end date is less than today
                                                                $pastMedications[] = $item;
                                                                $pastMedicationDates[] = [
                                                                    'start' => $startDate,
                                                                    'end' => $endDate
                                                                ];
                                                            } else {
                                                                // Current medication - end date is in future
                                                                $currentMedications[] = $item;
                                                                $currentMedicationDates[] = [
                                                                    'start' => $startDate,
                                                                    'end' => $endDate
                                                                ];
                                                            }
                                                        } catch (\Exception $e) {
                                                            // If date parsing fails, treat as current
                                                            $currentMedications[] = $item;
                                                            $currentMedicationDates[] = [
                                                                'start' => $startDate,
                                                                'end' => $endDate
                                                            ];
                                                        }
                                                    } else {
                                                        // No end date or empty/null - always keep as current
                                                        $currentMedications[] = $item;
                                                        $currentMedicationDates[] = [
                                                            'start' => $startDate,
                                                            'end' => $endDate
                                                        ];
                                                    }
                                                }
                                            @endphp

                                            <strong>Supplements:</strong>

                                            @if (!empty($currentSupplements))
                                                <ul class="ps-3 mt-3">
                                                    @foreach ($currentSupplements as $index => $item)
                                                        @php
                                                            $startDate = $currentSupplementDates[$index]['start'] ?? null;
                                                            $endDate = $currentSupplementDates[$index]['end'] ?? null;

                                                            $formattedStart = $formatDate($startDate, null);
                                                            $formattedEnd = $formatDate($endDate, null);
                                                            $item = trim($item);
                                                        @endphp
                                                        <li class="d-flex justify-content-between align-items-start @if ($formattedStart) mb-1 @else mb-3 @endif">
                                                            <span>{{ $item }}</span>
                                                            <div class="btn-list ms-2 mt-1">
                                                                <button class="btn btn-sm btn-light edit-icon edit-supliment-details" data-bs-toggle="modal" data-bs-target="#supplementEditModal"
                                                                    data-form-name="medical_history"
                                                                    data-question="List any dietary vitamins or supplements you are currently taking (if any):"
                                                                    data-answer="{{ $item }}"
                                                                    data-main-ans="{{ $vitaminAnswer }}"
                                                                    data-type="supplement-edit"
                                                                    data-startdate="{{ $formattedStart }}"
                                                                    data-enddate="{{ $formattedEnd }}" >
                                                                    <i class="fas fa-pen"></i>
                                                                </button>
                                                            </div>
                                                        </li>
                                                        @if ($formattedStart)
                                                            <small class="text-muted">
                                                                Start: {{ $formattedStart }}
                                                                @if ($formattedEnd)
                                                                    to End: {{ $formattedEnd }}
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
                                                    data-type="supplement"
                                                    onclick="openViewPastHistoryModal('supplement')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 border-bottom">
                                        <div class="position-relative">
                                        <strong>Medications:</strong>
                                            @if (!empty($currentMedications))
                                                <ul class="ps-3 mt-3">
                                                    @foreach ($currentMedications as $index => $item)
                                                        @php
                                                            $startDate = $currentMedicationDates[$index]['start'] ?? null;
                                                            $endDate = $currentMedicationDates[$index]['end'] ?? null;

                                                            $formattedStart = $formatDate($startDate, null);
                                                            $formattedEnd = $formatDate($endDate, null);
                                                            $item = trim($item);
                                                        @endphp
                                                        <li class="d-flex justify-content-between align-items-start @if ($formattedStart) mb-1 @else mb-3 @endif">
                                                            <span>{{ $item }}</span>
                                                            <div class="btn-list ms-2 mt-1">
                                                                <button class="btn btn-sm btn-light edit-icon edit-supliment-details" data-bs-toggle="modal" data-bs-target="#supplementEditModal"
                                                                    data-form-name="medical_history"
                                                                    data-question="Provide details of any prescription medications (if taking any):"
                                                                    data-answer="{{ $item }}"
                                                                    data-main-ans="{{ $medicationAnswer }}"
                                                                    data-type="medication-edit"
                                                                    data-startdate="{{ $formattedStart }}"
                                                                    data-enddate="{{ $formattedEnd }}">
                                                                    <i class="fas fa-pen"></i>
                                                                </button>
                                                            </div>
                                                        </li>
                                                        @if ($formattedStart)
                                                            <small class="text-muted">
                                                                Start: {{ $formattedStart }}
                                                                @if ($formattedEnd)
                                                                    to End: {{ $formattedEnd }}
                                                                @endif
                                                            </small>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>Nill</p>
                                            @endif

                                            <div class="btn-list">
                                                <button class="btn btn-light edit-icon edit-details" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-form-name="medical_history" data-question="Provide details of any prescription medications (if taking any):" data-answer="{{ $medicationAnswer }}" data-type="medication">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button class="btn btn-light edit-icon view-past-history" title="View Past Medications" 
                                                data-form-name="medical_history" data-question="Provide details of any prescription medications (if taking any):" data-answer="{{ $medicationAnswer }}" data-type="medication"
                                                onclick="openViewPastHistoryModal('medication')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-8 ms-auto">
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="card-header bg-white">
                                <h4 class="mt-2">Nutrition plan</h4>
                            </div>
                            <div class="card-body" id="planDiv">
                                @foreach($plans as $plan)
                                    @if(in_array($plan->id,$purchasedplans))
                                    <?php
                                        $userPlan = \App\Models\UserPlan::where('user_id', $user->id)->where('plan_id', $plan->id)->where('status', 'active')->first();
                                        $isMailSend = 0;
                                        if($userPlan){
                                            $isMailSend = $userPlan->is_mail_sent ?? 0;
                                        }
                                        $paymentMode = \App\Models\Payment::where('user_id', $user->id)->where('plan_id', $plan->id)->first();
                                        $discount = $paymentMode->status == "discount_applied" ?? null;
                                        $successPayment = $paymentMode->status == "succeeded" ?? null;

                                    ?>
                                    <div class="accordion mt-3" id="planAccordionPurchased">
                                        <div class="accordion-item card rounded-3 shadow-none overflow-hidden">
                                            <h2 class="accordion-header" id="headingPurchased{{ $plan->id }}">
                                                <button class="accordion-button collapsed d-flex justify-content-between align-items-center bg-white" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapsePurchased{{ $plan->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="collapsePurchased{{ $plan->id }}">
                                                    <span class="d-flex align-items-center">
                                                        <figure class="title-ico">
                                                        @if(!$isMailSend)
                                                            <svg class="score-lock-ico" version="1.1" x="0px" y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;" xml:space="preserve">
                                                            <g>
                                                                <circle class="st0" cx="400" cy="567.2" r="54.7"></circle>
                                                                <path class="st0" d="M621.2,326.9V219.3c0-120.2-97.8-217.9-217.9-217.9C279.5,1.3,178.8,102,178.8,225.7v101.2c-59.5,1.2-107.3,49.7-107.3,109.5v255.5c0,60.5,49,109.5,109.5,109.5h438c60.5,0,109.5-49,109.5-109.5V436.4C728.5,376.6,680.6,328.1,621.2,326.9z M255.5,225.7c0-81.5,66.3-147.8,147.8-147.8c77.9,0,141.3,63.4,141.3,141.3v104H255.5V225.7z M655.5,691.8c0,20.2-16.3,36.5-36.5,36.5H181c-20.2,0-36.5-16.3-36.5-36.5V436.4c0-20.2,16.3-36.5,36.5-36.5h42.8h352.3H619c20.2,0,36.5,16.3,36.5,36.5V691.8z"></path>
                                                            </g>
                                                            </svg> 
                                                        @else
                                                            <svg class="score-unlock-ico" version="1.1" x="0px" y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;" xml:space="preserve">
                                                            <g>
                                                                <circle cx="400" cy="566.5" r="54.4"></circle>
                                                                <path d="M617.8,327.5H271.9c-7.3-18-14.2-37.7-19.4-58.2c-9.4-37.5-12.3-74.3-3.9-105.5c7.9-29.6,26.4-56.8,65.8-76.5c39.4-19.8,72.2-18.4,100.6-7.1c30.1,12,57.9,36.2,82.3,66.1c5,6.1,9.8,12.4,14.3,18.7c12.7,17.6,37.6,22.4,54.6,8.9c14.4-11.3,18.1-31.7,7.6-46.7c-6.3-9-13.1-18-20.3-26.8C525.2,65.5,487.9,31,441.9,12.7c-47.7-19-102.1-19.5-160.1,9.7c-58,29.1-90.1,73.1-103.3,122.7c-12.8,47.9-7.3,98.4,3.7,142c3.5,13.9,7.7,27.5,12.2,40.4h-12.1c-60.1,0-108.9,48.8-108.9,108.9v254.1c0,60.1,48.8,108.9,108.9,108.9h435.6c60.1,0,108.9-48.8,108.9-108.9V436.4C726.7,376.2,677.9,327.5,617.8,327.5zM654.1,690.5c0,20-16.3,36.3-36.3,36.3H182.2c-20,0-36.3-16.3-36.3-36.3V436.4c0-20,16.3-36.3,36.3-36.3h435.6c20,0,36.3,16.3,36.3,36.3V690.5z"></path>
                                                            </g>
                                                            </svg>
                                                        @endif
                                                        </figure>
                                                        <h5 class="mb-0">{{ $plan->name }}</h5> 
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="collapsePurchased{{ $plan->id }}" class="accordion-collapse collapse" aria-labelledby="headingPurchased{{ $plan->id }}" data-bs-parent="#planAccordionPurchased">
                                                <div class="accordion-body pb-1 pt-2 bg-white" style="color: #000; font-size: 0.9rem;">
                                                    {!! ($plan->description ?? 'No description available.') !!}
                                                </div>
                                            </div>
                                            <div class="card-footer border-top-0 bg-white">
                                                <div class="d-flex flex-wrap">
                                                @if($user->email === 'zachtennis7@icloud.com' && ($profileSetUp == 1 || $profileSetUp == 0))
                                                    <a href="{{ route('front.pre-plan-details') }}?id={{ $payment->id }}&user_id={{ $user->id }}" class="btn btn-danger btn-outline-danger text-white m-2 px-3 {{ $isMailSend == 1 ? 'd-none' : '' }}">
                                                    Complete Your Profile
                                                </a>
                                                @else
                                                <a href="{{ route('front.pre-plan-details') }}?id={{ $payment->id }}&user_id={{ $user->id }}"
                                                class="btn btn-danger btn-outline-danger text-white m-2 px-3 {{ $profileSetUp == 1 ? 'd-none' : '' }}">
                                                    Complete Your Profile
                                                </a>
                                                @endif
                                                @if($discount && !$adminView)
                                                    @if($isMailSend)
                                                        <a href="{{ route('front.plans.details', ['id' => $plan->id, 'user_id' => $user->id]) }}" class="btn btn-primary m-2 "
                                                        data-bs-toggle="tooltip">
                                                        View Plan
                                                        </a>
                                                    @else 
                                                        <a href="javascript:void(0);"
                                                        class="btn btn-white m-2 buy-plan btn-outline-secondary"
                                                        data-user-id="{{ $user->id }}"
                                                        data-plan-id="{{ $plan->id }}"
                                                        data-plan-price="{{ $plan->price }}"
                                                        data-plan-name="{{ $plan->name }}">
                                                            Buy Plan
                                                        </a>
                                                    @endif
                                                @elseif($successPayment && !$adminView)
                                                    @if($isMailSend)
                                                        <a href="{{ route('front.plans.details', ['id' => $plan->id, 'user_id' => $user->id]) }}" class="btn btn-primary m-2 "
                                                        data-bs-toggle="tooltip">
                                                        View Plan
                                                        </a>
                                                    @else
                                                        <a href="{{ route('front.plans.details', ['id' => $plan->id, 'user_id' => $user->id]) }}" class="btn btn-secondary m-2 disabled"
                                                        data-bs-toggle="tooltip" title="Working on your plan :-) Email you when ready."
                                                        style="pointer-events: auto; cursor: not-allowed;">
                                                        View Plan
                                                        </a>
                                                    @endif
                                                @elseif(($discount || $successPayment) && $adminView)
                                                    <a href="{{ route('front.plans.details', ['id' => $plan->id, 'user_id' => $user->id]) }}" class="btn btn-primary m-2 "
                                                    >
                                                    View Plan
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="accordion mt-3" id="planAccordion">
                                        <div class="accordion-item card rounded-3 shadow-none overflow-hidden">
                                            <h2 class="accordion-header" id="heading{{ $plan->id }}">
                                                <button class="accordion-button collapsed d-flex justify-content-between align-items-center bg-white" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $plan->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="collapse{{ $plan->id }}">
                                                    <span class="d-flex align-items-center">
                                                        <figure class="title-ico me-2 mb-0">
                                                            <svg class="score-lock-ico" viewBox="0 0 800 800" width="24" height="24"  xmlns="http://www.w3.org/2000/svg">
                                                                <circle cx="400" cy="567.2" r="54.7"/>
                                                                <path d="M621.2,326.9V219.3c0-120.2-97.8-217.9-217.9-217.9C279.5,1.3,178.8,102,178.8,225.7v101.2
                                                                    c-59.5,1.2-107.3,49.7-107.3,109.5v255.5c0,60.5,49,109.5,109.5,109.5h438c60.5,0,109.5-49,109.5-109.5V436.4
                                                                    C728.5,376.6,680.6,328.1,621.2,326.9z M255.5,225.7c0-81.5,66.3-147.8,147.8-147.8c77.9,0,141.3,63.4,141.3,141.3v104H255.5V225.7z
                                                                    M655.5,691.8c0,20.2-16.3,36.5-36.5,36.5H181c-20.2,0-36.5-16.3-36.5-36.5V436.4c0-20.2,16.3-36.5,36.5-36.5h42.8h352.3H619
                                                                    c20.2,0,36.5,16.3,36.5,36.5V691.8z"/>
                                                            </svg>
                                                        </figure>
                                                        <h5>{{ $plan->name }} (${{ $plan->price }})<h5>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $plan->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $plan->id }}" data-bs-parent="#planAccordion">
                                                <div class="accordion-body pb-1 pt-2" style="background-color: #CFD5DD; color: #000; font-size: 0.9rem;">
                                                    <p class="mb-3">
                                                        {!! ($plan->description ?? 'No description available.') !!}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="card-footer gray-bg border-top-0 d-flex flex-wrap">
                                                <a href="javascript:void(0);" class="btn btn-white m-2 buy-plan-btn"
                                                    data-user-id="{{ $user->id }}"
                                                    data-plan-id="{{ $plan->id }}"
                                                    data-plan-price="{{ $plan->price }}"
                                                    data-plan-name="{{ $plan->name }}"
                                                    data-plan-description="{{ htmlspecialchars(strip_tags($plan->description), ENT_QUOTES) }}">
                                                    Buy Plan
                                                </a>
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
    <div class="modal" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
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
                            @if(isset($user->profile_image))
                                <img id="imagePreview" src="{{ webAssets($user->profile_image) }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="profileImageInput" class="form-label">Upload New Image</label>
                            <input type="file" class="form-control" id="profileImageInput" name="profile_image" accept="image/*" style="height: auto; border-radius: 5px;">
                            <input type="hidden" class="form-control" id="profileId" name="id" value="{{ $user->id }}">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="submitProfileUpdate('profile_image')">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Profile Name Modal -->
    <div class="modal" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-primary" onclick="submitProfileUpdate('name')">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Weight trak Modal -->
    <div class="modal" id="WeightModal" tabindex="-1" aria-labelledby="WeightModalLabel" aria-hidden="true">
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
            <div class="modal-content border-0 rounded-3">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-semibold" id="purchaseModalLabel">Purchase </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4">
                    <!-- Plan Description -->
                    <p class="mb-4 text-muted" id="plan-description">
                        
                    </p>

                    <!-- Form -->
                    <form id="payment-form">
                        <!-- Hidden User Info -->
                        <div id="registration-details">
                            <input type="hidden" id="name" value="{{ $user->name }}">
                            <input type="hidden" id="email" value="{{ $user->email }}">
                            <input type="hidden" id="phone" value="">
                        </div>
                        <!-- Heading -->
                        <h6 class="fw-bold text-dark mb-3">Payment Details</h6>
                        <div class="mb-3 mt-3">
                            <small>
                                <a href="#" id="toggle-coupon-link" class="coupon-link">Add a Coupon Code</a>
                            </small>
                        </div>
                        <!-- Coupon Code -->
                        <div class="mb-3 d-none" id="coupon-details">
                            <label for="promo-code" class="form-label">Coupon Code</label>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control h-auto" id="promo-code" placeholder="Enter coupon code">
                                <input type="hidden" class="form-control" id="discount">
                                <button type="button" class="btn btn-primary" id="apply-promo-code">Apply</button>
                            </div>
                            <small id="promo-message" class="form-text"></small>
                        </div>

                        <!-- Card Info -->
                        <div class="mb-3" id="payment-details">
                            <label for="card-element" class="form-label">Credit or Debit Card</label>
                            <div id="card-element" class="border rounded p-3 bg-light">
                                <!-- Stripe card element will go here -->
                            </div>
                            <div id="card-errors" class="text-danger mt-2"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submit" class="btn btn-primary w-100 mt-3">
                            Buy Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
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

    <!-- Add Goal / Challenge Modal -->
    <div class="modal fade" id="addGoalModal" tabindex="-1" aria-labelledby="editItemTitle" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemTitle">Add Nutrition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editItemForm">
                        <input type="hidden" id="itemType">
                        <label id="itemLabel" class="form-label">New Value</label>
                        <input type="text" class="form-control" id="itemInput">
                        <button type="button" class="btn btn-primary mt-3" id="saveGoal" onclick="saveGoalData()">Save</button>
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
    <div class="modal" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
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
                    <h5 class="modal-title"></h5>
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

    <div class="modal" id="supplementEditModal" tabindex="-1" aria-labelledby="supplementEditModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplementEditModalLabel">Edit Supplement Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="suplimentEditForm">
                        <input type="hidden" id="formName" name="form_name">
                        <input type="hidden" id="formQuestion" name="question" value="List any dietary vitamins or supplements you are currently taking (if any):">
                        <input type="hidden" id="type" name="type" value="supplement-edit">
                        <input type="hidden" id="mainAns" name="mainAns" value="Vitamin A">

                        <div class="mb-3">
                            <label class="form-label">Supplement Name</label>
                            <input type="text" id="answer" name="answer" class="form-control" readonly>
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

    <div class="modal" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content" style="z-index: 1100;">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Validation Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3" id="errorModalBody">
                <!-- Error messages will be injected here -->
            </div>
            <div class="modal-footer mt-0 py-1">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
    @php
        $trainingIntensityValue = isset($trainingIntencity[0]) && !empty($trainingIntencity[0]) ? $trainingIntencity[0] : null;
    @endphp
<script src="{!! frontAssets('js/jquery-3.6.min.js') !!}"></script>
<script src="{!! frontAssets('js/bootstrap.bundle.min.js') !!}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
    const user = @json($user);
    const userId = user.id;
    const userPrePlan = @json($userPrePlan);

    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
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

    function submitProfileUpdate(type) {
        const formData = new FormData();
        // Get user ID from either modal (whichever is present)
        
        const profileIdInput = $('#editImageModal #profileId').val() || $('#editNameModal #profileId').val();
        formData.append('user_id', profileIdInput);
        if(type === 'profile_image') {
            formData.append('type', 'profile_image');
            // Handle profile image
            const profileImageInput = document.querySelector('#editImageModal #profileImageInput');
            if (profileImageInput && profileImageInput.files.length > 0) {
                formData.append('profile_image', profileImageInput.files[0]);
            }
            formData.append('profile_image', '');
        } else if (type === 'name') {
            formData.append('type', 'name');

            const profileNameInput = $('#editNameModal #profileNameInput').val();
            if (profileNameInput) {
                formData.append('name', profileNameInput);
            }
        }
        
        fetch("{{ route('front.profile.update') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData
        })
        .then(async (response) => {
            if (!response.ok) {
                const errorData = await response.json();
                let message = '';

                if (response.status === 422 && errorData.errors) {
                    message += '<ul class="mb-1">';
                    Object.values(errorData.errors).forEach(err => {
                        message += `<li style="color: red;">${err[0]}</li>`;
                    });
                    message += '</ul>';
                } else if (errorData.message) {
                    message = `<p style="color:red;">${errorData.message}</p>`;
                } else {
                    message = `<p style="color:red;">Unexpected error (${response.status})</p>`;
                }

                // Show error in modal
                $('#errorModalBody').html(message);
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                errorModal.show();

                // Add background blur
                $('#editImageModal').addClass('blur-background');

                throw new Error('Validation or server error');
            }

            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.new_image_url) {
                    document.getElementById('currentProfileImage').src = data.new_image_url;
                }
                if (data.new_name) {
                    document.querySelector('h4.text-center').textContent = data.new_name;
                }

                alert('Profile updated successfully!');

                // Hide any open modal
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                });

                setTimeout(() => location.reload(), 500);
            } else {
                alert('Error updating profile');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });

    }

    $(document).ready(function () {
        $('.edit-profile-image').on('click', function () {
            $('#editImageModal').modal('show'); 
        });

        $('#editNameButton').on('click', function () {
            $('#editNameModal').modal('show'); 
        });

        $('#editHeightButton').on('click', function (event) {
            var button = $(this);
            var question = button.data('question');
            var answer = button.data('answer');
            var formName = button.data('form-name');
            $('#editHeightModal').find('#heightQuestion').val(question);
            $('#editHeightModal').find('#heightAnswer').val(answer);
            $('#editHeightModal').find('#formName').val(formName);
            $('#editHeightModal').modal('show'); 
        });
    });

    $('#editHeightForm').on('submit', function (e) {
        e.preventDefault();
        
        var updatedHeight = $('#heightAnswer').val();
        let formData = {
            form_name: $('#formName').val(),
            question: $('#heightQuestion').val(),
            answer: $('#heightAnswer').val(),
            type: "height",
            user_id: userId,
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: "{{ route('front.sample-plan-details-update') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Updated successfully!');
                    $('#editHeightModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error updating!');
                }
            },
            error: function(xhr) {
                alert('Something went wrong!');
            }
        });
    
    });

    $(document).off('change', '#selectAllCheckbox').on('change', '#selectAllCheckbox', function () {
        let isChecked = $(this).is(':checked');

        $('.meal-item-checkbox').prop('checked', isChecked);
        $('.meal-checkbox').prop('checked', isChecked);
    });

    $(document).on('change', '.meal-item-checkbox', function () {
        let allItems = $('.meal-item-checkbox');
        let allChecked = allItems.length === allItems.filter(':checked').length;

        // Set the global "Select All" checkbox state
        $('#selectAllCheckbox').prop('checked', allChecked);
    });

    $(document).on('click', '#fetchAllMeals', function () {
        $('#ShoppingModal .modal-body').html('<p>Loading...</p>');
        $('#ShoppingModal').modal('show');

        let userPlanId = $(this).data('user-plan-id');

        $.ajax({
            url: '{{ route("front.get.meals.items") }}' + `?user_id=${userId}&user_plan_id=${userPlanId}`,
            method: 'GET',
            success: function (response) {
                let meals = response.meals;
                let modalContent = `
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                        <label class="form-check-label" for="selectAllCheckbox">Select All</label>
                    </div>
                `;

                meals.forEach(meal => {
                    modalContent += `<h5 class="ms-3">${meal.category_title}</h5>`;
                    modalContent += `
                        <div class="ingredient-list">
                            <input type="checkbox" class="form-check-input mt-3 mx-3 meal-checkbox" id="mealCheckbox${meal.meal_id}">
                            <h2 class="d-inline-block px-0" style="border-bottom:none;">${meal.meal_title}</h2>
                            <hr class="m-0">
                            <ul>
                    `;

                    meal.items.forEach(item => {
                        let selectedQtyUnits = [];
                        try {
                            if (item.selected_qty_unit) {
                                selectedQtyUnits = JSON.parse(item.selected_qty_unit);
                            }
                            if (!Array.isArray(selectedQtyUnits)) {
                                selectedQtyUnits = [];
                            }
                        } catch (e) {
                            console.error(`Invalid selected_qty_unit JSON for item ${item.id}:`, e);
                            selectedQtyUnits = [];
                        }

                        const checkedUnits = selectedQtyUnits.filter(u =>
                            u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1"
                        );

                        let qtyCheckboxes = '';
                        if (checkedUnits.length > 0) {
                            const qtyText = checkedUnits.map(unitObj => {
                                let rawQty = unitObj.qty;
                                let unit = unitObj.unit;
                                let qty = 0;

                                // Handle fractional quantities
                                if (!isNaN(rawQty)) {
                                    qty = parseFloat(rawQty);
                                } else if (typeof rawQty === 'string' && rawQty.includes('/')) {
                                    const parts = rawQty.split('/');
                                    if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
                                        qty = parseFloat(parts[0]) / parseFloat(parts[1]);
                                    } else {
                                        qty = rawQty; // fallback
                                    }
                                }

                                if (['g', 'ml', 'mL'].includes(unit)) {
                                    return `${Math.round(qty)}${unit}`;
                                } else {
                                    const rounded = Math.round(qty * 100) / 100;
                                    let displayQty = rawQty;

                                    if (rounded === 0.25) displayQty = '¼';
                                    else if (rounded === 0.5) displayQty = '½';
                                    else if (rounded === 0.75) displayQty = '¾';

                                    return `${displayQty} ${unit}`;
                                }
                            }).join(' or ');
                            qtyCheckboxes = `<span>${qtyText}</span>`;
                        } else {
                            let qty = parseFloat(item.qty);
                            let unit = item.unit;
                            let displayQty = qty;

                            if (['g', 'ml', 'mL'].includes(unit)) {
                                qtyCheckboxes = `<span>${Math.round(qty)}${unit}</span>`;
                            } else {
                                const rounded = Math.round(qty * 100) / 100;
                                if (rounded === 0.25) displayQty = '¼';
                                else if (rounded === 0.5) displayQty = '½';
                                else if (rounded === 0.75) displayQty = '¾';

                                qtyCheckboxes = `<span>${displayQty} ${unit}</span>`;
                            }
                        }

                        modalContent += `
                            <li class="mb-3">
                                <div class="d-flex align-items-center ingredient-info">
                                    <div class="m-0">
                                        <input class="form-check-input meal-item-checkbox" type="checkbox" value="${item.id}" id="Check${item.id}">
                                        <input type="hidden" id="category" value="${item.category || ''}">
                                    </div>
                                    <div class="me-3 ingredient-img">
                                        <figure>
                                            <img src="{{ webAssets('storage') }}/${item.image || ''}" alt="">
                                        </figure>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span><strong>${item.title}</strong></span>
                                        <div class="mt-1 d-flex flex-wrap align-items-center">
                                            <strong class="me-2">QTY:</strong> ${qtyCheckboxes}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        `;
                    });

                    modalContent += `</ul></div>`;
                });

                $('#ShoppingModal .modal-body').html(modalContent);
            },
            error: function (xhr) {
                console.error('Error fetching meals:', xhr);
                $('#ShoppingModal .modal-body').html('<p>Error loading data.</p>');
            }
        });
    });

    $(document).on('click', '.btn-primary[data-bs-target="#ShippingPrintModal"]', function () {
        let aggregatedItems = {};

        $('#ShoppingModal .meal-item-checkbox:checked').each(function () {
            const listItem = $(this).closest('li');
            const itemName = listItem.find('.ingredient-info span strong').text().trim() || "Unknown Item";
            const category = listItem.find('input[type="hidden"]#category').val()?.trim() || "Uncategorized";

            const qtyContainer = listItem.find('.ingredient-info .flex-grow-1 > div.d-flex');
            if (qtyContainer.length === 0) return;

            const fullText = qtyContainer.text().trim();
            const qtyTextMatch = fullText.match(/QTY:\s*(.+)/i);
            if (!qtyTextMatch) return;

            const qtyText = qtyTextMatch[1];
            const qtyParts = qtyText.split(" or ").map(part => part.trim());

            qtyParts.forEach(part => {
                // Match values like: ½ piece OR 1/2 piece OR 1 piece
                const match = part.match(/^([\d¼½¾/.]+)\s*([a-zA-Z\s]+)$/);
                if (!match) return;

                let qtyRaw = match[1].trim();
                let unit = match[2].trim();

                const unicodeFractions = {
                    '¼': 0.25,
                    '½': 0.5,
                    '¾': 0.75
                };

                let qty = unicodeFractions[qtyRaw] ?? null;

                if (qty === null) {
                    if (qtyRaw.includes('/')) {
                        const parts = qtyRaw.split('/');
                        if (parts.length === 2) {
                            const numerator = parseFloat(parts[0]);
                            const denominator = parseFloat(parts[1]);
                            if (!isNaN(numerator) && !isNaN(denominator) && denominator !== 0) {
                                qty = numerator / denominator;
                            }
                        }
                    } else {
                        qty = parseFloat(qtyRaw);
                    }
                }

                if (!qty || isNaN(qty)) return;

                if (!aggregatedItems[category]) aggregatedItems[category] = {};
                if (!aggregatedItems[category][itemName]) aggregatedItems[category][itemName] = {};
                if (!aggregatedItems[category][itemName][unit]) aggregatedItems[category][itemName][unit] = 0;

                aggregatedItems[category][itemName][unit] += qty;
            });
        });

        let printListContent = '';
        for (let [category, items] of Object.entries(aggregatedItems)) {
            printListContent += `<h6>${category}</h6><ul style="list-style-type: none;">`;

            for (let [itemName, unitMap] of Object.entries(items)) {
                const qtyText = Object.entries(unitMap).map(([unit, total]) => {
                    if (['g', 'ml', 'mL'].includes(unit)) {
                        return `${Math.round(total)}${unit}`;
                    } else {
                        const roundedTotal = Math.round(total * 100) / 100;
                        let fraction = '';

                        if (roundedTotal === 0.25) fraction = '¼';
                        else if (roundedTotal === 0.5) fraction = '½';
                        else if (roundedTotal === 0.75) fraction = '¾';
                        else fraction = roundedTotal;

                        return `${fraction} ${unit}`;
                    }
                }).join(' or ');

                printListContent += `
                    <li style="margin: 0;">
                        <span style="margin-right: 2px; font-size: 18px; color: green;">&#10003;</span>
                        ${itemName} <strong>QTY:</strong> ${qtyText}
                    </li>
                `;
            }

            printListContent += `</ul><br/>`;
        }

        if (printListContent === '') {
            printListContent = '<p>No items selected.</p>';
        }

        $('#ShippingPrintModal .print-list').html(printListContent);
    });

    $(document).on('change', '.meal-checkbox', function () {
        const mealContainer = $(this).closest('.ingredient-list'); // Find the relevant meal container
        const isChecked = $(this).is(':checked'); // Check if "Meal Checkbox" is selected

        // Select/Deselect all meal items within this meal's container
        mealContainer.find('.meal-item-checkbox').prop('checked', isChecked);
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
                    $("#plan-preview-body").html(html);
                    $("#planPreviewModal").modal("show"); // ✅ show modal
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

        $('#errorModal').on('hidden.bs.modal', function () {
            $('#WeightModal').removeClass('blur-background');
            $('#editImageModal').removeClass('blur-background');
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
                    let message = '';
                    if (xhr.status === 422) {
                        // Laravel validation error
                        const errors = xhr.responseJSON.errors;
                        message += '<ul class="mb-1">';
                        $.each(errors, function(key, value) {
                            message += `<li style="color: red;">${value[0]}</li>`;
                        });
                        message += '</ul>';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = `<p style="color: red;">${xhr.responseJSON.message}</p>`;
                    } else {
                        message = `<p style="color: red;">Unexpected Error (${xhr.status}): ${error}</p>`;
                    }
                    
                    $('#errorModalBody').html(message);
                    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();  
                    $('#WeightModal').addClass('blur-background');
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
                            fill: false,
                            // tension: 0.4, // Smooth curves
                            pointRadius: 3, // Highlight points
                            pointBackgroundColor: '#fff'
                        },
                        {
                            label: 'Weight Progress', // Dataset label for Weight Progress
                            data: dataPointsWeight.map(dp => dp.y), // Y values for the Weight dataset
                            borderColor: '#649ef7', // Red color for the line
                            backgroundColor: '#fff', // Light red fill
                            fill: false,
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
                                        const date = dataPointsDate[tooltipData.parsed.x] ? dataPointsDate[tooltipData.parsed.x].x : 'Unknown Date'; // 
                                        // Get the date using the index from dataPointsDate
                                        return `Date: ${date}`; 
                                    }
                                    // return 'No Date';  // Fallback if no data is found
                                },
                                label: function(tooltipItem) {
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

        const $toggleLink = $('#toggle-coupon-link');
        const $couponDetails = $('#coupon-details');
        const $promoInput = $('#promo-code');
        const $promoMessage = $('#promo-message');
        const $paymentDetails = $('#payment-details');

        if ($toggleLink.length) {
            $toggleLink.on('click', function (e) {
                e.preventDefault();

                const isHidden = $couponDetails.hasClass('d-none');

                $couponDetails.toggleClass('d-none');

                $toggleLink.text(isHidden ? 'Remove a Coupon Code' : 'Add a Coupon Code');

                if (!isHidden) {
                    $promoInput.val('');
                    if ($promoMessage.length) {
                        $promoMessage.text('');
                    }
                    $paymentDetails.css('display', '');
                }
            });
        }

        // Stripe Payment
        // var stripe = Stripe('pk_test_51QI09cHWqn47bqTGYhGZIsiPSerWujjQgoHf4g0JwygrNt1OMC3RtEnMIjiEWbc8hiaN4umn4TD5zB8sBQEqcjzY0071a4RbUv');
        var stripe = Stripe('pk_live_51Pfz1YLSisFoEruHvHpdQQZLynQoR3x6BDuBgpb84zTK3EnTlROWMjxVpZhrp1rLmaqCJbusOUNHUoTKBLK7CXru00CkS5tVbt');
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
            // e.preventDefault();

            var planId = $(this).data('plan-id');  // Get the plan ID
            var price = $(this).data('plan-price');     // Get the plan price (if needed)
            var description = $(this).data('plan-description');     // Get the plan price (if needed)
            let name = $('#purchaseModal #name').val();
            let email = $('#purchaseModal #email').val();
            let phone = $('#purchaseModal #phone').val();

            // Update modal title with plan name (optional)
            $('#purchaseModalLabel').text('Purchase ' + $(this).data('plan-name')+ '($' + price+')');
            $('#plan-description').text(description);
            
            $('#purchaseModal #coupon-details').show();
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
                            name: name,
                            email: email,
                            phone: phone,
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
                            name: name,
                            email: email,
                            phone: phone,
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
                                    name: name,
                                    email: email,
                                    phone: phone,
                                    coupon_code: discountCode,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Handle successful payment
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

        $('body').on('click', '.buy-plan', function () {
            // e.preventDefault();

            var planId = $(this).data('plan-id');  // Get the plan ID
            var price = $(this).data('plan-price');     // Get the plan price (if needed)
            
            // Update modal title with plan name (optional)
            $('#purchaseModalLabel').text('Purchase ' + $(this).data('plan-name'));

            $('#purchaseModal #coupon-details').hide();
            
            $('#purchaseModal').modal('show');
            let name = $('#purchaseModal #name').val();
            let email = $('#purchaseModal #email').val();
            let phone = $('#purchaseModal #phone').val();
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
                        name: name,
                        email: email,
                        phone: phone,
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
                                name: name,
                                email: email,
                                phone: phone,
                                coupon_code: null,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Handle successful payment
                                    $('#purchaseModal').modal('hide');

                                    showThankYouModal();

                                    var user_id = response.data.user_id;  // Assuming the backend sends the user_id
                                    var payment_id = response.data.payment_id;  // Assuming the backend sends the user_id

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

        $('#thankYouModal').on('hidden.bs.modal', function () {
            location.reload(); // Reloads the page when modal is closed
        });

        $('#purchaseModal').on('hidden.bs.modal', function () {
            $('#payment-form')[0].reset();
            $('#card-errors').text('');
            // Reset coupon UI
            const toggleLink = document.getElementById('toggle-coupon-link');
            const couponDetails = document.getElementById('coupon-details');
            const promoInput = document.getElementById('promo-code');
            const promoMessage = document.getElementById('promo-message');

            if (couponDetails && !couponDetails.classList.contains('d-none')) {
                couponDetails.classList.add('d-none');
            }

            if (toggleLink) {
                toggleLink.textContent = 'Add a Coupon Code';
            }

            if (promoInput) {
                promoInput.value = '';
            }

            if (promoMessage) {
                promoMessage.textContent = '';
            }
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
            let formData = {
                form_name: $('#formName').val(),
                question: $('#question').val(),
                answer: $('#answer').val(),
                type: $('#type').val(),
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val(),
                user_id: userId,
                main_ans: $('#mainAns').val(),
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
             // Get data attributes from button
            const formName = $(this).data('form-name');
            const question = $(this).data('question');
            const type = $(this).data('type'); // "supplement-edit" or "medication-edit"
            const answer = $(this).data('answer');
            const mainAns = $(this).data('main-ans');
            const startDate = $(this).data('startdate');
            const endDate = $(this).data('enddate');

            // Set values in modal
            const modal = $('#supplementEditModal');
            modal.find('#formName').val(formName);
            modal.find('#formQuestion').val(question);
            modal.find('#type').val(type);
            modal.find('#mainAns').val(mainAns);
            modal.find('#answer').val(answer);
            modal.find('#start_date').val(formatDateForInput(startDate));
            modal.find('#end_date').val(formatDateForInput(endDate));

            // Change modal title and label dynamically
            if (type === 'medication-edit') {
                modal.find('.modal-title').text('Edit Medication Details');
                modal.find('label[for="answer"], label.form-label').first().text('Medication Name');
            } else {
                modal.find('.modal-title').text('Edit Supplement Details');
                modal.find('label[for="answer"], label.form-label').first().text('Supplement Name');
            }

            modal.modal('show');  
        });

        $('#suplimentEditForm').on('submit', function(event) {
            event.preventDefault();
            let formData = {
                form_name: $('#suplimentEditForm #formName').val(),
                question: $('#suplimentEditForm #formQuestion').val(),
                answer: $('#suplimentEditForm #answer').val(),
                type: $('#suplimentEditForm #type').val(),
                start_date: $("#suplimentEditForm #start_date").val(),
                end_date: $("#suplimentEditForm #end_date").val(),
                user_id: userId,
                main_ans: $('#suplimentEditForm #mainAns').val(),
                _token: '{{ csrf_token() }}' // CSRF protection
            };
            $.ajax({
                url: "{{ route('front.sample-plan-details-update') }}", // Laravel route to handle updates
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Updated successfully!');
                        $('#supplementEditModal').modal('hide'); // Close modal
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

        // Helper function: converts DD-MM-YYYY → YYYY-MM-DD
        function formatDateForInput(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('-'); // ["24", "04", "2025"]
            if (parts.length !== 3) return '';
            return `${parts[2]}-${parts[1]}-${parts[0]}`; // "2025-04-24"
        }
        
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

        // Multiple ways to bind the click event
        $(document).on('click', '.add-goal', function (e) {
            e.preventDefault();
            e.stopPropagation();
            
            let type = $(this).attr("data-type");
            
            $("#itemType").val(type);
            $("#editItemTitle").text(type === "goal" ? "Add Goal" : "Add Challenge");
            $("#itemLabel").text(type === "goal" ? "New Goal" : "New Challenge");
            
            // Use the same method that works for test button
            $("#addGoalModal").modal("show");
        });

        // Update Goal or Challenge via AJAX
        $("#saveGoal").on('click', function () {
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
        $(".view-past-goals").on('click', function () {
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

        $(".view-past-history").on('click', function () {
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
                    let modalTitle = type === "supplement" ? "Past Supplements" : "Past Medications";
                    $("#viewPastItemsModalLabel").text(modalTitle);

                    let pastList = $("#pastItemsList");
                    pastList.html(""); // Clear existing list

                    if (data.length > 0) {
                        $.each(data, function (index, item) {
                            let displayText = item.answer;

                            if (item.start_date && item.end_date) {
                                displayText += ` <small>(Start: ${new Date(item.start_date).toLocaleDateString()} to End: ${new Date(item.end_date).toLocaleDateString()})</small>`;
                            } else {
                                displayText += ` <small>(Added on: ${new Date(item.created_at).toLocaleDateString()})</small>`;
                            }

                            pastList.append("<li>" + displayText + "</li>");
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
        
        // Assuming server passes training intensity as JSON
        const response = @json(isset($trainingIntencity[0]) && !empty($trainingIntencity[0]) ? $trainingIntencity[0] : null);

        const displayLabels = ["Low", "Moderate", "High"]; // X-axis labels
        const intensityKeys = ["Low Intensity", "Moderate Intensity", "High Intensity"];

        const colors = {
            "Low Intensity": "rgba(47, 202, 98, 0.6)",
            "Moderate Intensity": "rgba(255, 159, 64, 0.6)",
            "High Intensity": "rgba(232, 62, 53, 0.6)"
        };

        const borderColors = {
            "Low Intensity": "rgba(47, 202, 98, 1)",
            "Moderate Intensity": "rgba(255, 159, 64, 1)",
            "High Intensity": "rgba(232, 62, 53, 1)"
        };

        const dataset = {
            label: '# of days',
            data: intensityKeys.map(key => parseInt(response[key]) || 0),
            backgroundColor: intensityKeys.map(key => colors[key]),
            borderColor: intensityKeys.map(key => borderColors[key]),
            borderWidth: 1
        };

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: displayLabels,
                datasets: [dataset]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: context => `Days: ${context.raw}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 7,
                        ticks: {
                            stepSize: 1,
                            autoSkip: false
                        },
                        title: {
                            display: true,
                            text: '# of days'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Training Intensity'
                        }
                    }
                }
            }
        });
    });

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

        // Test function to check if modal works
    function testModal() {
        try {
            $("#addGoalModal").modal("show");
        } catch (error) {
            console.error('Test modal error:', error);
            // Fallback
            $("#addGoalModal").addClass('show').css('display', 'block');
            $('body').addClass('modal-open');
            $('<div class="modal-backdrop fade show"></div>').appendTo('body');
        }
    }

    // Function to open add goal modal (called by onclick attribute)
    function openAddGoalModal(type) {
        
        $("#itemType").val(type);
        $("#editItemTitle").text(type === "goal" ? "Add Goal" : "Add Challenge");
        $("#itemLabel").text(type === "goal" ? "New Goal" : "New Challenge");
        
        $("#addGoalModal").modal("show");
    }

    // Function to open view past goals modal (called by onclick attribute)
    function openViewPastGoalsModal(type) {
        
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
    }

    // Function to open view past history modal (called by onclick attribute)
    function openViewPastHistoryModal(type) {
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
                        let displayText = item.answer;

                        if (item.start_date && item.end_date) {
                            displayText += ` <small>(Start: ${new Date(item.start_date).toLocaleDateString()} to End: ${new Date(item.end_date).toLocaleDateString()})</small>`;
                        } else {
                            displayText += ` <small>(Added on: ${new Date(item.created_at).toLocaleDateString()})</small>`;
                        }

                        pastList.append("<li>" + displayText + "</li>");
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
    }

    // Function to save goal data (called by onclick attribute)
    function saveGoalData() {
        let type = $("#itemType").val();
        let answer = $("#itemInput").val();

        if (!answer || answer.trim() === '') {
            alert('Please enter a value before saving.');
            return;
        }

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
                    $("#addGoalModal").modal("hide");
                    location.reload();
                } else {
                    alert("Error: " + (response.message || "Something went wrong!"));
                }
            },
            error: function (xhr, status, error) {
                alert("Something went wrong! Please try again.");
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Simple solution: just prevent default behavior on weight tracking link
        const weightTrackingLink = document.getElementById('weight-tracking');
        if (weightTrackingLink) {
            weightTrackingLink.addEventListener('click', function(e) {
                e.preventDefault(); // Only prevent default link behavior
                e.stopPropagation(); // Stop event bubbling
                // Let Bootstrap handle the modal normally
            });
        }
    });
</script>
@endsection