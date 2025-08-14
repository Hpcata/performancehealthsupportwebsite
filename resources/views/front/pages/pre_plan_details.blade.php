@extends(frontView('layouts.app'))

@section('title', 'Performance Dietitian | Strength & Conditioning Coach')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
    input::placeholder {
        color: #6c757d !important;
        opacity: 1 !important;
    }

    .edit-icon {
        display: none;
        margin-left: 8px;
        cursor: pointer;
        color: #0d6efd;
    }
    .food-checkbox:checked + label + .edit-icon {
        display: inline-block;
    }
    .modal-header {
        cursor: move;
    }
    .required-asterisk {
        font-size: 1.5rem;
        vertical-align: middle;
    }
</style>
    <div class="section">
        <div class="container">
            <div class="steps-list mb-4">
                <div class="wizard-inner">
                    <a class="tab-steps active" href="#"><span class="round-tab">1</span> <i>Step 1</i></a>
                    <a class="tab-steps" href="#"><span class="round-tab">2</span> <i>Step 2</i></a>
                    <a class="tab-steps" href="#"><span class="round-tab">3</span> <i>Step 3</i></a>
                    <a class="tab-steps" href="#"><span class="round-tab">4</span> <i>Step 4</i></a>
                    <a class="tab-steps" href="#"><span class="round-tab">5</span> <i>Step 5</i></a>
                    <a class="tab-steps" href="#"><span class="round-tab">6</span> <i>Step 6</i></a>
                    <a class="tab-steps" href="#"><span class="round-tab">7</span> <i>Step 7</i></a>
                    <a class="tab-steps" href="#"><span class="round-tab">8</span> <i>Step 8</i></a>
                    <a class="tab-steps" href="#"><span class="round-tab">9</span> <i>Step 9</i></a>
                </div>
            </div>
            <div class="tab-main-box">
                <form id="nutrition-screen-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $userId }}" />
                    <input type="hidden" name="payment_id" value="{{ $paymentId }}" />
                    <div class="step-tab-box" id="div1">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Personal details</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <input type="hidden" name="questions[personal_details][dob]" value="Date of Birth">
                                        <div class="form-floating my-3">
                                            <!-- Hidden question input -->
                                            <input type="date" class="form-control" name="ans[personal_details][dob]" placeholder="">
                                            <label>Date of Birth<small class="text-danger required-asterisk">*</small></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="hidden" name="questions[personal_details][sport_category]" value="Sport Category">
                                        <div class="form-floating my-3">
                                            <select class="form-select" id="sport_category" name="ans[personal_details][sport_category]" required>
                                                <option value="">Select Sport Category</option>
                                                @foreach($sportCategories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="sport_category">Sport Category<small class="text-danger required-asterisk">*</small></label>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-6 col-lg-4">
                                        <input type="hidden" name="questions[personal_details][sport_game]" value="Sport Game">
                                        <div class="form-floating my-3">
                                            <select class="form-select" id="sport_game" name="ans[personal_details][sport_game]" required>
                                                <option value="">Select Sport Game</option>
                                                {{-- Games will be populated here via JavaScript --}}
                                            </select>
                                            <label for="sport_game">Sport Game<small class="text-danger">*</small></label>
                                        </div>
                                    </div> -->

                                    <div class="col-md-6 col-lg-4">
                                        <input type="hidden" name="questions[personal_details][occupation]" value="Occupation">
                                        <div class="form-floating my-3">
                                            <select class="form-select" id="sport_game"  name="ans[personal_details][occupation]" placeholder="">
                                                <option value="">Select Sport Game</option>
                                            </select>
                                            <label for="sport_game">Sport<small class="text-danger required-asterisk">*</small></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="hidden" name="questions[personal_details][postcode]" value="Postcode">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="ans[personal_details][postcode]" placeholder="" maxlength="6" pattern="\d{1,6}">
                                            <label>Postcode<small class="text-danger required-asterisk">*</small></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="hidden" name="questions[personal_details][referredBy]" value="Referred by">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="ans[personal_details][referredBy]" id="referredBy" placeholder="">
                                            <label>Referred by</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                            <button id="next" type="button" class="btn btn-primary ms-auto showStepTab pt-2 pb-2 px-4 py-4" target="2">Next</button>
                            </div>
                        </div>
                    </div>

                    <div class="step-tab-box " id="div2">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Medical History </h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <h5>Have you recently had a blood test?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[medical_history][blood_test]" value="Have you recently had a blood test?">
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[medical_history][blood_test][answer]" value="No" id="bloodTestNo">
                                                <label class="form-check-label" for="bloodTestNo">
                                                No
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[medical_history][blood_test][answer]" value="Yes" id="bloodTestYes">
                                                <label class="form-check-label" for="bloodTestYes">
                                                Yes
                                                </label>
                                            </div>
                                        </div>
                                        <!-- File Upload Input, initially hidden -->
                                        <div id="bloodTestDateSection" style="display: none;">
                                            <label for="bloodTestDate" class="form-label">Approx. Date: <small class="text-danger" >*</small></label>
                                            <select class="form-select mb-2" name="ans[medical_history][blood_test][date]" id="bloodTestDate">
                                                <option value="">-- Select --</option>
                                                <option value="3 months">3 months</option>
                                                <option value="6 months">6 months</option>
                                                <option value="1 year">1 year</option>
                                                <option value="2 years">2 years</option>
                                                <option value="over 2 years">Over 2 years</option>
                                            </select>
                                        </div>
                                        <div id="fileUploadSection" style="display: none;">
                                            <label for="bloodTestFile" class="form-label">Optional: Upload blood test results</label>
                                            <input type="file" class="form-control" name="ans[medical_history][blood_test_file]" id="bloodTestFile" style="height: auto; border-radius: 5px;">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <h5>Provide details of any prescription medications (if taking any):<span class="text-danger required-asterisk">*</span></h5>
                                            <input type="hidden" name="questions[medical_history][prescription_meds]" value="Provide details of any prescription medications (if taking any):">
                                            <input type="text" class="form-control" name="ans[medical_history][prescription_meds]" placeholder="Eg: Nurofen, Ritalin or Nil">
                                            <small class="text-muted">(Use commas to separate items. Eg: Nurofen, Ritalin or Nil.)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <h5>Have you recently been diagnosed with any health problems or illnesses:<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[medical_history][diagnosed]" value="Have you recently been diagnosed with any of the following:">

                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="Sports-related injury" name="ans[medical_history][diagnosed][]" id="diagnosed1">
                                                <label class="form-check-label" for="diagnosed1">
                                                    Sports-related injury
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="Mental disorder (e.g. ADHD, Anxiety, Depression)" name="ans[medical_history][diagnosed][]" id="diagnosed2">
                                                <label class="form-check-label" for="diagnosed2">
                                                    Mental disorder (Eg: ADHD, Anxiety, Depression)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="Eating disorder" name="ans[medical_history][diagnosed][]" id="diagnosed3">
                                                <label class="form-check-label" for="diagnosed3">
                                                    Eating disorder
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="Low iron/anaemia" name="ans[medical_history][diagnosed][]" id="diagnosed4" >
                                                <label class="form-check-label" for="diagnosed4">
                                                    Low iron/anaemia
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[medical_history][diagnosed][]" value="Amenorrhoea (loss of menstruation/period)" id="diagnosed5">
                                                <label class="form-check-label" for="diagnosed5">
                                                    Amenorrhoea (loss of menstruation/period)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[medical_history][diagnosed][]" value="Surgery" id="diagnosed6">
                                                <label class="form-check-label" for="diagnosed6">
                                                    Surgery
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="No" name="ans[medical_history][diagnosed][]" id="diagnosed7">
                                                <label class="form-check-label" for="diagnosed7">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <h5>List any dietary vitamins or supplements you are <strong>currently</strong> taking (if any):<span class="text-danger required-asterisk">*</span></h5>
                                            <input type="hidden" name="questions[medical_history][vitamins_supplements]" value="List any dietary vitamins or supplements you are currently taking (if any):">
                                            <input type="text" class="form-control" name="ans[medical_history][vitamins_supplements]" placeholder="Eg: Swisse Vitamin C, Musashi Whey Protein Powder, Nil">
                                            <small class="text-muted">(Use commas to separate items. Eg: Swisse Vitamin C, Musashi Whey Protein Powder or Nil.)</small>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <h5>Please list any other medical conditions<span class="text-danger required-asterisk">*</span></h5>
                                            <input type="hidden" name="questions[medical_history][medical_conditions]" value="Please list any other medical conditions">
                                            <input type="text" class="form-control" name="ans[medical_history][medical_conditions]" placeholder="Eg: Coeilac, Asthma or Nil">
                                            <small class="text-muted">Use commas to separate items. Eg: Coeilac, Asthma or Nil.</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <h5>If female which statement best describes your current menstrual function?<span class="text-danger required-asterisk">*</span></h5>
                                            <input type="hidden" name="questions[medical_history][menstrual_function]" value="If female which statement best describes your current menstrual function?">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="Male - Not applicable" name="ans[medical_history][menstrual_function]" id="diagnose1">
                                                    <label class="form-check-label" for="diagnose1">
                                                        Male - Not applicable
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="I am on contraception with controlled cycles" name="ans[medical_history][menstrual_function]" id="diagnose2">
                                                    <label class="form-check-label" for="diagnose2">
                                                        I am on contraception with controlled cycles
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="I am not on contraception and have regular menstrual cycles" name="ans[medical_history][menstrual_function]" id="diagnose3">
                                                    <label class="form-check-label" for="diagnose3">
                                                        I am not on contraception and have regular menstrual cycles
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="I often miss a cycles" name="ans[medical_history][menstrual_function]" id="diagnose4">
                                                    <label class="form-check-label" for="diagnose4">
                                                        I often miss a cycles
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="I have not had a cycle for over 2 months" name="ans[medical_history][menstrual_function]" id="diagnose5" >
                                                    <label class="form-check-label" for="diagnose5">
                                                        I have not had a cycle for over 2 months
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[medical_history][menstrual_function]" value="Other" id="diagnose6">
                                                    <label class="form-check-label" for="diagnose6">
                                                        Other
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab pt-2 pb-2 px-4 py-4" target="1">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab pt-2 pb-2 px-4 py-4" target="3">Next</button>
                            </div>
                        </div>
                    </div>

                    <div class="step-tab-box " id="div3">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Physical Measures</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>Height (cm):<span class="text-danger required-asterisk">*</span></label>
                                            <input type="hidden" name="questions[physical_measures][height]" value="Height (cm):" />
                                            <input type="text" class="form-control" name="ans[physical_measures][height]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>Current body weight (kg) (if known):<span class="text-danger required-asterisk">*</span></label>
                                            <input type="hidden" name="questions[physical_measures][weight]" value="Current body weight (kg) (if known):" />
                                            <input type="text" class="form-control" name="ans[physical_measures][weight]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <h5>What has happened to your body weight over the past 2-3 months?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[physical_measures][weightover]" value="What has happened to your body weight over the past 2-3 months?" />

                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][weightover]" value="Consistent (stable)"  id="weightover1">
                                                <label class="form-check-label" for="weightover1">
                                                    Consistent (stable)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][weightover]" value="Increased" id="weightover2">
                                                <label class="form-check-label" for="weightover2">
                                                    Increased
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][weightover]" value="Decreased" id="weightover3">
                                                <label class="form-check-label" for="weightover3">
                                                    Decreased
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][weightover]" value="Changing (fluctuating)" id="weightover4">
                                                <label class="form-check-label" for="weightover4">
                                                    Changing (fluctuating)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][weightover]" value="Unsure" id="weightover5">
                                                <label class="form-check-label" for="weightover5">
                                                    Unsure
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <h5>Have you recently undertaken a body composition assessment (measure of muscle, body fat)?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[physical_measures][bodycomposition]" value="Have you recently undertaken a body composition assessment (measure of muscle, body fat)?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][bodycomposition][answer]" value="No" id="bodyCompositionNo">
                                                <label class="form-check-label" for="bodyCompositionNo">
                                                    No
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][bodycomposition][answer]" value="Yes" id="bodyCompositionYes">
                                                <label class="form-check-label" for="bodyCompositionYes">
                                                   Yes
                                                </label>
                                            </div>
                                            <!-- File Upload Input, initially hidden -->
                                            <div id="bodyCompositionDateSection" style="display: none;">
                                                <label for="bodyCompositionDate" class="form-label">Approx. Date: <small class="text-danger">*</small></label>
                                                <select class="form-select mb-2" name="ans[physical_measures][bodycomposition][date]" id="bodyCompositionDate">
                                                    <option value="">-- Select --</option>
                                                    <option value="3 months">3 months</option>
                                                    <option value="6 months">6 months</option>
                                                    <option value="1 year">1 year</option>
                                                    <option value="2 years">2 years</option>
                                                    <option value="over 2 years">Over 2 years</option>
                                                </select>
                                            </div>
                                            <div id="bodyCompositionFileInput" style="display: none;">
                                                <label for="bodyCompositionFile" class="form-label">Optional: Upload body composition file</label>
                                                <input type="file" class="form-control" name="ans[physical_measures][bodycomposition][]" id="bodyCompositionFile" multiple style="height: auto; border-radius: 5px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="2">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step pt-2 pb-2 px-4 py-4" target="4">Next</button>
                            </div>
                        </div>
                    </div>

                    <div class="step-tab-box" id="div4">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Social Information</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>I am currently living with:<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[social_information][livingwith]" value="I am currently living with:" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][livingwith]" value="Myself" id="livingwith1">
                                                <label class="form-check-label" for="livingwith1">
                                                    Myself
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][livingwith]" value="Partner" id="livingwith2">
                                                <label class="form-check-label" for="livingwith2">
                                                    Partner
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][livingwith]" value="Family" id="livingwith3">
                                                <label class="form-check-label" for="livingwith3">
                                                    Family
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][livingwith]" value="Friends" id="livingwith4">
                                                <label class="form-check-label" for="livingwith4">
                                                    Friends
                                                </label>
                                            </div>
                                            <!-- <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][livingwith]" value="Other" id="livingwith5">
                                                <label class="form-check-label" for="livingwith5">
                                                    Other:
                                                </label>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>Who does most of the cooking at home?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[social_information][cookinghome]" value="Who does most of the cooking at home?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Me" id="cookinghome3">
                                                <label class="form-check-label" for="cookinghome3">
                                                    Me
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Mother" id="cookinghome1">
                                                <label class="form-check-label" for="cookinghome1">
                                                    Mother
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Father" id="cookinghome2">
                                                <label class="form-check-label" for="cookinghome2">
                                                    Father
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Partner" id="cookinghome4">
                                                <label class="form-check-label" for="cookinghome4">
                                                    Partner
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Friend" id="cookinghome5">
                                                <label class="form-check-label" for="cookinghome5">
                                                Friend
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>How would you rate your cooking skills?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[social_information][cookingskills]" value="How would you rate your cooking skills?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookingskills]" value="Very poor: no cooking skills, struggle with the kettle and toaster most days" id="cookingskills1">
                                                <label class="form-check-label" for="cookingskills1">
                                                    Very poor: no cooking skills, struggle with the kettle and toaster most days
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookingskills]" value="Poor: ready made meals and a microwave or take out are where it's at" id="cookingskills2">
                                                <label class="form-check-label" for="cookingskills2">
                                                    Poor: ready made meals and a microwave or take out are where it's at
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookingskills]" value="Average: capable of using different cooking techniques & prepare most simple meals" id="cookingskills3">
                                                <label class="form-check-label" for="cookingskills3">
                                                    Average: capable of using different cooking techniques & prepare most simple meals
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookingskills]" value="Good: able to follow most recipes with overall good success" id="cookingskills4">
                                                <label class="form-check-label" for="cookingskills4">
                                                    Good: able to follow most recipes with overall good success
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookingskills]" value="Excellent: I enjoy cooking and often take on detailed recipes" id="cookingskills5">
                                                <label class="form-check-label" for="cookingskills5">
                                                    Excellent: I enjoy cooking and often take on detailed recipes
                                                </label>
                                            </div>
                                            <!-- <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookingskills]" value="Other" id="cookingskills6">
                                                <label class="form-check-label" for="cookingskills6">
                                                    Other:
                                                </label>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="3">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step pt-2 pb-2 px-4 py-4" target="5">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box " id="div5">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Dietary Information</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>List your favourite foods?<span class="text-danger required-asterisk">*</span></label>
                                            <input type="hidden" name="questions[dietary_information][favoutire_foods]" value="List your favourite foods?" />
                                            <input type="text" class="form-control" name="ans[dietary_information][favoutire_foods]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>Do you avoid/dislike any foods? List below<span class="text-danger required-asterisk">*</span></label>
                                            <input type="hidden" name="questions[dietary_information][dislike_foods]" value="Do you avoid/dislike any foods? List below" />
                                            <input type="text" class="form-control" name="ans[dietary_information][dislike_foods]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Do you have any allergies or intolerances?<span class="text-danger required-asterisk">*</span></h5><span>(more than 1 box can be checked)</span>
                                        <input type="hidden" name="questions[dietary_information][dietaryneeds]" value="Do you have any allergies or intolerances?" />
                                        <div class="form-floating my-3">

                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Coeliac / Gluten Free" id="dietaryneeds2">
                                                <label class="form-check-label" for="dietaryneeds2">
                                                    Coeliac / Gluten Free
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Dairy intolerant / Lactose free" id="dietaryneeds3">
                                                <label class="form-check-label" for="dietaryneeds3">
                                                    Dairy intolerant / Lactose free
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Nut allergy" id="dietaryneeds4">
                                                <label class="form-check-label" for="dietaryneeds4">
                                                    Nut allergy
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Shellfish allergy" id="dietaryneeds5">
                                                <label class="form-check-label" for="dietaryneeds5">
                                                    Shellfish allergy
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Soy allergy" id="dietaryneeds6">
                                                <label class="form-check-label" for="dietaryneeds6">
                                                    Soy allergy
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="No" id="dietaryneeds1">
                                                <label class="form-check-label" for="dietaryneeds1">
                                                    No
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Other" id="dietaryneeds7">
                                                <label class="form-check-label" for="dietaryneeds7">
                                                    Other
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Do you tend to follow any particular way of eating?<span class="text-danger required-asterisk">*</span></h5><span>(more than 1 box can be checked)</span>
                                        <input type="hidden" name="questions[dietary_information][wayofeating]" value="Do you tend to follow any particular way of eating?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="Paleo" id="wayofeating2">
                                                <label class="form-check-label" for="wayofeating2">Paleo</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="Low carb" id="wayofeating3">
                                                <label class="form-check-label" for="wayofeating3">Low carb</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="High protein" id="wayofeating4">
                                                <label class="form-check-label" for="wayofeating4">High protein</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="Gluten free/wheat free" id="wayofeating5">
                                                <label class="form-check-label" for="wayofeating5">Gluten free/wheat free</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="Keto" id="wayofeating6">
                                                <label class="form-check-label" for="wayofeating6">Keto</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="No" id="wayofeating1">
                                                <label class="form-check-label" for="wayofeating1">No</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="Other" id="wayofeating7">
                                                <label class="form-check-label" for="wayofeating7">Other</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <h5>Please indicate your hunger/appetite over the day: (tick relevant meal times)<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[dietary_information][hunger]" value="Please indicate your hunger/appetite over the day: (tick relevant meal times)" />
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">Not hungry</th>
                                                        <th class="text-center">Beginning to feel hungry</th>
                                                        <th class="text-center">Pretty hungry</th>
                                                        <th class="text-center">Very hungry</th>
                                                        <th class="text-center">Starving</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Breakfast</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][breakfast]" value="Not hungry" id="Breakfast-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][breakfast]" value="Beginning to feel hungry" id="Breakfast-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][breakfast]" value="Pretty hungry" id="Breakfast-3"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][breakfast]" value="Very hungry" id="Breakfast-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][breakfast]" value="Starving" id="Breakfast-5"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Morning Tea</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][morning_tea]" value="Not hungry" id="MorningTea-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][morning_tea]" value="Beginning to feel hungry" id="MorningTea-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][morning_tea]" value="Pretty hungry" id="MorningTea-3"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][morning_tea]" value="Very hungry" id="MorningTea-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][morning_tea]" value="Starving" id="MorningTea-5"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Lunch</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][lunch]" value="Not hungry" id="Lunch-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][lunch]" value="Beginning to feel hungry" id="Lunch-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][lunch]" value="Pretty hungry" id="Lunch-3"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][lunch]" value="Very hungry" id="Lunch-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][lunch]" value="Starving" id="Lunch-5"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Afternoon tea</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][afternoon_tea]" value="Not hungry" id="Afternoontea-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][afternoon_tea]" value="Beginning to feel hungry" id="Afternoontea-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][afternoon_tea]" value="Pretty hungry" id="Afternoontea-3"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][afternoon_tea]" value="Very hungry" id="Afternoontea-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][afternoon_tea]" value="Starving" id="Afternoontea-5"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinner</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dinner]" value="Not hungry" id="Dinner-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dinner]" value="Beginning to feel hungry" id="Dinner-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dinner]" value="Pretty hungry" id="Dinner-3"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dinner]" value="Very hungry" id="Dinner-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dinner]" value="Starving" id="Dinner-5"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dessert</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dessert]" value="Not hungry" id="Supper-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dessert]" value="Beginning to feel hungry" id="Supper-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dessert]" value="Pretty hungry" id="Supper-3"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dessert]" value="Very hungry" id="Supper-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][dessert]" value="Starving" id="Supper-5"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>How often do you eat takeaway food?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[dietary_information][takeaway_foods]" value="How often do you eat takeaway food?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[dietary_information][takeaway_foods]" value="Most days of the week" id="takeawayfood1">
                                                <label class="form-check-label" for="takeawayfood1">Most days of the week</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[dietary_information][takeaway_foods]" value="3-4 days a week" id="takeawayfood2">
                                                <label class="form-check-label" for="takeawayfood2">3-4 days a week</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[dietary_information][takeaway_foods]" value="Once a week or less" id="takeawayfood3">
                                                <label class="form-check-label" for="takeawayfood3">Once a week or less</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>What are the most common takeaways you eat? Pizza, McDonald's, Mexican, etc<span class="text-danger required-asterisk">*</span></label>
                                            <input type="hidden" name="questions[dietary_information][common_takeaways]" value="What are the most common takeaways you eat? Pizza, McDonald's, Mexican, etc" />
                                            <input type="text" class="form-control" name="ans[dietary_information][common_takeaways]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <h5>Rank the following considerations from highest (1) to lowest (3) when selecting a meal or snack:<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[dietary_information][flavour_taste]" value="Rank the following considerations from highest (1) to lowest (3) when selecting a meal or snack:" />
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">1</th>
                                                        <th class="text-center">2</th>
                                                        <th class="text-center">3</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Flavour/taste</td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][flavour]" value="1" data-rank="1"></td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][flavour]" value="2" data-rank="2"></td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][flavour]" value="3" data-rank="3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Convenience</td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][convenience]" value="1" data-rank="1"></td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][convenience]" value="2" data-rank="2"></td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][convenience]" value="3" data-rank="3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nutritional value</td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][nutritional]" value="1" data-rank="1"></td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][nutritional]" value="2" data-rank="2"></td>
                                                        <td class="text-center"><input class="form-check-input rank-option" type="radio" name="ans[dietary_information][flavour_taste][nutritional]" value="3" data-rank="3"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>If 18+, do you drink alcohol?<span class="text-danger required-asterisk">*</span></label>
                                            <input type="hidden" name="questions[dietary_information][drink_alcohol]" value="If 18+, do you drink alcohol?" />
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[dietary_information][drink_alcohol]" value="No" id="drink_alcohol_no">
                                                    <label class="form-check-label" for="drink_alcohol_no">No</label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[dietary_information][drink_alcohol]" value="Yes" id="drink_alcohol_yes">
                                                    <label class="form-check-label" for="drink_alcohol_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div id="drinkAlcoholInput" style="display: none;" class="mt-3">
                                                <label for="drink_alcohol_days" class="form-label">If yes, how many days/week?</label>
                                                <input type="text" class="form-control" id="drink_alcohol_days" name="ans[dietary_information][drink_alcohol][days]" placeholder="e.g. 3 days">
                                                <label for="drink_alcohol_drinks" class="form-label mt-2">How many drinks/day?</label>
                                                <input type="text" class="form-control" id="drink_alcohol_drinks" name="ans[dietary_information][drink_alcohol][drinks]" placeholder="e.g. 2 drinks">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="4">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step pt-2 pb-2 px-4 py-4" target="6">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box" id="div6">
                        <div class="card">
                            <div class="bg-white card-header p-4 pb-3">
                                <h4 class="m-0">Food Preference List</h4>
                                <p class="mt-3 text-danger">* Select foods you currently eat OR are open to try</p>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <!-- <h5>Carbohydrate rich foods</h5> -->
                                    <div class="col-md-12">
                                        <h5>1. Grains</h5>
                                        <input type="hidden" name="questions[food_preference][grains]" value="Grains" />

                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][grains][Cereals][]" value="" id="repair1" data-food-key="Cereals"
                                                        data-food-group="grains">
                                                        <label class="form-check-label" for="repair1">Cereals</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Cereals"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][grains][Pasta & Noodles][]" value="" id="repair2" data-food-key="Pasta & Noodles"
                                                        data-food-group="grains">
                                                        <label class="form-check-label" for="repair2">Pasta & Noodles</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Pasta & Noodles"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][grains][Small Grains][]" value="" id="repair3" data-food-key="Small Grains"
                                                        data-food-group="grains">
                                                        <label class="form-check-label" for="repair3">Small Grains</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Small Grains"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][grains][Bread & Rolls][]" value="" id="repair7" data-food-key="Bread & Rolls"
                                                        data-food-group="grains">
                                                        <label class="form-check-label" for="repair7">Bread & Rolls</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Bread & Rolls"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][grains][Specialty Breads][]" value="" id="repair8" data-food-key="Specialty Breads"
                                                        data-food-group="grains">
                                                        <label class="form-check-label" for="repair8">Specialty Breads</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Specialty Breads"></div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][grains][Flat Bread][]" value="" id="repair-8" data-food-key="Flat Bread"
                                                        data-food-group="grains">
                                                        <label class="form-check-label" for="repair-8">Flat Bread</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Flat Bread"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>2. Legumes & Beans</h5>
                                        <input type="hidden" name="questions[food_preference][legumes_beans_and_pulses]"
                                        value="Legumes & Beans" />
                                        <div class="form-floating my-3">
                                             <div class="row row-cols-1 row-cols-md-4 g-2">
                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][Legumes & Beans][]" value="" id="protect1" data-food-key="Legumes & Beans" data-food-group="legumes_beans_and_pulses">
                                                        <label class="form-check-label" for="protect1">Legumes & Beans</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Legumes & Beans"></div>
                                                </div>
                                                <!-- <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][Beans][]" value="" id="protect2" data-food-key="Beans" data-food-group="legumes_beans_and_pulses">
                                                        <label class="form-check-label" for="protect2">Beans</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Beans"></div>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>3. Nuts</h5>
                                        <input type="hidden" name="questions[food_preference][nuts]" value="Nuts" />
                                        <div class="form-floating my-3">
                                             <div class="row row-cols-1 row-cols-md-4 g-2">
                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][Nuts][]" value="" id="protect3" data-food-key="Nuts" data-food-group="nuts">
                                                        <label class="form-check-label" for="protect3">Nuts</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Nuts"></div>
                                                </div>
                                                <!-- <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][nuts_and_seeds][Seeds][]" value="" id="protect4" data-food-key="Seeds" data-food-group="nuts_and_seeds">
                                                        <label class="form-check-label" for="protect4">Seeds</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Seeds"></div>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>4. Seeds</h5>
                                        <input type="hidden" name="questions[food_preference][seeds]" value="Seeds" />
                                        <div class="form-floating my-3">
                                             <div class="row row-cols-1 row-cols-md-4 g-2">
                                                <!-- <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][nuts_and_seeds][Nuts][]" value="" id="protect3" data-food-key="Nuts" data-food-group="nuts_and_seeds">
                                                        <label class="form-check-label" for="protect3">Nuts</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Nuts"></div>
                                                </div> -->
                                                <div class="col">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][Seeds][]" value="" id="protect4" data-food-key="Seeds" data-food-group="seeds">
                                                        <label class="form-check-label" for="protect4">Seeds</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Seeds"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <h5>Protein rich foods </h5> -->
                                    <div class="col-md">
                                        <h5>5. Eggs</h5>
                                        <input type="hidden" name="questions[food_preference][eggs]" value="Eggs" />
                                        <div class="form-floating my-3">
                                            <div class="form-check ">
                                                <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][eggs][]" value="" id="protein1" data-food-key="Eggs" data-food-group="eggs">
                                                <label class="form-check-label" for="protein1">Eggs</label>
                                            </div>
                                            <div class="food-dropdown-wrapper" data-wrapper-for="Eggs"></div>
                                        </div>
                                        <hr>
                                        <h5>6. Meat</h5>
                                        <input type="hidden" name="questions[food_preference][meat]" value="Meat" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][meat][Beef][]" value="" id="protein02" data-food-key="Beef" data-food-group="meat">
                                                        <label class="form-check-label" for="protein02">Beef</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Beef"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][meat][Chicken][]" value="" id="protein3" data-food-key="Chicken" data-food-group="meat">
                                                        <label class="form-check-label" for="protein3">Chicken</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Chicken"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][meat][Lamb][]" value="" id="protein4" data-food-key="Lamb" data-food-group="meat">
                                                        <label class="form-check-label" for="protein4">Lamb</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Lamb"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][meat][Pork][]" value="" id="protein5" data-food-key="Pork" data-food-group="meat">
                                                        <label class="form-check-label" for="protein5">Pork</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Pork"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][meat][Turkey][]" value="" id="protein6" data-food-key="Turkey" data-food-group="meat">
                                                        <label class="form-check-label" for="protein6">Turkey</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Turkey"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][meat][Deli Meat][]" value="" id="protein7" data-food-key="Deli Meat" data-food-group="meat">
                                                        <label class="form-check-label" for="protein7">Deli Meat</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Deli Meat"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>7. Plant Based</h5>
                                        <input type="hidden" name="questions[food_preference][plant_based]" value="Plant Based" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][plant_based][Meat Alternatives][]" value="" id="protein-2" data-food-key="Meat Alternatives" data-food-group="plant_based">
                                                        <label class="form-check-label" for="protein-2">Meat Alternatives</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Meat Alternatives"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>8. Seafood</h5>
                                        <input type="hidden" name="questions[food_preference][seafood]" value="Seafood" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][seafood][Fresh Seafood][]" value="" id="seafood1" data-food-key="Fresh Seafood" data-food-group="seafood">
                                                        <label class="form-check-label" for="seafood1">Fresh Seafood</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Fresh Seafood"></div>

                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][seafood][Tinned Seafood][]" value="" id="seafood2" data-food-key="Tinned Seafood" data-food-group="seafood">
                                                        <label class="form-check-label" for="seafood2">Tinned Seafood</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Tinned Seafood"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>9. Dairy</h5>
                                        <input type="hidden" name="questions[food_preference][dairy]" value="Dairy" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][dairy][Milk][]" value="" id="dairy1" data-food-key="Milk" data-food-group="dairy">
                                                        <label class="form-check-label" for="dairy1">Milk</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Milk"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][dairy][Cheese][]" value="" id="dairy3" data-food-key="Cheese" data-food-group="dairy">
                                                        <label class="form-check-label" for="dairy3">Cheese</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Cheese"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][dairy][Yoghurt][]" value="" id="dairy2" data-food-key="Yoghurt" data-food-group="dairy">
                                                        <label class="form-check-label" for="dairy2">Yoghurt</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Yoghurt"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h5>10. Fruit</h5>
                                        <input type="hidden" name="questions[food_preference][fruit]" value="Fruit" />
                                        <!-- <div class="form-check">
                                            <input class="form-check-input food-checkbox" type="checkbox" id="selectAllFruits">
                                            <label class="form-check-label fw-bold" for="selectAllFruits">Select All</label>
                                        </div> -->
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check ">
                                                            <input class="form-check-input food-checkbox fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="" id="protein20" data-food-key="Fruit" data-food-group="fruit">
                                                        <label class="form-check-label" for="protein20">Fruit</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Fruit"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>11. Vegetables</h5>
                                        <input type="hidden" name="questions[food_preference][vegetables]" value="Vegetables" />
                                        <!-- <div class="form-check">
                                            <input class="form-check-input food-checkbox" type="checkbox" id="selectAllVegetables">
                                            <label class="form-check-label fw-bold" for="selectAllVegetables">Select All</label>
                                        </div> -->
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="" id="protein21" data-food-key="Vegetables" data-food-group="vegetables">
                                                        <label class="form-check-label" for="protein21">Vegetables</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Vegetables"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>12. Oils / Butter</h5>
                                        <input type="hidden" name="questions[food_preference][oils_butter]" value="Oils / Butter" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][oils_butter][Butters][]" value="" id="protein22" data-food-key="Butters" data-food-group="oils_butter">
                                                        <label class="form-check-label" for="protein22">Butters</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Butters"></div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][oils_butter][Oils][]" value="" id="protein4" data-food-key="Oils" data-food-group="oils_butter">
                                                        <label class="form-check-label" for="protein4">Oils</label>
                                                    </div>
                                                    <div class="food-dropdown-wrapper" data-wrapper-for="Oils"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="5">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="7">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box" id="div7">
                        <div class="card">
                            <div class="bg-white card-header p-4 pb-3">
                                <h4 class="m-0">Food Preference List</h4>
                                <p class="mt-3 text-danger">* Select foods you currently eat OR are open to try</p>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <!-- Snacks Section -->
                                    <div class="col-12 mb-4">
                                        <h5>Snacks</h5>
                                        <input type="hidden" name="questions[food_preference][snacks]" value="Snacks" />
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][snacks][Fruit & Nut bars][]" value="" id="snack_fruit_nut_bars_checkbox" data-food-key="Fruit & Nut bars" data-food-group="snacks">
                                                <label for="snack_fruit_nut_bars_checkbox" class="form-label">Fruit & Nut bars</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Fruit & Nut bars"></div>
                                                <!-- <input type="text" id="snack_fruit_nut_bars" name="ans[food_preference][snacks][fruit_nut_bars]" class="form-control" placeholder=""> -->
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][snacks][Muesli bars][]" value="" id="snack_muesli_bars_checkbox" data-food-key="Muesli bars" data-food-group="snacks">
                                                <label for="snack_muesli_bars_checkbox" class="form-label">Muesli bars</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Muesli bars"></div>
                                                <!-- <input type="text" id="snack_muesli_bars" name="ans[food_preference][snacks][muesli_bars]" class="form-control" placeholder=""> -->
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][snacks][Other Snacks][]" value="" id="snack_popcorn_checkbox" data-food-key="Other Snacks" data-food-group="snacks">
                                                <label for="snack_popcorn_checkbox" class="form-label">Other Snacks</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Other Snacks"></div>
                                                <!-- <input type="text" id="snack_popcorn" name="ans[food_preference][snacks][popcorn]" class="form-control" placeholder=""> -->
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <!-- <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][Chocolate bars]" value="" id="snack_chocolate_bars_checkbox" data-food-key="Chocolate bars" data-food-group="snacks"> -->
                                                <label for="snack_chocolate_bars_checkbox" class="form-label">Chocolate bars</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Chocolate bars"></div>
                                                <input type="text" id="snack_chocolate_bars" name="ans[food_preference][snacks][Chocolate Bars]" class="form-control" placeholder="Eg: Mars Bar, Picnic, Chocolate mud cake">
                                                <small class="form-text text-muted">(Use commas to separate items. Eg: Mars Bar, Picnic, Chocolate mud cake.)</small>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][Lollies]" value="" id="snack_lollies_checkbox" data-food-key="Lollies" data-food-group="snacks"> -->
                                                <label for="snack_lollies_checkbox" class="form-label">Lollies</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Lollies"></div>
                                                <input type="text" id="snack_lollies" name="ans[food_preference][snacks][Lollies]" class="form-control" placeholder="Eg: Snakes, Sour Worms">
                                                <small class="form-text text-muted">(Use commas to separate items. Eg: Snakes, Sour Worms.)</small>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Drinks Section -->
                                    <div class="col-12 mb-4">
                                        <h5>Drinks</h5>
                                        <!-- <h6>Cold Drinks</h6> -->
                                        <input type="hidden" name="questions[food_preference][drink]" value="Drinks" />

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][drink][Cold Drinks][]" value="" id="drink_iced_coffee_checkbox" data-food-key="Cold Drinks" data-food-group="drink">
                                                <label for="drink_iced_coffee" class="form-label">Cold Drinks</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Cold Drinks"></div>
                                                <!-- <input type="text" id="drink_iced_coffee" name="ans[food_preference][cold_drink][iced_coffee]" class="form-control" placeholder=""> -->
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input food-checkbox" type="checkbox" name="ans[food_preference][drink][Hot Drinks][]" value="" id="drink_iced_tea_checkbox" data-food-key="Hot Drinks" data-food-group="drink">
                                                <label for="drink_iced_tea" class="form-label">Hot Drinks</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Hot Drinks"></div>
                                                <!-- <input type="text" id="drink_iced_tea" name="ans[food_preference][cold_drink][iced_tea]" class="form-control" placeholder="> -->
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Cuisines Section -->
                                    <div class="col-12 mb-4">
                                        <h5>Cuisines</h5>
                                        <p>Select your favourite cuisines</p>
                                        <input type="hidden" name="questions[food_preference][cuisines]" value="Cuisines" />

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input cuisines-checkbox" type="checkbox" name="ans[food_preference][cuisines][Japanese][]" value="" id="cuisine_japanese_checkbox" data-food-key="Japanese" data-food-group="cuisines">
                                                <label for="cuisine_japanese" class="form-label">Japanese</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Japanese"></div>
                                                <input type="text" id="cuisine_japanese" name="ans[food_preference][cuisines][Japanese]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input cuisines-checkbox" type="checkbox" name="ans[food_preference][cuisines][Chinese][]" value="" id="cuisine_chinese_checkbox" data-food-key="Chinese" data-food-group="cuisines">
                                                <label for="cuisine_chinese" class="form-label">Chinese</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Chinese"></div>
                                                <input type="text" id="cuisine_chinese" name="ans[food_preference][cuisines][Chinese]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input cuisines-checkbox" type="checkbox" name="ans[food_preference][cuisines][Thai][]" value="" id="cuisine_thai_checkbox" data-food-key="Thai" data-food-group="cuisines">
                                                <label for="cuisine_thai" class="form-label">Thai</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Thai"></div>
                                                <input type="text" id="cuisine_thai" name="ans[food_preference][cuisines][Thai]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input cuisines-checkbox" type="checkbox" name="ans[food_preference][cuisines][Indian][]" value="" id="cuisine_indian_checkbox" data-food-key="Indian" data-food-group="cuisines">
                                                <label for="cuisine_indian" class="form-label">Indian</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Indian"></div>
                                                <input type="text" id="cuisine_indian" name="ans[food_preference][cuisines][Indian]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input cuisines-checkbox" type="checkbox" name="ans[food_preference][cuisines][Italian][]" value="" id="cuisine_italian_checkbox" data-food-key="Italian" data-food-group="cuisines">
                                                <label for="cuisine_italian" class="form-label">Italian</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Italian"></div>
                                                <input type="text" id="cuisine_italian" name="ans[food_preference][cuisines][Italian]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input cuisines-checkbox" type="checkbox" name="ans[food_preference][cuisines][mexican][]" value="" id="cuisine_mexican_checkbox" data-food-key="Mexican" data-food-group="cuisines">
                                                <label for="cuisine_mexican" class="form-label">Mexican</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Mexican"></div>
                                                <input type="text" id="cuisine_mexican" name="ans[food_preference][cuisines][mexican]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input cuisines-checkbox" type="checkbox" name="ans[food_preference][cuisines][Greek][]" value="" id="cuisine_greek_checkbox" data-food-key="Greek" data-food-group="cuisines">
                                                <label for="cuisine_greek" class="form-label">Greek</label>
                                                <div class="food-dropdown-wrapper" data-wrapper-for="Greek"></div>
                                                <input type="text" id="cuisine_greek" name="ans[food_preference][cuisines][Greek]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <!-- <input class="form-check-input cuisines-checkbox" type="checkbox" name="ans[food_preference][cuisines][other]" value="Others" id="cuisine_other_checkbox"> -->
                                                <label for="cuisine_other" class="form-label">Other</label>
                                                <input type="text" id="cuisine_other" name="ans[food_preference][cuisines][other]" class="form-control" placeholder="What are your favourite dishes?">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="6">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="8">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box " id="div8">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Nutrition Goals</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-6">
                                    <h5>Which of these do you want help with?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[nutrition_goals][related_goals]" value="Which of these do you want help with?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Sports performance & recovery" id="relatedgoals1">
                                                <label class="form-check-label" for="relatedgoals1">Sports performance & recovery</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Health & immunity" id="relatedgoals5">
                                                <label class="form-check-label" for="relatedgoals5">Health & immunity</label>
                                            </div>

                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Comp day nutrition" id="relatedgoals9">
                                                <label class="form-check-label" for="relatedgoals9">Comp day nutrition</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Weight loss" id="relatedgoals6">
                                                <label class="form-check-label" for="relatedgoals6">Weight loss</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Mass gain" id="relatedgoals2">
                                                <label class="form-check-label" for="relatedgoals2">Mass gain</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Leaner body composition" id="relatedgoals3">
                                                <label class="form-check-label" for="relatedgoals3">Leaner body composition</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Reducing fatigue" id="relatedgoals4">
                                                <label class="form-check-label" for="relatedgoals4">Reducing fatigue</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Injury nutrition" id="relatedgoals7">
                                                <label class="form-check-label" for="relatedgoals7">Injury nutrition</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Gut issues" id="relatedgoals8">
                                                <label class="form-check-label" for="relatedgoals8">Gut issues</label>
                                            </div>

                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Other" id="relatedgoals10">
                                                <label class="form-check-label" for="relatedgoals10">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>What do you want help with?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[nutrition_goals][like_assistance_with]" value="What do you want help with?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Healthier eating habits" id="likeassistancewith1">
                                                <label class="form-check-label" for="likeassistancewith1">Healthier eating habits
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Confirming I'm on the right track" id="likeassistancewith2">
                                                <label class="form-check-label" for="likeassistancewith2">Confirming I'm on the right track</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Accountability & support" id="likeassistancewith3">
                                                <label class="form-check-label" for="likeassistancewith3">Accountability & support</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Cutting through social media confusion" id="likeassistancewith4">
                                                <label class="form-check-label" for="likeassistancewith4">Cutting through social media confusion</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Other" id="likeassistancewith5">

                                                <label class="form-check-label" for="likeassistancewith5">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>What's your biggest nutrition challenge?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[nutrition_goals][biggest_nutrition_challenge]" value="What's your biggest nutrition challenge?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Cravings" id="biggestnutritionchallenge1">
                                                <label class="form-check-label" for="biggestnutritionchallenge1">Cravings</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Not sure what to eat" id="biggestnutritionchallenge2">
                                                <label class="form-check-label" for="biggestnutritionchallenge2">Not sure what to eat</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="No time to prep meals" id="biggestnutritionchallenge3">
                                                <label class="form-check-label" for="biggestnutritionchallenge3">No time to prep meals</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Eating out too much" id="biggestnutritionchallenge4">
                                                <label class="form-check-label" for="biggestnutritionchallenge4">Eating out too much</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Emotional/stress eating" id="biggestnutritionchallenge5">
                                                <label class="form-check-label" for="biggestnutritionchallenge5">Emotional/stress eating</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Family or peer pressure" id="biggestnutritionchallenge6">
                                                <label class="form-check-label" for="biggestnutritionchallenge6">Family or peer pressure</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Big portions" id="biggestnutritionchallenge7">
                                                <label class="form-check-label" for="biggestnutritionchallenge7">Big portions</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Lack of planning" id="biggestnutritionchallenge8">
                                                <label class="form-check-label" for="biggestnutritionchallenge8">Lack of planning</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Poor planning" id="biggestnutritionchallenge9">
                                                <label class="form-check-label" for="biggestnutritionchallenge9">Poor planning</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Snacking when not hungry" id="biggestnutritionchallenge10">
                                                <label class="form-check-label" for="biggestnutritionchallenge10">Snacking when not hungry</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Sweet tooth" id="biggestnutritionchallenge11">
                                                <label class="form-check-label" for="biggestnutritionchallenge11">Sweet tooth</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Social media influence" id="biggestnutritionchallenge14">
                                                <label class="form-check-label" for="biggestnutritionchallenge14">Social media influence</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Unsure" id="biggestnutritionchallenge12">
                                                <label class="form-check-label" for="biggestnutritionchallenge12">Unsure</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Other" id="biggestnutritionchallenge13">
                                                <label class="form-check-label" for="biggestnutritionchallenge13">Others:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Where do you get your nutrition info?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[nutrition_goals][getnutrition]" value="Where do you get your nutrition info?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Coach" id="getnutrition1">
                                                <label class="form-check-label" for="getnutrition1">
                                                    Coach
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Parents" id="getnutrition-1">
                                                <label class="form-check-label" for="getnutrition-1">
                                                    Parents
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Siblings" id="getnutrition2">
                                                <label class="form-check-label" for="getnutrition2">
                                                    Siblings
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Friends" id="getnutrition-2">
                                                <label class="form-check-label" for="getnutrition-2">
                                                    Friends
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Instagram" id="getnutrition3">
                                                <label class="form-check-label" for="getnutrition3">
                                                    Instagram
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Facebook" id="getnutrition4">
                                                <label class="form-check-label" for="getnutrition4">
                                                    Facebook
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Google" id="getnutrition5">
                                                <label class="form-check-label" for="getnutrition5">
                                                    Google
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="TikTok" id="getnutrition-4">
                                                <label class="form-check-label" for="getnutrition-4">
                                                    TikTok
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Other" id="getnutrition-5">
                                                <label class="form-check-label" for="getnutrition-5">
                                                    Other:
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>What are the 3 top things you want to obtain from the consultation? </label>
                                            <input type="hidden" name="questions[nutrition_goals][topthings]" value="What are the 3 top things you want to obtain from the consultation?" />
                                            <input type="text" class="form-control" name="ans[nutrition_goals][topthings]" id="topthings" placeholder="">
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="7">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step pt-2 pb-2 px-4 py-4" target="9">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box" id="div9">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Sport & Training</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <h5>What type of physical activity do you mainly do or compete in? (more than one can apply)<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[physical_activity_and_exercise][physical_activity]" value="What type of physical activity do you mainly do or compete in? (more than one can apply)" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][physical_activity][]" id="physicalActivity-1" value="Action Sports - Surfing, Freestyle BMX, Skateboarding">
                                                <label class="form-check-label" for="physicalActivity-1">Action Sports - Surfing, Freestyle BMX, Skateboarding</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][physical_activity][]" id="physicalActivity-2" value="Combat sports- Boxing, Brazilian Jiu Jitsu, Martial arts">
                                                <label class="form-check-label" for="physicalActivity-2">Combat sports- Boxing, Brazilian Jiu Jitsu, Martial arts</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][physical_activity][]" id="physicalActivity-3" value="Team sports - rugby league/union, volleyball, touch football, soccer">
                                                <label class="form-check-label" for="physicalActivity-3">Team sports - rugby league/union, volleyball, touch football, soccer</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][physical_activity][]" id="physicalActivity-4" value="Cardiovascular exercise such as jogging/running, cycling, swimming, hiking">
                                                <label class="form-check-label" for="physicalActivity-4">Cardiovascular exercise such as jogging/running, cycling, swimming, hiking</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][physical_activity][]" id="physicalActivity-5" value="Weight (resistance) training">
                                                <label class="form-check-label" for="physicalActivity-5">Weight (resistance) training</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][physical_activity][]" id="physicalActivity-6" value="Other">
                                                <label class="form-check-label" for="physicalActivity-6">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <h5>On average, how many days per week do you train, and at what intensity?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[physical_activity_and_exercise][intensity]" value="On average, how many days per week do you train, and at what intensity?" />
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="">Intensity Level</th>
                                                        <th class="">Days per Week (0-7)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Low Intensity</td>
                                                        <td class=""><input type="number" name="ans[physical_activity_and_exercise][intensity][Low Intensity]" value="" id="perweekintensity-1" style="width: 60px; height: 40px; text-align: center; padding: 0;" min="0" max="7"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Moderate Intensity</td>
                                                        <td class=""><input type="number" name="ans[physical_activity_and_exercise][intensity][Moderate Intensity]" value="" id="perweekintensity-4" style="width: 60px; height: 40px; text-align: center; padding: 0;" min="0" max="7"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>High Intensity</td>
                                                        <td class=""><input type="number" name="ans[physical_activity_and_exercise][intensity][High Intensity]" value="" id="perweekintensity-7" style="width: 60px; height: 40px; text-align: center; padding: 0;" min="0" max="7"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <h5>Do you <strong class="text-primary">CURRENTLY</strong> use any exercise or nutrition trackers/apps?<span class="text-danger required-asterisk">*</span></h5>
                                        <input type="hidden" name="questions[physical_activity_and_exercise][tracking_device]" value="Do you CURRENTLY use any exercise or nutrition trackers/apps?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-5" value="No">
                                                <label class="form-check-label" for="trackingDevices-5">No</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-1" value="Garmin or similar watch">
                                                <label class="form-check-label" for="trackingDevices-1">Garmin or similar watch</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-2" value="Oura ring or similar">
                                                <label class="form-check-label" for="trackingDevices-2">Oura ring or similar</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-3" value="Whoop band">
                                                <label class="form-check-label" for="trackingDevices-3">Whoop band</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-4" value="MyFitnessPal or similar">
                                                <label class="form-check-label" for="trackingDevices-4">MyFitnessPal or similar</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-6" value="Other">
                                                <label class="form-check-label" for="trackingDevices-6">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3 d-none" id="trackingDetailsField">
                                            <label>What do you mainly track? (e.g. exercise, food ,sleep)<span class="text-danger required-asterisk">*</span></label>
                                            <input type="hidden" name="questions[physical_activity_and_exercise][track]" value="If answered yes to the above question, what do you mainly track? (e.g. exercise, food, sleep)" />
                                            <input type="text" class="form-control" name="ans[physical_activity_and_exercise][track]" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                            <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step pt-2 pb-2 px-4 py-4" target="8">Back</button>
                                <button type="button" class="btn btn-primary ms-auto next-step pt-2 pb-2 px-4 py-4" id="submit-nutrition-form">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Thank You Modal -->
    <div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
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
                    <input type="hidden" name="redirect_url" id="thankYouModalRedirectUrl" value="">
                    <p class="mb-2">Your form is submitted.</p>
                    <p class="mb-4">Your plan will be created by Kez and sent via email in the coming days.</p>
                    <button type="button" class="btn btn-primary w-50" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Food Selection Modal -->
    <div class="modal" id="foodModal" tabindex="-1" aria-labelledby="foodModalLabel" aria-hidden="true"  data-bs-backdrop="static"
     data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width: 50%;">
            <div class="modal-content" style="overflow-x: hidden; overflow-y: auto;">
                <div class="modal-header">
                    <h5 class="modal-title" id="foodModalLabel">Select Food Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-x: hidden; overflow-y: auto; max-height: 70vh;">

                    <div class="row" id="foodListContainer">
                    <!-- Items inserted here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary pt-2 pb-2" id="confirmFoodSelection">
                        Confirm Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        $('#foodModal').on('shown.bs.modal', function () {
            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
        });

        $('#sport_category').on('change', function () {
            let categoryId = $(this).val();
            console.log(categoryId);
            $('#sport_game').html('<option value="">Loading...</option>');

            if (categoryId) {
                $.ajax({
                    url: "{{ route('front.get-sports-games') }}",
                    method: "GET",
                    data: { category: categoryId },
                    success: function (response) {
                        let options = '<option value="">Select Sport Game</option>';
                        if (Array.isArray(response)) {
                            response.forEach(function (game) {
                                options += `<option value="${game.name}">${game.name}</option>`;
                            });
                        }
                        $('#sport_game').html(options);
                    },
                    error: function () {
                        $('#sport_game').html('<option value="">Error loading games</option>');
                    }
                });
            } else {
                $('#sport_game').html('<option value="">Select Sport Game</option>');
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const rankOptions = document.querySelectorAll(".rank-option");

        rankOptions.forEach((radio) => {
            radio.addEventListener("change", function () {
                const selectedRank = this.value;
                const selectedCategory = this.name;

                // Find previous selection with the same rank
                rankOptions.forEach((option) => {
                    if (option !== this && option.value === selectedRank && option.checked) {
                        option.checked = false; // Uncheck previous selection
                    }
                });
            });
        });
    });

    function createOtherInput(input, value = '') {
        let otherInputId = `${input.id}-input`;
        let otherInput = document.getElementById(otherInputId);

        const trackingDetailsField = document.querySelector('[name="ans[physical_activity_and_exercise][track]"]').closest('.col-md-6');
        const trackingDetailsInput = trackingDetailsField.querySelector('input');

        if (!otherInput) {
            otherInput = document.createElement('input');
            otherInput.type = 'text';
            otherInput.className = 'form-control mt-2';
            otherInput.name = `${input.name}_other`;
            otherInput.id = otherInputId;
            otherInput.placeholder = 'Please specify...';
            input.parentNode.appendChild(otherInput);

            console.log(`🟠 Created "Other" Input for [${input.name}]`);

            // otherInput.addEventListener('keyup', function () {
            //     const inputValue = this.value.trim().toLowerCase();
            //     handleTrackingDetailsVisibility(inputValue);
            // });
        }

        otherInput.value = value;
    }

    document.querySelectorAll('input[type="radio"][value="Other"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            let otherInputId = `${this.id}-input`;
            let otherInput = document.getElementById(otherInputId);

            if (this.checked) {
                if (!otherInput) {
                    createOtherInput(this);
                }
            } else {
                if (otherInput) {
                    otherInput.remove();
                }

                const trackingDetailsField = document.querySelector('[name="ans[physical_activity_and_exercise][track]"]').closest('.col-md-6');
                const trackingDetailsInput = trackingDetailsField.querySelector('input');

                trackingDetailsField.style.display = 'block';
                trackingDetailsInput.disabled = false;
                trackingDetailsInput.setAttribute('required', 'required');
            }
        });
    });

    // Handle checkbox changes
    document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            if (this.value === 'Other') {
                let otherInputId = `${this.id}-input`;
                let otherInput = document.getElementById(otherInputId);

                if (this.checked) {
                    if (!otherInput) {
                        createOtherInput(this);
                    }
                } else {
                    if (otherInput) {
                        otherInput.remove();
                    }
                }
            }
        });
    });

    document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            let radioName = this.name;

            // Target the tracking input field wrapper
            const trackingDetailsField = document.querySelector('[name="ans[physical_activity_and_exercise][track]"]').closest('.col-md-6');
            const trackingDetailsInput = trackingDetailsField.querySelector('input');

            // Check if current question is the tracking device one
            if (radioName === 'ans[physical_activity_and_exercise][tracking_device]') {
                if (this.value === 'No') {
                    // Hide the follow-up input
                    trackingDetailsField.style.display = 'none';
                    trackingDetailsInput.disabled = true;
                    trackingDetailsInput.removeAttribute('required');
                    trackingDetailsInput.value = '';
                } else {
                    // Show the follow-up input
                    trackingDetailsField.style.display = 'block';
                    trackingDetailsInput.disabled = false;
                    trackingDetailsInput.setAttribute('required', 'required');
                }
            }

            // Remove other radio's dynamic input fields (if any)
            document.querySelectorAll(`input[type="radio"][name="${radioName}"]`).forEach(function (otherRadio) {
                if (otherRadio !== radio) {
                    let otherInputId = `${otherRadio.id}-input`;
                    let otherInput = document.getElementById(otherInputId);
                    if (otherInput) {
                        otherInput.remove();
                    }
                }
            });
        });
    });

    $(document).ready(function () {
        $('.cuisines-checkbox').on('change', function () {
            const $checkbox = $(this);
            const checkboxId = $checkbox.attr('id'); // e.g., cuisine_japanese_checkbox
            const cuisineKey = checkboxId.replace('_checkbox', ''); // e.g., cuisine_japanese
            const $input = $('#' + cuisineKey);

            if ($checkbox.is(':checked')) {
                $input.addClass('required-if-checked');
                $input.attr('required', true);
                $input.attr('placeholder', 'What are your favourite dishes?');
            } else {
                $input.removeClass('required-if-checked');
                $input.removeAttr('required');
                $input.attr('placeholder', '');
                $input.css('border', ''); // remove red border if previously invalid
                $input.val('');
            }
        });
    });

    // Initialize the dropdown with Select2 for search functionality
    $('.select2').select2({
        placeholder: 'Select foods',
        allowClear: true
    })

    // ✅ Modified JavaScript for food selection with correct prefill logic
    let selectedFoodKey = '';
    let selectedFoodGroup = '';
    let targetWrapper = null;
    let activeFoodCheckbox = null;
    let userConfirmed = false;

    document.addEventListener("DOMContentLoaded", () => {
        const stepCircles = document.querySelectorAll('.tab-steps');
        const stepTabs = document.querySelectorAll(".step-tab-box");
        const showStepButtons = document.querySelectorAll('.showStepTab');
        const submitButton = document.getElementById('submit-nutrition-form');
        const form = document.getElementById('nutrition-screen-form');

        let currentStep = 0; // Track the active step index
        let targetStep = null;

        const startingStep = {{ $nextStep ?? 1 }};
        const stepDataRaw = @json($stepData->toArray());
        const stepData = Object.values(stepDataRaw).flat();
        currentStep = startingStep - 1;

        // ✅ Initial display moved here
        showStep(currentStep);
        prefillData(currentStep);

        const alcoholData = stepData.find(item => {
            if (item.question === "If 18+, do you drink alcohol?" && item.answer) {
                try {
                    const parsedAnswer = JSON.parse(item.answer);
                    return parsedAnswer && 'days' in parsedAnswer && 'drinks' in parsedAnswer;
                } catch (e) {
                    return false;
                }
            }
            return false;
        });

        if (alcoholData && alcoholData.answer) {
            // Try to parse the answer string
            let parsedAnswer;
            try {
                parsedAnswer = JSON.parse(alcoholData.answer);
            } catch (e) {
                parsedAnswer = null;
            }

            if (parsedAnswer && parsedAnswer.days && parsedAnswer.drinks) {
                $('#drink_alcohol_yes').prop('checked', true);
                $('#drinkAlcoholInput').show();
                $('#drink_alcohol_days').prop('required', true);
                $('#drink_alcohol_drinks').prop('required', true);

                // Prefill the input fields
                $('#drink_alcohol_days').val(parsedAnswer.days);
                $('#drink_alcohol_drinks').val(parsedAnswer.drinks);
            } else {
                $('#drink_alcohol_no').prop('checked', true);
                $('#drinkAlcoholInput').hide();
                $('#drink_alcohol_days').prop('required', false);
                $('#drink_alcohol_drinks').prop('required', false);
            }
        } else {
            $('#drink_alcohol_no').prop('checked', false);
            $('#drinkAlcoholInput').hide();
            $('#drink_alcohol_days').prop('required', false);
            $('#drink_alcohol_drinks').prop('required', false);
        }

        // Handle radio button changes
        $('input[name="ans[dietary_information][drink_alcohol]"]').on('change', function() {
            if ($('#drink_alcohol_yes').prop('checked')) {
                $('#drinkAlcoholInput').show();
                $('#drink_alcohol_days').prop('required', true);
                $('#drink_alcohol_drinks').prop('required', true);
            } else {
                $('#drinkAlcoholInput').hide();
                $('#drink_alcohol_days').prop('required', false);
                $('#drink_alcohol_drinks').prop('required', false);
                $('#drink_alcohol_days').val('');
                $('#drink_alcohol_drinks').val('');
            }
        });

        const trackerUsage = stepData.find(item =>
            item.question === "Do you CURRENTLY use any exercise or nutrition trackers/apps?" &&
            item.answer
        );

        if (trackerUsage) {
            let trackerAnswer = trackerUsage.answer;

            // Parse answer if it's a stringified JSON
            if (typeof trackerAnswer === 'string') {
                try {
                    trackerAnswer = JSON.parse(trackerAnswer);
                } catch (e) {
                    // Leave as string if JSON.parse fails
                }
            }

            if (trackerAnswer === "No") {
                // Hide the "What do you mainly track?" field
                $('input[name="ans[physical_activity_and_exercise][track]"]').closest('.col-md-6').hide();
            } else {
                $('input[name="ans[physical_activity_and_exercise][track]"]').closest('.col-md-6').show();
            }
        }

        const bloodTestData = stepData.find(item =>
            item.question === "Have you recently had a blood test?" && item.answer
        );

        if (bloodTestData) {
            let bloodAnswer = bloodTestData.answer;

            // Try to parse JSON if it's a string
            if (typeof bloodAnswer === 'string') {
                try {
                    bloodAnswer = JSON.parse(bloodAnswer);
                } catch (e) {
                    console.warn("Failed to parse bloodAnswer as JSON", e);
                }
            }

            if (bloodAnswer && typeof bloodAnswer === 'object') {
                if (bloodAnswer.answer === "Yes") {
                    $('#bloodTestYes').prop('checked', true);
                    $('#bloodTestDateSection').show();
                    $('#fileUploadSection').show();

                    if (bloodAnswer.date) {
                        console.log("Setting bloodTestDate to:", bloodAnswer.date);
                        $('#bloodTestDate').val(bloodAnswer.date.trim().toLowerCase());
                    }
                } else if (bloodAnswer.answer === "No") {
                    $('#bloodTestNo').prop('checked', true);
                    $('#bloodTestDateSection').hide();
                    $('#fileUploadSection').hide();
                }
            } else {
                console.warn("bloodAnswer is not an object:", bloodAnswer);
            }
        }

        const bodyCompositionData = stepData.find(item =>
            item.question === "Have you recently undertaken a body composition assessment (measure of muscle, body fat)?" && item.answer
        );

        if (bodyCompositionData) {
            let bodyCompositionAnswer = bodyCompositionData.answer;

            // Parse if answer is stringified JSON
            if (typeof bodyCompositionAnswer === 'string') {
                try {
                    bodyCompositionAnswer = JSON.parse(bodyCompositionAnswer);
                } catch (e) {
                    // If parsing fails, keep original
                    console.warn("Failed to parse bodyCompositionAnswer as JSON", e);
                }
            }

            if (bodyCompositionAnswer && typeof bodyCompositionAnswer === 'object') {
                if (bodyCompositionAnswer.answer === "Yes") {
                    $('#bodyCompositionYes').prop('checked', true);
                    $('#bodyCompositionDateSection').show();
                    $('#bodyCompositionFileInput').show();

                    if (bodyCompositionAnswer.date) {
                        $('#bodyCompositionDate').val(bodyCompositionAnswer.date.trim().toLowerCase());
                    }
                } else if (bodyCompositionAnswer.answer === "No") {
                    $('#bodyCompositionNo').prop('checked', true);
                    $('#bodyCompositionDateSection').hide();
                    $('#bodyCompositionFileInput').hide();
                }
            } else {
                console.warn("bloodAnswer is not an object:", bloodAnswer);
            }
        }

        document.body.addEventListener("click", function (e) {
            const button = e.target.closest(".showStepTab");
            const stepCircle = e.target.closest(".tab-steps");

            // Handle Next/Back buttons
            if (button) {
                const targetStep = parseInt(button.getAttribute("target")) - 1;
                if (targetStep > currentStep && !validateStep(currentStep)) return;

                if (targetStep > currentStep) {
                    saveStepData(currentStep, function (success) {
                        if (success) {
                            currentStep = targetStep;
                            showStep(currentStep);
                            prefillData(currentStep);
                            prefillFoodInputsOnStep(currentStep);
                        }
                    });
                } else {
                    currentStep = targetStep;
                    showStep(currentStep);
                    prefillData(currentStep);
                    prefillFoodInputsOnStep(currentStep);
                }
            }

            if (button) {
                const targetStep = parseInt(button.getAttribute("target")) - 1;
                if (targetStep > currentStep && !validateStep(currentStep)) return;

                if (targetStep > currentStep) {
                    saveStepData(currentStep, function (success) {
                        if (success) {
                            currentStep = targetStep;
                            showStep(currentStep);

                            // ✅ Immediately prefill step data from server/database
                            prefillData(currentStep);
                        }
                    });
                } else {
                    currentStep = targetStep;
                    showStep(currentStep);
                    prefillData(currentStep); // ✅ Even on back
                }
            }

            // Handle tab-circle click navigation
            if (stepCircle) {
                const steps = Array.from(document.querySelectorAll('.tab-steps'));
                const targetStep = steps.indexOf(stepCircle);

                // Only allow navigating to a previously completed step
                if (targetStep <= currentStep) {
                    currentStep = targetStep;
                    showStep(currentStep);
                    prefillData(currentStep);
                    prefillFoodInputsOnStep(currentStep);
                }
            }
        });

        function validateStep(stepIndex) {
            const stepTab = stepTabs[stepIndex];
            const inputs = stepTab.querySelectorAll("input, select, textarea");
            let isValid = true;

            // ✅ Special case: group validation for intensity fields
            const intensityInputs = stepTab.querySelectorAll('input[name^="ans[physical_activity_and_exercise][intensity]"]');
            if (intensityInputs.length > 0) {
                const hasAnyValue = Array.from(intensityInputs).some(i => i.value.trim() !== "");

                intensityInputs.forEach(i => i.style.border = ""); // Reset borders

                if (!hasAnyValue) {
                    intensityInputs.forEach(i => {
                        i.style.border = "1px solid red";
                    });
                    isValid = false;
                }
            }

            inputs.forEach(input => {
                if (input.name === "ans[personal_details][referredBy]") return;
                if (stepIndex === 5) return;

                const isHidden = input.offsetParent === null || getComputedStyle(input).display === 'none';
                if (input.disabled || isHidden) return;

                // Skip intensity group (already validated above)
                if (input.name.startsWith("ans[physical_activity_and_exercise][intensity]")) return;

                input.style.border = "";

                if ((input.type === "text" || input.type === "number" || input.type === "date" ||
                    input.tagName.toLowerCase() === "textarea" || input.tagName.toLowerCase() === "select") &&
                    !input.value.trim()) {

                    if (stepIndex === 6) {
                        const isRequiredIfChecked = input.classList.contains('required-if-checked');
                        const relatedCheckboxId = input.id + '_checkbox';
                        const relatedCheckbox = document.getElementById(relatedCheckboxId);

                        const isActuallyRequired = input.hasAttribute('required') ||
                            (isRequiredIfChecked && relatedCheckbox && relatedCheckbox.checked);

                        if (isActuallyRequired) {
                            input.style.border = "1px solid red";
                            isValid = false;
                        }
                    } else {
                        input.style.border = "1px solid red";
                        isValid = false;
                    }
                }

                if ((input.type === "radio" || input.type === "checkbox") &&
                    !document.querySelector(`input[name="${input.name}"]:checked`)) {

                    if (stepIndex === 6) {
                        const isActuallyRequired = input.hasAttribute('required');
                        if (isActuallyRequired) {
                            input.style.border = "1px solid red";
                            isValid = false;
                        }
                    } else {
                        input.style.border = "1px solid red";
                        isValid = false;
                    }
                }
            });

            return isValid;
        }

        function saveStepData(stepIndex, callback) {
            const stepTab = stepTabs[stepIndex];

            const formData = new FormData();
            formData.append("user_id", document.querySelector('[name="user_id"]').value);
            formData.append("payment_id", document.querySelector('[name="payment_id"]').value);
            formData.append("step", stepIndex + 1);
            formData.append("step_fill", true);

            const inputs = stepTab.querySelectorAll("input, select, textarea");
            inputs.forEach(input => {
                if (!input.name) return;

                if (input.type === "file") {
                    for (let i = 0; i < input.files.length; i++) {
                        formData.append(input.name + '[]', input.files[i]);
                    }
                } else if ((input.type === "checkbox" || input.type === "radio")) {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                    }
                } else {
                    formData.append(input.name, input.value);
                }
            });

            $.ajaxSetup({
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            });

            $.ajax({
                url: "{{ route('front.pre-plan-details.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.success) {
                        if (res.redirect_url) {
                            $('#thankYouModalRedirectUrl').val(res.redirect_url);
                            $('#thankYouModal').modal('show');

                            $.ajax({
                                url: "{{ route('front.questionnaire.send-mail') }}",
                                method: "POST",
                                data: {
                                    user_id: $('input[name="user_id"]').val(),
                                    payment_id: $('input[name="payment_id"]').val(),
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(mailRes) {
                                    if (mailRes.success) {
                                        console.log("Mail sent successfully.");
                                    } else {
                                        console.error("Mail send failed:", mailRes.message);
                                    }
                                },
                                error: function() {
                                    console.error("Error sending mail. Try again.");
                                }
                            });
                        } else {
                            stepTabs.forEach((tab, i) => {
                                tab.style.display = i === targetStep ? "block" : "none";
                            });
                            stepCircles.forEach((circle, i) => {
                                circle.classList.toggle("active", i <= targetStep);
                            });
                            currentStep = targetStep;
                            window.scrollTo({ top: 0, behavior: "smooth" });
                        }
                        callback(true);
                    } else {
                        alert("Save failed: " + res.message);
                        callback(false);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert("Error saving step. Try again.");
                    callback(false);
                }
            });
        }

        function showStep(stepIndex) {
            console.log("Showing Step:", stepIndex);
            console.log(stepTabs);
            stepTabs.forEach((tab, index) => {
                tab.style.display = index === stepIndex ? "block" : "none";
            });

            stepCircles.forEach((circle, index) => {
                circle.classList.toggle("active", index <= stepIndex);
            });

            window.scrollTo({ top: 0, behavior: "smooth" });
        }


        function normalizeString(str) {
            return String(str).trim().toLowerCase().replace(/\s+/g, " ");
        }

        function prefillData(stepIndex) {
            const currentStepNum = stepIndex + 1;
            const currentStepData = stepData.filter(item => parseInt(item.step) === currentStepNum);

            const stepTab = stepTabs[stepIndex];
            const stepFields = stepTab.querySelectorAll("input, select, textarea");
            const hiddenQuestions = stepTab.querySelectorAll("input[type='hidden'][name^='questions']");

            hiddenQuestions.forEach(hiddenInput => {
                const questionText = normalizeString(hiddenInput.value);
                const questionName = hiddenInput.name; // e.g., "questions[dietary_information][flavour_taste]"
                const matchedItem = currentStepData.find(item =>
                    normalizeString(item.question) === questionText
                );
                if (!matchedItem) return;

                let baseFieldName = questionName.replace(/^questions/, "ans");
                console.log("Matched Item:", baseFieldName);
                let answer;
                try {
                    answer = JSON.parse(matchedItem.answer);
                } catch {
                    answer = matchedItem.answer;
                }

                if (answer == null) return;

                // Handle deeply nested objects
                function setFields(fieldNamePrefix, value) {
                    if (typeof value === 'object' && !Array.isArray(value)) {
                        Object.entries(value).forEach(([key, val]) => {
                            if (val == null) return;
                            setFields(`${fieldNamePrefix}[${key}]`, val);
                        });
                    } else if (Array.isArray(value)) {
                        value.forEach(val => {
                            if (val == null) {
                                // Special case: check checkboxes with empty value ("") if array value is null
                                stepFields.forEach(field => {
                                    if (
                                        (field.name === `${fieldNamePrefix}[]` || field.name === fieldNamePrefix) &&
                                        field.type === "checkbox" &&
                                        field.value === ""
                                    ) {
                                        field.checked = true;
                                    }
                                });
                            } else {
                                // Existing logic: match value normally
                                stepFields.forEach(field => {
                                    if (
                                        (field.name === `${fieldNamePrefix}[]` || field.name === fieldNamePrefix) &&
                                        field.value === val
                                    ) {
                                        field.checked = true;
                                    }
                                });
                            }
                        });
                    } else {
                        stepFields.forEach(field => {
                            if (field.name === fieldNamePrefix) {
                                if (field.type === "radio" || field.type === "checkbox") {
                                    field.checked = field.value === String(value);
                                } else {
                                    field.value = value;
                                }
                            }
                        });
                    }
                }

                setFields(baseFieldName, answer);
            });
        }

        submitButton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default form submission
            currentStep = 8;

            if (!validateStep(currentStep)) {
                return;
            }

            console.log('Saving final step...');
            saveStepData(currentStep, function (success) {
                if (!success) {
                    alert("Final step failed to save. Please try again.");
                }
            });
        });

        // ✅ Real-time validation border cleanup
        document.addEventListener("input", function (e) {
            const input = e.target;
            if (input.tagName === "INPUT" || input.tagName === "TEXTAREA" || input.tagName === "SELECT") {
                validateField(input);
            }
        });

        document.addEventListener("change", function (e) {
            const input = e.target;
            if (input.type === "radio" || input.type === "checkbox") {
                validateField(input);
            }
        });

        function validateField(input) {
            if (!input || !input.name) return;

            // ✅ Handle special case: Intensity group
            if (input.name.startsWith("ans[physical_activity_and_exercise][intensity]")) {
                const allIntensityInputs = document.querySelectorAll('input[name^="ans[physical_activity_and_exercise][intensity]"]');

                const hasValue = Array.from(allIntensityInputs).some(i => i.value.trim() !== "");

                allIntensityInputs.forEach(i => {
                    i.style.border = hasValue ? "" : "1px solid red";
                });

                return;
            }

            let isValid = true;

            if (
                input.type === "text" || input.type === "number" || input.type === "date" ||
                input.tagName.toLowerCase() === "textarea" || input.tagName.toLowerCase() === "select"
            ) {
                isValid = input.value.trim() !== "";
            }

            if (input.type === "radio" || input.type === "checkbox") {
                const checked = document.querySelector(`input[name="${input.name}"]:checked`);
                isValid = !!checked;
            }

            if (isValid) {
                input.style.border = "";

                // For radio/checkbox group
                if (input.type === "radio" || input.type === "checkbox") {
                    const group = document.querySelectorAll(`input[name="${input.name}"]`);
                    group.forEach(el => el.style.border = "");
                }
            }
        }

        $('#thankYouModal').on('hidden.bs.modal', function () {
            // Get the user ID from the hidden input
            const redirectUrl = $('#thankYouModalRedirectUrl').val();

            if (redirectUrl) {
                // User is already logged in from the controller, redirect to profile page
                // Add a small delay to ensure the modal is fully closed
                setTimeout(function() {
                    window.location.href = redirectUrl;
                }, 100);
            } else {
                // Fallback: redirect to home page if no user ID found
                console.log('No user ID found, redirecting to home page...');
                window.location.href = "{{ route('front.sub-home-page') }}";
            }
        })

        // Prefill food checkboxes + hidden inputs on page load
        function prefillFoodDataFromPrevious(previousData, wrapper) {
            if (!previousData || !previousData.food_preference) return;

            const foodPreference = previousData.food_preference;

            Object.keys(foodPreference).forEach(group => {
                const groupData = foodPreference[group];

                // Check if groupData is an array or an object
                if (Array.isArray(groupData)) {
                // If groupData is a simple array, key is the same as group (or no key)
                groupData.forEach(name => {
                    // Set UI selection for this group and item (you'll need to implement setSelection)
                    setSelection(group, null, name);

                    // Create hidden input with the correct name format
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `ans[food_preference][${group}][]`;
                    input.value = name;
                    wrapper.appendChild(input);
                });
                } else if (typeof groupData === 'object') {
                // If groupData is an object, iterate keys
                Object.keys(groupData).forEach(key => {
                    const items = groupData[key];
                    items.forEach(name => {
                    // Set UI selection for group, key, and item
                    setSelection(group, key, name);

                    // Create hidden input with the correct name format
                    const input = document.createElement('input');
                    input.type = 'hidden';

                    if (group.toLowerCase() !== key.toLowerCase()) {
                        input.name = `ans[food_preference][${group}][${key}][]`;
                    } else {
                        input.name = `ans[food_preference][${group}][]`;
                    }

                    input.value = name;
                    wrapper.appendChild(input);
                    });
                });
                }
            });
        }

        // Listen for main food checkbox changes
        document.querySelectorAll('.food-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    activeFoodCheckbox = this;
                    selectedFoodKey = this.dataset.foodKey;
                    console.log(selectedFoodKey);
                    selectedFoodGroup = this.dataset.foodGroup;
                    targetWrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${selectedFoodKey}"]`);

                    // Load sub-food items via AJAX
                    const url = "{{ route('front.flag.items', ['key' => 'FOOD_KEY_PLACEHOLDER']) }}".replace('FOOD_KEY_PLACEHOLDER', encodeURIComponent(selectedFoodKey));

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            $('#foodListContainer').empty();

                            if (Array.isArray(data) && data.length > 0) {
                                $('#foodListContainer').append(`
                                    <div class="form-check mb-3 mx-3">
                                        <input type="checkbox" class="form-check-input" id="selectAllSubFoods">
                                        <label class="form-check-label" for="selectAllSubFoods"><strong>Select All</strong></label>
                                    </div>
                                `);

                                data.forEach(item => {
                                    $('#foodListContainer').append(`
                                        <div class="col-md-12 mb-3">
                                            <div class="card h-100 p-2">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                    <div class="d-flex align-items-center flex-grow-1">
                                                        <input type="checkbox" class="form-check-input me-2 sub-food-checkbox" id="sub-${item.name}" value="${item.name}">
                                                        <label class="form-check-label mb-0" for="sub-${item.name}">${item.name}</label>
                                                    </div>
                                                    <div>
                                                        <img src="${item.image}" class="img-fluid rounded" alt="" style="width: 50px; height: auto;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `);
                                });
                            } else {
                                $('#foodListContainer').append(`<div class="col-md-12 mb-3"><p>No Food Found.</p></div>`);
                            }

                            // Prefill modal checkboxes from hidden inputs in wrapper
                            const hiddenInputs = targetWrapper.querySelectorAll('input[type="hidden"]');
                            const savedValues = Array.from(hiddenInputs).map(input => input.value);

                            savedValues.forEach(val => {
                                $(`.sub-food-checkbox[value="${val}"]`).prop('checked', true);
                            });

                            // Set modal title using the checkbox's data-food-key attribute
                            document.getElementById('foodModalLabel').textContent = `Select ${selectedFoodKey} Food Items`;
                            // Set select all checkbox state
                            const allChecked = $('.sub-food-checkbox').length === $('.sub-food-checkbox:checked').length;
                            $('#selectAllSubFoods').prop('checked', allChecked);

                            $('#foodModal').modal('show');

                            // Select all toggle
                            $(document).off('change', '#selectAllSubFoods').on('change', '#selectAllSubFoods', function () {
                                $('.sub-food-checkbox').prop('checked', this.checked);
                            });

                            // Sub-food checkbox toggle to update select all
                            $(document).off('change', '.sub-food-checkbox').on('change', '.sub-food-checkbox', function () {
                                const allChecked = $('.sub-food-checkbox').length === $('.sub-food-checkbox:checked').length;
                                $('#selectAllSubFoods').prop('checked', allChecked);
                            });
                        },
                        error: function () {
                            console.error('Failed to load sub food items.');
                        }
                    });
                } else {
                    // If main checkbox unchecked, clear hidden inputs in wrapper
                    const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${this.dataset.foodKey}"]`);
                    if (wrapper) wrapper.innerHTML = '';
                }
            });
        });

        // Confirm button in modal
        document.getElementById('confirmFoodSelection').addEventListener('click', function () {
            console.log('main');
            if (!selectedFoodKey || !selectedFoodGroup) return;

            const selectedItems = document.querySelectorAll('.sub-food-checkbox:checked');
            if (selectedItems.length === 0) return;

            const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${selectedFoodKey}"]`);
            if (!wrapper) return;

            // Clear old hidden inputs
            wrapper.innerHTML = '';

            selectedItems.forEach(item => {
                const input = document.createElement('input');
                input.type = 'hidden';

                if (selectedFoodGroup.toLowerCase() !== selectedFoodKey.toLowerCase()) {
                    input.name = `ans[food_preference][${selectedFoodGroup}][${selectedFoodKey}][]`;
                } else {
                    input.name = `ans[food_preference][${selectedFoodGroup}][]`;
                }

                input.value = item.value;
                wrapper.appendChild(input);
            });

            userConfirmed = true;
            $('#foodModal').modal('hide');
        });

        // On modal hide event
        document.getElementById('foodModal').addEventListener('hidden.bs.modal', function () {
            this.querySelectorAll('.sub-food-checkbox').forEach(cb => cb.checked = false);

            if (!userConfirmed && activeFoodCheckbox) {
                activeFoodCheckbox.checked = false;
                const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${activeFoodCheckbox.dataset.foodKey}"]`);
                if (wrapper) wrapper.innerHTML = '';
            }

            activeFoodCheckbox = null;
            userConfirmed = false;
        });

        // Prefill food checkboxes + hidden inputs on page load
        function prefillFoodDataFromPrevious(previousData) {
            console.log("Prefilling food data from previous answers...");
            console.log(previousData);
            if (!previousData || !previousData.food_preference) return;

            const foodPreference = previousData.food_preference;

           Object.keys(foodPreference).forEach(group => {
                const groupData = foodPreference[group];
                const groupLower = group.toLowerCase(); // convert once for reuse
                console.log(`Processing group: ${group}`, groupData);

                if (Array.isArray(groupData)) {
                    const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${group}"]`);
                    if (!wrapper) return;
                    wrapper.innerHTML = ''; // Clear old inputs

                    groupData.forEach(name => {
                        setSelection(group, null, name);
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `ans[food_preference][${groupLower}][]`;
                        input.value = name;
                        wrapper.appendChild(input);
                    });
                    console.log(`Prefilled ${group} with items:`, groupData);

                } else if (typeof groupData === 'object') {
                    Object.keys(groupData).forEach(key => {
                        const items = groupData[key];
                        const keyLower = key.toLowerCase(); // convert once
                        const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${key}"]`);
                        if (!wrapper) return;
                        wrapper.innerHTML = ''; // Clear old inputs

                        items.forEach(name => {
                            setSelection(group, key, name);
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = (groupLower !== keyLower)
                                ? `ans[food_preference][${groupLower}][${key}][]`
                                : `ans[food_preference][${groupLower}][]`;
                            input.value = name;
                            wrapper.appendChild(input);
                        });
                        console.log(`Prefilled ${group} - ${key} with items:`, items);
                    });
                }
            });

        }

        function setSelection(group, key, name) {
            const groupKey = key || group; // fallback if no nested group

            if(group == "Oils / Butter") {
                group = "oils_butter";
            }
            if(group == "Legumes & Beans") {
                group = "legumes_beans_and_pulses";
            }
            const checkbox = document.querySelector(
                `.food-checkbox[data-food-group="${group.toLowerCase()}"][data-food-key="${groupKey}"]`
            );

            if (checkbox) {
                checkbox.checked = true;
                checkbox.classList.add("selected"); // optional for visual effect
                const label = checkbox.nextElementSibling;
                const editIcon = label && label.nextElementSibling && label.nextElementSibling.classList.contains('edit-icon')
                    ? label.nextElementSibling
                    : null;
                if (editIcon) {
                    editIcon.classList.remove('d-none');
                }
            } else {
                console.warn(`Checkbox not found for group=${group}, key=${key}, name=${name}`);
            }
        }

        // Extract previous answers from stepData for food preference
        function getPreviousAnswers() {
            if (!Array.isArray(stepData)) return null;

            const foodPreference = {};

            stepData.forEach(item => {
                if (item.form_slug !== 'food_preference') return;

                const group = item.question?.trim();
                if (!group) return;

                let value = item.answer;

                try {
                    value = JSON.parse(value);
                } catch (e) {
                    value = [];
                }

                if (Array.isArray(value)) {
                    foodPreference[group] = value.filter(Boolean);
                } else if (value && typeof value === 'object') {
                    foodPreference[group] = {};
                    Object.keys(value).forEach(key => {
                        foodPreference[group][key] = Array.isArray(value[key])
                            ? value[key].filter(Boolean)
                            : [];
                    });
                }
            });

            return { food_preference: foodPreference };
        }

        // Call this when the current step is 5 or 6
        function prefillFoodInputsOnStep(currentStep) {
            console.log("Prefilling food inputs for step:", currentStep);
            if (currentStep === 5 || currentStep === 6) {
                const previousData = getPreviousAnswers();
                prefillFoodDataFromPrevious(previousData);
            }
        }

    });

    // HTML for Other Input (Hidden by Default)
    $(document.body).append(`
        <div class="col-md-6 col-lg-4" id="otherInputContainer" style="display: none;">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="other" placeholder="">
                <label>Other (please specify)</label>
            </div>
        </div>
    `);

    // Add edit icon to labels when checkbox is checked
    document.querySelectorAll('.food-checkbox').forEach(checkbox => {
        const label = checkbox.nextElementSibling;
        console.log('label');
        const editIcon = document.createElement('i');
        editIcon.className = 'fas fa-edit edit-icon d-none';
        editIcon.title = 'Edit selection';
        label.parentNode.insertBefore(editIcon, label.nextSibling);

        // Handle edit icon click
        editIcon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Set active checkbox and show modal
            activeFoodCheckbox = checkbox;
            selectedFoodKey = checkbox.dataset.foodKey;
            selectedFoodGroup = checkbox.dataset.foodGroup;

            // Get previously selected items
            const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${selectedFoodKey}"]`);
            const selectedValues = Array.from(wrapper.querySelectorAll('input[type="hidden"]')).map(input => input.value);

            // Load and show modal with pre-selected items
            const url = "{{ route('front.flag.items', ['key' => 'FOOD_KEY_PLACEHOLDER']) }}".replace('FOOD_KEY_PLACEHOLDER', encodeURIComponent(selectedFoodKey));

            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#foodListContainer').empty();

                    // Add Select All checkbox at the top
                    $('#foodListContainer').append(`
                        <div class="col-md-12 mb-3">
                            <div class="card h-100 p-3">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input me-2" id="selectAllFoods">
                                    <label class="form-check-label mb-0" for="selectAllFoods">
                                        Select All
                                    </label>
                                </div>
                            </div>
                        </div>
                    `);

                    // Add individual food items
                    data.forEach(item => {
                        const isChecked = selectedValues.includes(item.name);
                        $('#foodListContainer').append(`
                            <div class="col-md-12 mb-3">
                                <div class="card h-100 p-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <input type="checkbox" class="form-check-input me-2 sub-food-checkbox"
                                                id="sub-${item.name}" value="${item.name}" ${isChecked ? 'checked' : ''}>
                                            <label class="form-check-label mb-0" for="sub-${item.name}">
                                                ${item.name}
                                            </label>
                                        </div>
                                        <div>
                                            <img src="${item.image}" class="img-fluid rounded" alt="" style="width: 50px; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                    // Add Select All functionality
                    const selectAllCheckbox = document.getElementById('selectAllFoods');
                    const subCheckboxes = document.querySelectorAll('.sub-food-checkbox');

                    // Set initial state of Select All
                    selectAllCheckbox.checked = subCheckboxes.length > 0 &&
                        Array.from(subCheckboxes).every(cb => cb.checked);

                    // Handle Select All checkbox change
                    selectAllCheckbox.addEventListener('change', function() {
                        subCheckboxes.forEach(cb => {
                            cb.checked = this.checked;
                        });
                    });

                    // Update Select All state when individual checkboxes change
                    subCheckboxes.forEach(cb => {
                        cb.addEventListener('change', function() {
                            selectAllCheckbox.checked = Array.from(subCheckboxes).every(cb => cb.checked);
                        });
                    });

                    document.getElementById('foodModalLabel').textContent = `Select ${selectedFoodKey} Food Items`;

                    $('#foodModal').modal('show');
                },
                error: function() {
                    console.error('Failed to load sub food items.');
                }
            });
        });

        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                const label = this.nextElementSibling;
                const editIcon = label && label.nextElementSibling && label.nextElementSibling.classList.contains('edit-icon')
                    ? label.nextElementSibling
                    : null;
                if (editIcon) {
                    editIcon.classList.add('d-none');
                }
            }
        });
    });

    // Modify the confirm button click handler
    document.getElementById('confirmFoodSelection').addEventListener('click', function() {
        console.log('second');
        if (!selectedFoodKey || !selectedFoodGroup || !activeFoodCheckbox) return;

        const selectedItems = document.querySelectorAll('.sub-food-checkbox:checked');
        const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${selectedFoodKey}"]`);

        if (!wrapper) return;

        // Clear old hidden inputs
        wrapper.innerHTML = '';

        // If no items selected, uncheck the main checkbox
        if (selectedItems.length === 0) {
            activeFoodCheckbox.checked = false;
            // Hide the edit icon if no selection
            const label = activeFoodCheckbox.nextElementSibling;
            const editIcon = label && label.nextElementSibling && label.nextElementSibling.classList.contains('edit-icon')
                ? label.nextElementSibling
                : null;
            if (editIcon) {
                editIcon.classList.add('d-none');
            }
            userConfirmed = true;
            $('#foodModal').modal('hide');
            return;
        }

        // Add new hidden inputs for selected items
        selectedItems.forEach(item => {
            const input = document.createElement('input');
            input.type = 'hidden';

            if (selectedFoodGroup.toLowerCase() !== selectedFoodKey.toLowerCase()) {
                input.name = `ans[food_preference][${selectedFoodGroup}][${selectedFoodKey}][]`;
            } else {
                input.name = `ans[food_preference][${selectedFoodGroup}][]`;
            }

            input.value = item.value;
            wrapper.appendChild(input);
        });

        // Ensure main checkbox is checked
        activeFoodCheckbox.checked = true;
        // Show the edit icon for this checkbox
        const label = activeFoodCheckbox.nextElementSibling;
        const editIcon = label && label.nextElementSibling && label.nextElementSibling.classList.contains('edit-icon')
            ? label.nextElementSibling
            : null;
        if (editIcon) {
            editIcon.classList.remove('d-none');
        }
        userConfirmed = true;
        $('#foodModal').modal('hide');
    });

    // Add step change handler to update food selections
    document.querySelectorAll('.showStepTab').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('target');
            if (target === '5') { // When going back to step 5
                // Update all food checkboxes and their hidden inputs
                document.querySelectorAll('.food-checkbox').forEach(checkbox => {
                    const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${checkbox.dataset.foodKey}"]`);
                    if (wrapper) {
                        const hasSelections = wrapper.querySelectorAll('input[type="hidden"]').length > 0;
                        checkbox.checked = hasSelections;
                    }
                });
            }
        });
    });

    // Add back the modal hide event handler
    document.getElementById('foodModal').addEventListener('hidden.bs.modal', function() {
        // Reset sub-checkboxes
        this.querySelectorAll('.sub-food-checkbox').forEach(cb => cb.checked = false);

        // If user did not confirm and there are no selected items, uncheck the main checkbox
        if (!userConfirmed && activeFoodCheckbox) {
            const wrapper = document.querySelector(`.food-dropdown-wrapper[data-wrapper-for="${activeFoodCheckbox.dataset.foodKey}"]`);
            if (wrapper && wrapper.querySelectorAll('input[type="hidden"]').length === 0) {
                activeFoodCheckbox.checked = false;
            }
        }

        // Reset flags
        activeFoodCheckbox = null;
        userConfirmed = false;
    });

    $(document).ready(function () {
        // Check if a value is already selected on page load (in case of form pre-population)
        // if ($('#bloodTest1').prop('checked')) {
        //     $('#fileUploadSection').show();
        //     $('#bloodTestDateSection').show();
        // } else {
        //     $('#fileUploadSection').hide();
        //     $('#bloodTestDateSection').hide();
        // }

        $('input[name="ans[physical_activity_and_exercise][tracking_device]"]').on('change', function () {
            const selectedValue = $(this).val();

            if (selectedValue === 'No') {
                $('#trackingDetailsField').addClass('d-none');
            } else {
                $('#trackingDetailsField').removeClass('d-none');
            }
        });

        // Toggle file upload visibility based on radio button selection
        $('input[name="ans[medical_history][blood_test][answer]"]').on('change', function () {
            if ($('#bloodTestYes').is(':checked')) {
                $('#bloodTestDateSection').show();
                $('#fileUploadSection').show();
            } else {
                $('#bloodTestDateSection').hide();
                $('#fileUploadSection').hide();
                $('#bloodTestDate').val('');
                $('#bloodTestFile').val('');
            }
        });

        if ($('#bodyCompositionYes').prop('checked')) {
            $('#bodyCompositionFileInput').show();
            $('#bodyCompositionDateSection').show();
        } else {
            $('#bodyCompositionFileInput').hide();
            $('#bodyCompositionDateSection').hide();
            $('#bodyCompositionFile').val('');
            $('#bodyCompositionDate').val('');
        }

        // Toggle file upload visibility based on radio button selection
        $('input[name="ans[physical_measures][bodycomposition][answer]"]').on('change', function () {
            if ($('#bodyCompositionYes').prop('checked')) {
                $('#bodyCompositionFileInput').show();
                $('#bodyCompositionDateSection').show();
            } else {
                $('#bodyCompositionFile').val('');
                $('#bodyCompositionFileInput').hide();
                $('#bodyCompositionDate').val('');
                $('#bodyCompositionDateSection').hide();
            }
        });

        if ($('#drink_alcohol_yes').prop('checked')) {
            $('#drinkAlcoholInput').show();
        } else {
            $('#drinkAlcoholInput').hide();
        }

        $('input[name="ans[dietary_information][drink_alcohol]"]').on('change', function () {
            if ($('#drink_alcohol_yes').prop('checked')) {
                $('#drinkAlcoholInput').show();  // Show file upload when "Yes" is selected
            } else {
                $('#drink_alcohol_details').val('');  // Reset the file input when "No" is selected
                $('#drinkAlcoholInput').hide();  // Hide file upload when "No" is selected
            }
        });

        // Select All Fruits Checkbox
        $('#selectAllFruits').on('change', function () {
            $('.fruit-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.fruit-checkbox').on('change', function () {
            if (!$(this).prop('checked')) {
                $('#selectAllFruits').prop('checked', false);
            } else if ($('.fruit-checkbox:checked').length === $('.fruit-checkbox').length) {
                $('#selectAllFruits').prop('checked', true);
            }
        });

        // Select All Vegetables Checkbox
        $('#selectAllVegetables').on('change', function () {
            $('.vegetable-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.vegetable-checkbox').on('change', function () {
            if (!$(this).prop('checked')) {
                $('#selectAllVegetables').prop('checked', false);
            } else if ($('.vegetable-checkbox:checked').length === $('.vegetable-checkbox').length) {
                $('#selectAllVegetables').prop('checked', true);
            }
        });
    });
</script>
@endsection