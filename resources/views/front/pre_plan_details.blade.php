@extends(frontView('layouts.app'))

@section('title', 'Performance Dietitian | Strength & Conditioning Coach')

@section('content')
  
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
                                        <div class="form-floating my-3">
                                            <input type="date" class="form-control" name="dob" placeholder="">
                                            <label>Date of Birth</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="occupation" placeholder="">
                                            <label>Occupation</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="address" placeholder="">
                                            <label>Postcode</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="referredBy" id="referredBy" placeholder="">
                                            <label>Referred by</label>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6 col-lg-4">
                                        <div class="form-floating my-3">
                                            <select class="form-select select2" name="race_ethnicity_culture" id="raceEthnicityCulture">
                                                <option value="" disabled selected>Select or search</option>
                                            </select>
                                            <label for="raceEthnicityCulture">Race/ethnicity/culture</label>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                            <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="2">Next</button>
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
                                        <h5>Have you recently had a blood test? </h5>
                                        <input type="hidden" name="questions[medical_history][blood_test]" value="Have you recently had a blood test?">
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[medical_history][blood_test]" value="Yes" id="bloodTest1">
                                                <label class="form-check-label" for="bloodTest1">
                                                Yes
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[medical_history][blood_test]" value="No" id="bloodTest2">
                                                <label class="form-check-label" for="bloodTest2">
                                                No
                                                </label>
                                            </div>
                                        </div>
                                        <!-- File Upload Input, initially hidden -->
                                        <div id="fileUploadSection" style="display: none;">
                                            <label for="bloodTestFile" class="form-label">Optional: Upload blood test results</label>
                                            <input type="file" class="form-control" name="ans[medical_history][blood_test_file]" id="bloodTestFile">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <h5>Have you recently been diagnosed with any health problems or illnesses:</h5>
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
                                                    Mental disorder (e.g. ADHD, Anxiety, Depression)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="Eating disorder" name="ans[medical_history][diagnosed][]" id="diagnosed2">
                                                <label class="form-check-label" for="diagnosed2">
                                                    Eating disorder
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="Low iron/anaemia" name="ans[medical_history][diagnosed][]" id="diagnosed3" >
                                                <label class="form-check-label" for="diagnosed3">
                                                    Low iron/anaemia
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[medical_history][diagnosed][]" value="Amenorrhoea (loss of menstruation/period)" id="diagnosed4">
                                                <label class="form-check-label" for="diagnosed4">
                                                    Amenorrhoea (loss of menstruation/period)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[medical_history][diagnosed][]" value="Surgery" id="diagnosed5">
                                                <label class="form-check-label" for="diagnosed5">
                                                    Surgery
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="No" name="ans[medical_history][diagnosed][]" id="diagnosed1">
                                                <label class="form-check-label" for="diagnosed1">
                                                    No
                                                </label>
                                            </div>  
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>Please list any other medical conditions</label>
                                            <input type="hidden" name="questions[medical_history][medical_conditions]" value="Please list any other medical conditions">
                                            <input type="text" class="form-control" name="ans[medical_history][medical_conditions]" placeholder="Short-answer text">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>Provide details of any prescription medications (if taking any):</label>
                                            <input type="hidden" name="questions[medical_history][prescription_meds]" value="Provide details of any prescription medications (if taking any):">
                                            <input type="text" class="form-control" name="ans[medical_history][prescription_meds]" placeholder="Long-answer text">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>List any dietary vitamins or supplements you are <strong>currently</strong> taking (if any):</label>
                                            <input type="hidden" name="questions[medical_history][vitamins_supplements]" value="List any dietary vitamins or supplements you are currently taking (if any):">
                                            <input type="text" class="form-control" name="ans[medical_history][vitamins_supplements]" placeholder="Long-answer text">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>If female which statement best describes your current menstrual function?</label>
                                            <input type="hidden" name="questions[medical_history][menstrual_function]" value="If female which statement best describes your current menstrual function?">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="Male - Not applicable" name="ans[medical_history][menstrual_function]" id="diagnosed1">
                                                    <label class="form-check-label" for="diagnosed1">
                                                        Male - Not applicable
                                                    </label>
                                                </div>  
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="I am on contraception with controlled cycles" name="ans[medical_history][menstrual_function]" id="diagnosed1">
                                                    <label class="form-check-label" for="diagnosed1">
                                                        I am on contraception with controlled cycles
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="I am not on contraception and have regular menstrual cycles" name="ans[medical_history][menstrual_function]" id="diagnosed2">
                                                    <label class="form-check-label" for="diagnosed2">
                                                        I am not on contraception and have regular menstrual cycles 
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="I often miss a cycles" name="ans[medical_history][menstrual_function]" id="diagnosed2">
                                                    <label class="form-check-label" for="diagnosed2">
                                                        I often miss a cycles
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" value="I have not had a cycle for over 2 months" name="ans[medical_history][menstrual_function]" id="diagnosed3" >
                                                    <label class="form-check-label" for="diagnosed3">
                                                        I have not had a cycle for over 2 months
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[medical_history][menstrual_function]" value="Other" id="diagnosed5">
                                                    <label class="form-check-label" for="diagnosed5">
                                                        Other
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="1">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="3">Next</button>
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
                                            <label>Height (cm): </label>
                                            <input type="hidden" name="questions[physical_measures][height]" value="Height (cm):" />
                                            <input type="text" class="form-control" name="ans[physical_measures][height]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>Current body weight (kg) (if known): </label>
                                            <input type="hidden" name="questions[physical_measures][weight]" value="Current body weight (kg) (if known):" />
                                            <input type="text" class="form-control" name="ans[physical_measures][weight]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <h5>What has happened to your body weight over the past 2-3 months? </h5>
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
                                        <h5>Have you recently undertaken a body composition assessment (measure of muscle, body fat)?</h5>
                                        <input type="hidden" name="questions[physical_measures][bodycomposition]" value="Have you recently undertaken a body composition assessment (measure of muscle, body fat)?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][bodycomposition]" value="No" id="bodycomposition2">
                                                <label class="form-check-label" for="bodycomposition2">
                                                    No
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][bodycomposition]" value="Yes" id="bodycomposition1">
                                                <label class="form-check-label" for="bodycomposition1">
                                                   Yes
                                                </label>
                                            </div>
                                            <!-- File Upload Input, initially hidden -->
                                            <div id="bodycompositionFileInput" style="display: none;">
                                                <label for="bodycompositionFile" class="form-label">Optional: Upload body composition file</label>
                                                <input type="file" class="form-control" name="ans[physical_measures][bodycomposition][]" id="bodycompositionFile" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step" target="2">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step" target="4">Next</button>
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
                                        <h5>I am currently living with:</h5>
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
                                        <h5>Who does most of the cooking at home? </h5>
                                        <input type="hidden" name="questions[social_information][cookinghome]" value="Who does most of the cooking at home?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Me" id="cookinghome3">
                                                <label class="form-check-label" for="lcookinghome3">
                                                    Me
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Mum" id="cookinghome1">
                                                <label class="form-check-label" for="cookinghome1">
                                                    Mum
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Dad" id="cookinghome2">
                                                <label class="form-check-label" for="cookinghome2">
                                                    Dad
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
                                        <h5>How would you rate your cooking skills? </h5>
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
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step" target="3">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step" target="5">Next</button>
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
                                            <label>List your favourite foods?</label>
                                            <input type="hidden" name="questions[dietary_information][favoutire_foods]" value="List your favourite foods?" />
                                            <input type="text" class="form-control" name="ans[dietary_information][favoutire_foods]" placeholder="">                                        
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>Do you avoid/dislike any foods? List below</label>
                                            <input type="hidden" name="questions[dietary_information][dislike_foods]" value="Do you avoid/dislike any foods? List below" />
                                            <input type="text" class="form-control" name="ans[dietary_information][dislike_foods]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Do you have any allergies or intolerances?</h5><span>(more than 1 box can be checked)</span>
                                        <input type="hidden" name="questions[dietary_information][dietaryneeds]" value="Do you have any special dietary needs (e.g. Coeliac  - Gluten free)" />
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
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Nut allergy" id="dietaryneeds3">
                                                <label class="form-check-label" for="dietaryneeds3">
                                                    Nut allergy
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Shellfish allergy" id="dietaryneeds3">
                                                <label class="form-check-label" for="dietaryneeds3">
                                                    Shellfish allergy
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Soy allergy" id="dietaryneeds3">
                                                <label class="form-check-label" for="dietaryneeds3">
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
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][dietaryneeds][]" value="Other" id="dietaryneeds4">
                                                <label class="form-check-label" for="dietaryneeds4">
                                                    Other
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Do you tend to follow any particular way of eating? </h5><span>(more than 1 box can be checked)</span>
                                        <input type="hidden" name="questions[dietary_information][wayofeating]" value="Do you have any special dietary needs (e.g. Coeliac  - Gluten free)" />
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
                                        <h5>Please indicate your hunger/appetite over the day: (tick relevant meal times)</h5>
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
                                        <h5>How often do you eat takeaway food?</h5>
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
                                            <label>What are the most common takeaways you eat? Pizza, McDonald’s, Mexican, etc</label>
                                            <input type="hidden" name="questions[dietary_information][common_takeaways]" value="What are the most common takeaways you eat? Pizza, McDonald’s, Mexican, etc" />
                                            <input type="text" class="form-control" name="ans[dietary_information][common_takeaways]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <h5>Rank the following considerations from highest (1) to lowest (3) when selecting a meal or snack:</h5>
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
                                            <label>If 18+, do you drink alcohol? If yes, how many days of the week do you drink? How many each day?</label>
                                            <input type="hidden" name="questions[dietary_information][drink_alcohol]" value="If 18+, do you drink alcohol? If yes, how many days of the week do you drink? How many each day?" />
                                            <input type="text" class="form-control" name="ans[dietary_information][drink_alcohol]" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step" target="4">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step" target="6">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box" id="div6">
                        <div class="card">
                            <div class="bg-white card-header p-4 pb-3">
                                <h4 class="m-0">Food Preference List</h4>
                                <p class="mt-3">Please select all items that you enjoy eating.</p>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <h5>Carbohydrate rich foods</h5>
                                    <div class="col-md">
                                        <h5>1. Grains</h5>
                                        <input type="hidden" name="questions[food_preference][grains]" value="Grains" />

                                        <div class="form-floating my-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Cereals (granola, weet-bix, oats)" id="repair1">
                                                        <label class="form-check-label" for="repair1">Cereals (granola, weet-bix, oats)</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Pasta" id="repair2">
                                                        <label class="form-check-label" for="repair2">Pasta</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Rice" id="repair3">
                                                        <label class="form-check-label" for="repair3">Rice</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Quinoa" id="repair4">
                                                        <label class="form-check-label" for="repair4">Quinoa</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Cous cous" id="repair5">
                                                        <label class="form-check-label" for="repair5">Cous cous</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Noodles" id="repair6">
                                                        <label class="form-check-label" for="repair6">Noodles</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Bread" id="repair7">
                                                        <label class="form-check-label" for="repair7">Bread</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Wraps" id="repair8">
                                                        <label class="form-check-label" for="repair8">Wraps</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Rolls" id="repair8">
                                                        <label class="form-check-label" for="repair8">Rolls</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Pita" id="repair8">
                                                        <label class="form-check-label" for="repair8">Pita</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="English muffin" id="repair8">
                                                        <label class="form-check-label" for="repair8">English muffin</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Crumpets" id="repair8">
                                                        <label class="form-check-label" for="repair8">Crumpets</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][grains][]" value="Fruit bread" id="repair8">
                                                        <label class="form-check-label" for="repair8">Fruit bread</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <h5>2. Legume, beans & nuts</h5>
                                        <input type="hidden" name="questions[food_preference][legumes_beans_and_pulses]" value="Legumes, beans and pulses" />
                                        <div class="form-floating my-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][]" value="Kidney beans" id="protect1">
                                                        <label class="form-check-label" for="protect1">Kidney beans</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][]" value="Chickpeas" id="protect2">
                                                        <label class="form-check-label" for="protect2">Chickpeas</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][]" value="Navy beans" id="protect3">
                                                        <label class="form-check-label" for="protect3">Navy beans</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][]" value="Black beans" id="protect4">
                                                        <label class="form-check-label" for="protect4">Black beans</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][]" value="Lentils" id="protect5">
                                                        <label class="form-check-label" for="protect5">Lentils</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][]" value="Black beans" id="protect6">
                                                        <label class="form-check-label" for="protect6">Baked beans</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][]" value="Nuts (almonds, macadamia)" id="protect8">
                                                        <label class="form-check-label" for="protect8">Nuts (almonds, macadamia)</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][legumes_beans_and_pulses][]" value="Seeds (pepita, chia, flaxseed)" id="protect9">
                                                        <label class="form-check-label" for="protect9">Seeds (pepita, chia, flaxseed)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Protein rich foods </h5>
                                    <div class="col-md">
                                        <h5>1. Eggs</h5>
                                        <input type="hidden" name="questions[food_preference][eggs]" value="Eggs" />
                                        <div class="form-floating my-3">
                                            <div class="form-check ">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][eggs][]" value="Eggs" id="protein1">
                                                <label class="form-check-label" for="protein1">Eggs</label>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>2. Meat</h5>
                                        <input type="hidden" name="questions[food_preference][meat]" value="Meat" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat][]" value="Beef - Steak" id="protein2">
                                                        <label class="form-check-label" for="protein2">Beef - Steak</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat][]" value="Beef - Mince" id="protein3">
                                                        <label class="form-check-label" for="protein3">Beef - Mince</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat][]" value="Lamb" id="protein4">
                                                        <label class="form-check-label" for="protein4">Lamb</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat][]" value="Pork" id="protein5">
                                                        <label class="form-check-label" for="protein5">Pork</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat][]" value="Turkey" id="protein6">
                                                        <label class="form-check-label" for="protein6">Turkey</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat][]" value="Chicken" id="protein7">
                                                        <label class="form-check-label" for="protein7">Chicken</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>3. Meat Alternatives</h5>
                                        <input type="hidden" name="questions[food_preference][meat_alternatives]" value="Meat Alternatives" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat_alternatives][]" value="Tofu" id="protein2">
                                                        <label class="form-check-label" for="protein2">Tofu</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat_alternatives][]" value="Tempeh" id="protein3">
                                                        <label class="form-check-label" for="protein3">Tempeh</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat_alternatives][]" value="Quorn" id="protein4">
                                                        <label class="form-check-label" for="protein4">Quorn</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat_alternatives][]" value="TVP (Textured Vegetable Protein)" id="protein5">
                                                        <label class="form-check-label" for="protein5">TVP (Textured Vegetable Protein)</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][meat_alternatives][]" value="Seitan" id="protein6">
                                                        <label class="form-check-label" for="protein6">Seitan</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>4. Seafood</h5>
                                        <input type="hidden" name="questions[food_preference][seafood]" value="Seafood" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][seafood][]" value="White Fish" id="seafood1">
                                                        <label class="form-check-label" for="seafood1">White fish (barramundi, snapper)</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][seafood][]" value="Salmon" id="seafood2">
                                                        <label class="form-check-label" for="seafood2">Salmon</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][seafood][]" value="Smoked Salmon" id="seafood3">
                                                        <label class="form-check-label" for="seafood3">Smoked Salmon</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][seafood][]" value="Tuna" id="seafood4">
                                                        <label class="form-check-label" for="seafood4">Tuna</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][seafood][]" value="Tinned Fish" id="seafood5">
                                                        <label class="form-check-label" for="seafood5">Tinned Fish</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>5. Dairy</h5>
                                        <input type="hidden" name="questions[food_preference][dairy]" value="Dairy" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Full Cream Milk" id="dairy1">
                                                        <label class="form-check-label" for="dairy1">Full Cream Milk</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="High Protein Yoghurt" id="dairy5">
                                                        <label class="form-check-label" for="dairy5">High Protein Yoghurt (Yopro)</label>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Parmesan cheese" id="dairy5">
                                                        <label class="form-check-label" for="dairy5">Parmesan cheese</label>
                                                    </div>
                                                </div>
                                               
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Low Fat Milk" id="dairy2">
                                                        <label class="form-check-label" for="dairy2">Low Fat Milk</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Tasty cheese" id="dairy5">
                                                        <label class="form-check-label" for="dairy5">Tasty cheese</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Goats cheese" id="dairy5">
                                                        <label class="form-check-label" for="dairy5">Goats cheese</label>
                                                    </div>
                                                </div>
                                                

                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Skim Milk" id="dairy3">
                                                        <label class="form-check-label" for="dairy3">Skim Milk</label>
                                                    </div>
                                                </div>
                                               
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Colby cheese" id="dairy5">
                                                        <label class="form-check-label" for="dairy5">Colby cheese</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Soft Cheese (Brie)" id="dairy5">
                                                        <label class="form-check-label" for="dairy5">Soft Cheese (Brie)</label>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Greek Yoghurt" id="dairy4">
                                                        <label class="form-check-label" for="dairy4">Greek Yoghurt</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][dairy][]" value="Vintage cheese" id="dairy5">
                                                        <label class="form-check-label" for="dairy5">Vintage cheese</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>6. Non-Dairy</h5>
                                        <input type="hidden" name="questions[food_preference][non-dairy]" value="Non-Dairy" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][non-dairy][]" value="Soy Milk" id="nondairy1">
                                                        <label class="form-check-label" for="nondairy1">Soy Milk</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][non-dairy][]" value="Almond Milk" id="nondairy2">
                                                        <label class="form-check-label" for="nondairy2">Almond Milk</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][non-dairy][]" value="Oat Milk" id="nondairy3">
                                                        <label class="form-check-label" for="nondairy3">Oat Milk</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>7. Fruit</h5>
                                        <input type="hidden" name="questions[food_preference][fruit]" value="Fruit" />
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllFruits">
                                            <label class="form-check-label fw-bold" for="selectAllFruits">Select All</label>
                                        </div>
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Avocado" id="protein2">
                                                        <label class="form-check-label" for="protein2">Avocado</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Banana" id="protein3">
                                                        <label class="form-check-label" for="protein3">Banana</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Pineapple" id="protein4">
                                                        <label class="form-check-label" for="protein4">Pineapple</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Kiwi" id="protein4">
                                                        <label class="form-check-label" for="protein4">Kiwi</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Passionfruit" id="protein4">
                                                        <label class="form-check-label" for="protein4">Passionfruit</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Orange" id="protein4">
                                                        <label class="form-check-label" for="protein4">Orange</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Mandarin" id="protein4">
                                                        <label class="form-check-label" for="protein4">Mandarin</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Mango" id="protein4">
                                                        <label class="form-check-label" for="protein4">Mango</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Peach" id="protein4">
                                                        <label class="form-check-label" for="protein4">Peach</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Rockmelon" id="protein4">
                                                        <label class="form-check-label" for="protein4">Rockmelon</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Apple" id="protein4">
                                                        <label class="form-check-label" for="protein4">Apple</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Watermelon" id="protein4">
                                                        <label class="form-check-label" for="protein4">Watermelon</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Strawberries" id="protein4">
                                                        <label class="form-check-label" for="protein4">Strawberries</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Raspberries" id="protein4">
                                                        <label class="form-check-label" for="protein4">Raspberries</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Pomegranate" id="protein4">
                                                        <label class="form-check-label" for="protein4">Pomegranate</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Apple (green)" id="protein4">
                                                        <label class="form-check-label" for="protein4">Apple (green)</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Pear" id="protein4">
                                                        <label class="form-check-label" for="protein4">Pear</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Grapes" id="protein4">
                                                        <label class="form-check-label" for="protein4">Grapes</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Tomato" id="protein4">
                                                        <label class="form-check-label" for="protein4">Tomato</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Blueberries" id="protein4">
                                                        <label class="form-check-label" for="protein4">Blueberries</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Black grapes" id="protein4">
                                                        <label class="form-check-label" for="protein4">Black grapes</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input fruit-checkbox" type="checkbox" name="ans[food_preference][fruit][]" value="Dates" id="protein4">
                                                        <label class="form-check-label" for="protein4">Dates</label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5>8. Vegetables</h5>
                                        <input type="hidden" name="questions[food_preference][vegetables]" value="Vegetables" />
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllVegetables">
                                            <label class="form-check-label fw-bold" for="selectAllVegetables">Select All</label>
                                        </div>
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Carrot" id="protein2">
                                                        <label class="form-check-label" for="protein2">Carrot</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Pumpkin" id="protein3">
                                                        <label class="form-check-label" for="protein3">Pumpkin</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Potato" id="protein4">
                                                        <label class="form-check-label" for="protein4">Potato</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Sweet potato" id="protein4">
                                                        <label class="form-check-label" for="protein4">Sweet potato</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Capsicum (red)" id="protein4">
                                                        <label class="form-check-label" for="protein4">Capsicum (red)</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Capsicum (green)" id="protein4">
                                                        <label class="form-check-label" for="protein4">Capsicum (green)</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Zucchini" id="protein4">
                                                        <label class="form-check-label" for="protein4">Zucchini</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Corn" id="protein4">
                                                        <label class="form-check-label" for="protein4">Corn</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Celery" id="protein4">
                                                        <label class="form-check-label" for="protein4">Celery</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Cucumber" id="protein4">
                                                        <label class="form-check-label" for="protein4">Cucumber</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Broccoli" id="protein4">
                                                        <label class="form-check-label" for="protein4">Broccoli</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Beans" id="protein4">
                                                        <label class="form-check-label" for="protein4">Beans</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Snow peas" id="protein4">
                                                        <label class="form-check-label" for="protein4">Snow peas</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Bok choy" id="protein4">
                                                        <label class="form-check-label" for="protein4">Bok choy</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Peas" id="protein4">
                                                        <label class="form-check-label" for="protein4">Peas</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Beetroot" id="protein4">
                                                        <label class="form-check-label" for="protein4">Beetroot</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input vegetable-checkbox" type="checkbox" name="ans[food_preference][vegetables][]" value="Eggplant" id="protein4">
                                                        <label class="form-check-label" for="protein4">Eggplant</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>9. Oils / Butter</h5>
                                        <input type="hidden" name="questions[food_preference][oils_butter]" value="Oils / Butter" />
                                        <div class="form-floating my-3">
                                            <div class="row row-cols-1 row-cols-md-3 g-2">
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][oils_butter][]" value="Butter" id="protein2">
                                                        <label class="form-check-label" for="protein2">Butter</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][oils_butter][]" value="Margarine" id="protein3">
                                                        <label class="form-check-label" for="protein3">Margarine</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][oils_butter][]" value="Vegetable oil" id="protein4">
                                                        <label class="form-check-label" for="protein4">Vegetable oil</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][oils_butter][]" value="Extra Virgin Olive oil" id="protein4">
                                                        <label class="form-check-label" for="protein4">Extra Virgin Olive oil</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][oils_butter][]" value="Olive oil" id="protein4">
                                                        <label class="form-check-label" for="protein4">Olive oil</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][oils_butter][]" value="Avocado oil" id="protein4">
                                                        <label class="form-check-label" for="protein4">Avocado oil</label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check ">
                                                        <input class="form-check-input" type="checkbox" name="ans[food_preference][oils_butter][]" value="Canola oil" id="protein4">
                                                        <label class="form-check-label" for="protein4">Canola oil</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="5">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="7">Next</button>
                            </div>
                        </div>
                    </div>
                    <div class="step-tab-box" id="div7">
                        <div class="card">
                            <div class="bg-white card-header p-4 pb-3">
                                <h4 class="m-0">Food Preference List</h4>
                                <p class="mt-3">Please select all items that you enjoy eating.</p>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <!-- Snacks Section -->
                                    <div class="col-12 mb-4">
                                        <h5>Snacks</h5>
                                        <input type="hidden" name="questions[food_preference][snacks]" value="Snacks" />
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Cracker biscuits (e.g. Jatz)" id="snack_cracker_biscuits_checkbox">
                                                <label for="snack_cracker_biscuits_checkbox" class="form-label">Cracker biscuits (e.g. Jatz)</label>
                                                <input type="text" id="snack_cracker_biscuits" name="ans[food_preference][snacks][cracker_biscuits]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Rice cakes" id="snack_rice_cakes_checkbox">
                                                <label for="snack_rice_cakes_checkbox" class="form-label">Rice cakes</label>
                                                <input type="text" id="snack_rice_cakes" name="ans[food_preference][snacks][rice_cakes]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Fruit/nut bars" id="snack_fruit_nut_bars_checkbox">
                                                <label for="snack_fruit_nut_bars_checkbox" class="form-label">Fruit/nut bars</label>
                                                <input type="text" id="snack_fruit_nut_bars" name="ans[food_preference][snacks][fruit_nut_bars]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Muesli bars" id="snack_muesli_bars_checkbox">
                                                <label for="snack_muesli_bars_checkbox" class="form-label">Muesli bars</label>
                                                <input type="text" id="snack_muesli_bars" name="ans[food_preference][snacks][muesli_bars]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Popcorn" id="snack_popcorn_checkbox">
                                                <label for="snack_popcorn_checkbox" class="form-label">Popcorn</label>
                                                <input type="text" id="snack_popcorn" name="ans[food_preference][snacks][popcorn]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Lollies" id="snack_lollies_checkbox">
                                                <label for="snack_lollies_checkbox" class="form-label">Lollies</label>
                                                <input type="text" id="snack_lollies" name="ans[food_preference][snacks][lollies]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Chocolate bars" id="snack_chocolate_bars_checkbox">
                                                <label for="snack_chocolate_bars_checkbox" class="form-label">Chocolate bars</label>
                                                <input type="text" id="snack_chocolate_bars" name="ans[food_preference][snacks][chocolate_bars]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Chocolate bites" id="snack_chocolate_bites_checkbox">
                                                <label for="snack_chocolate_bites_checkbox" class="form-label">Chocolate bites</label>
                                                <input type="text" id="snack_chocolate_bites" name="ans[food_preference][snacks][chocolate_bites]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Chips (Corn, Crisps, Pretzels)" id="snack_chips_checkbox">
                                                <label for="snack_chips_checkbox" class="form-label">Chips (Corn, Crisps, Pretzels)</label>
                                                <input type="text" id="snack_chips" name="ans[food_preference][snacks][chips]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][snacks][]" value="Muffins" id="snack_muffins_checkbox">
                                                <label for="snack_muffins_checkbox" class="form-label">Muffins</label>
                                                <input type="text" id="snack_muffins" name="ans[food_preference][snacks][muffins]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Drinks Section -->
                                    <div class="col-12 mb-4">
                                        <h5>Drinks</h5>
                                        <h6>Cold Drinks</h6>
                                        <input type="hidden" name="questions[food_preference][cold_drink]" value="Cold Drinks" />

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cold_drink][iced_coffee][]" value="Iced Coffee" id="drink_iced_coffee_checkbox">
                                                <label for="drink_iced_coffee" class="form-label">Iced Coffee</label>
                                                <input type="text" id="drink_iced_coffee" name="ans[food_preference][cold_drink][iced_coffee]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cold_drink][iced_tea][]" value="Iced Tea" id="drink_iced_tea_checkbox">
                                                <label for="drink_iced_tea" class="form-label">Iced Tea</label>
                                                <input type="text" id="drink_iced_tea" name="ans[food_preference][cold_drink][iced_tea]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cold_drink][flavoured_milk][]" value="Flavoured Milk" id="drink_flavoured_milk_checkbox">
                                                <label for="drink_flavoured_milk" class="form-label">Flavoured Milk</label>
                                                <input type="text" id="drink_flavoured_milk" name="ans[food_preference][cold_drink][flavoured_milk]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cold_drink][soft_drink][]" value="Soft Drink" id="drink_soft_drink_checkbox">
                                                <label for="drink_soft_drink" class="form-label">Soft drink</label>
                                                <input type="text" id="drink_soft_drink" name="ans[food_preference][cold_drink][soft_drink]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cold_drink][sugar_free_soft_drink][]" value="Sugar-free soft drink (e.g. Coke Zero)" id="drink_sugar_free_soft_drink_checkbox">
                                                <label for="drink_sugar_free_soft_drink" class="form-label">Sugar-free soft drink (e.g. Coke Zero)</label>
                                                <input type="text" id="drink_sugar_free_soft_drink" name="ans[food_preference][cold_drink][sugar_free_soft_drink]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cold_drink][energy_drink][]" value="Energy Drink" id="drink_energy_drink_checkbox">
                                                <label for="drink_energy_drink" class="form-label">Energy Drink</label>
                                                <input type="text" id="drink_energy_drink" name="ans[food_preference][cold_drink][energy_drink]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cold_drink][juice][]" value="Juice" id="drink_juice_checkbox">
                                                <label for="drink_juice" class="form-label">Juice</label>
                                                <input type="text" id="drink_juice" name="ans[food_preference][cold_drink][juice]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cold_drink][kombucha][]" value="Kombucha" id="drink_kombucha_checkbox">
                                                <label for="drink_kombucha" class="form-label">Kombucha</label>
                                                <input type="text" id="drink_kombucha" name="ans[food_preference][cold_drink][kombucha]" class="form-control" placeholder="">
                                            </div>
                                        </div>

                                        <h6>Hot Drinks</h6>
                                        <input type="hidden" name="questions[food_preference][hot_drink]" value="Hot Drinks" />

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][hot_drink][coffee][]" value="Coffee" id="drink_hot_coffee_checkbox">
                                                <label for="drink_hot_coffee" class="form-label">Coffee</label>
                                                <input type="text" id="drink_hot_coffee" name="ans[food_preference][hot_drink][coffee]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][hot_drink][chocolate][]" value="Hot Chocolate" id="drink_hot_chocolate_checkbox">
                                                <label for="drink_hot_chocolate" class="form-label">Hot Chocolate</label>
                                                <input type="text" id="drink_hot_chocolate" name="ans[food_preference][hot_drink][chocolate]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][hot_drink][tea][]" value="Tea" id="drink_hot_tea_checkbox">
                                                <label for="drink_hot_tea" class="form-label">Tea</label>
                                                <input type="text" id="drink_hot_tea" name="ans[food_preference][hot_drink][tea]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cuisines Section -->
                                    <div class="col-12 mb-4">
                                        <h5>Cuisines</h5>
                                        <p>Select preferred cuisines</p>
                                        <input type="hidden" name="questions[food_preference][cuisines]" value="Cuisines" />

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cuisines][japanese][]" value="Japanese" id="cuisine_japanese_checkbox">
                                                <label for="cuisine_japanese" class="form-label">Japanese</label>
                                                <input type="text" id="cuisine_japanese" name="ans[food_preference][cuisines][japanese]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cuisines][chinese][]" value="Chinese" id="cuisine_chinese_checkbox">
                                                <label for="cuisine_chinese" class="form-label">Chinese</label>
                                                <input type="text" id="cuisine_chinese" name="ans[food_preference][cuisines][chinese]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cuisines][thai][]" value="Thai" id="cuisine_thai_checkbox">
                                                <label for="cuisine_thai" class="form-label">Thai</label>
                                                <input type="text" id="cuisine_thai" name="ans[food_preference][cuisines][thai]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cuisines][indian][]" value="Indian" id="cuisine_indian_checkbox">
                                                <label for="cuisine_indian" class="form-label">Indian</label>
                                                <input type="text" id="cuisine_indian" name="ans[food_preference][cuisines][indian]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cuisines][italian][]" value="Italian" id="cuisine_italian_checkbox">
                                                <label for="cuisine_italian" class="form-label">Italian</label>
                                                <input type="text" id="cuisine_italian" name="ans[food_preference][cuisines][italian]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cuisines][mexican][]" value="Mexican" id="cuisine_mexican_checkbox">
                                                <label for="cuisine_mexican" class="form-label">Mexican</label>
                                                <input type="text" id="cuisine_mexican" name="ans[food_preference][cuisines][mexican]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input class="form-check-input" type="checkbox" name="ans[food_preference][cuisines][greek][]" value="Greek" id="cuisine_greek_checkbox">
                                                <label for="cuisine_greek" class="form-label">Greek</label>
                                                <input type="text" id="cuisine_greek" name="ans[food_preference][cuisines][greek]" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-md-6">
                                                <!-- <input class="form-check-input" type="checkbox" name="ans[food_preference][cuisines][other]" value="Other" id="cuisine_other_checkbox"> -->
                                                <label for="cuisine_other" class="form-label">Other</label>
                                                <input type="text" id="cuisine_other" name="ans[food_preference][cuisines][other]" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="6">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="8">Next</button>
                            </div>
                        </div>
                    </div>
                {{--<div class="step-tab-box" id="div5">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Fuel, Repeat, Protect, Hydrate</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="graph-img">
                                    <figure>
                                        <img src="{!! frontAssets('images/graph-img-02.png') !!}" alt="">
                                    </figure>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>Fuel</h5>
                                        <input type="hidden" name="questions[fuel_repeat_protect_hydrate][fuel]" value="Fuel" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][fuel][]" value="Rice" id="fuel1">
                                                <label class="form-check-label" for="fuel1">Rice</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][fuel][]" value="Spaghetti" id="fuel2">
                                                <label class="form-check-label" for="fuel2">Spaghetti</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][fuel][]" value="Pasta" id="fuel3">
                                                <label class="form-check-label" for="fuel3">Pasta</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][fuel][]" value="Bread" id="fuel4">
                                                <label class="form-check-label" for="fuel4">Bread</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <h5>Repair</h5>
                                        <input type="hidden" name="questions[fuel_repeat_protect_hydrate][repair]" value="Repair" />

                                        <div class="form-floating my-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][repair][]" value="Beef" id="repair1">
                                                        <label class="form-check-label" for="repair1">Beef</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][repair][]" value="Turkey" id="repair2">
                                                        <label class="form-check-label" for="repair2">Turkey</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][repair][]" value="Pork" id="repair3">
                                                        <label class="form-check-label" for="repair3">Pork</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][repair][]" value="chicken" id="repair4">
                                                        <label class="form-check-label" for="repair4">chicken</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][repair][]" value="Tuna" id="repair5">
                                                        <label class="form-check-label" for="repair5">Tuna</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][repair][]" value="salmon" id="repair6">
                                                        <label class="form-check-label" for="repair6">salmon</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][repair][]" value="Smoked salmon" id="repair7">
                                                        <label class="form-check-label" for="repair7">Smoked salmon</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][repair][]" value="Eggs" id="repair8">
                                                        <label class="form-check-label" for="repair8">Eggs</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <h5>Protect</h5>
                                        <input type="hidden" name="questions[fuel_repeat_protect_hydrate][protect]" value="Protect" />
                                        <div class="form-floating my-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Plant plants" id="protect1">
                                                        <label class="form-check-label" for="protect1">Plant plants</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Sauces" id="protect2">
                                                        <label class="form-check-label" for="protect2">Sauces</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Tempe" id="protect3">
                                                        <label class="form-check-label" for="protect3">Tempe</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Tofu" id="protect4">
                                                        <label class="form-check-label" for="protect4">Tofu</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Kidney beans" id="protect5">
                                                        <label class="form-check-label" for="protect5">Kidney beans</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Black beans" id="protect6">
                                                        <label class="form-check-label" for="protect6">Black beans</label>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Vegetable protein" id="protect7">
                                                        <label class="form-check-label" for="protect7">Vegetable protein</label>
                                                    </div>
                                                </div> -->
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Nuts" id="protect8">
                                                        <label class="form-check-label" for="protect8">Nuts</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[fuel_repeat_protect_hydrate][protect][]" value="Brazil nuts" id="protect9">
                                                        <label class="form-check-label" for="protect9">Brazil nuts</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab" target="4">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab" target="6">Next</button>
                            </div>
                        </div>
                    </div>
                --}}
                    <div class="step-tab-box " id="div8">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Nutrition Goals</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-6">
                                    <h5>Which of the following nutrition related goals are you <strong class="text-primary">CURRENTLY</strong> interested in working on?</h5>
                                        <input type="hidden" name="questions[nutrition_goals][related_goals]" value="Which of the following nutrition related goals are you interested in working on?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Reduce bodyweight (i.e. weight loss)" id="relatedgoals1">
                                                <label class="form-check-label" for="relatedgoals1">Improving Sports Performance/Recovery</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Improve health/immunity" id="relatedgoals5">
                                                <label class="form-check-label" for="relatedgoals5">Improve health/immunity</label>
                                            </div>
                                            
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Competition nutrition strategies" id="relatedgoals9">
                                                <label class="form-check-label" for="relatedgoals9">Competition nutrition strategies</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Reduce bodyweight (i.e. weight loss)" id="relatedgoals1">
                                                <label class="form-check-label" for="relatedgoals1">Reduce bodyweight (i.e. weight loss)</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Increase bodyweight (i.e. gain mass)" id="relatedgoals2">
                                                <label class="form-check-label" for="trelatedgoals2">Increase bodyweight (i.e. gain mass)</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Body composition (maintain weight while getting leaner)" id="relatedgoals3">
                                                <label class="form-check-label" for="relatedgoals3">Body composition (maintain weight while getting leaner)</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Decreasing fatigue" id="relatedgoals4">
                                                <label class="form-check-label" for="relatedgoals4">Decreasing fatigue</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Nutrition for injury" id="relatedgoals7">
                                                <label class="form-check-label" for="relatedgoals7">Nutrition for injury</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Gastrointestinal (gut) issues" id="relatedgoals8">
                                                <label class="form-check-label" for="relatedgoals8">Gastrointestinal (gut) issues</label>
                                            </div>
                                            
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Other" id="relatedgoals10">
                                                <label class="form-check-label" for="relatedgoals10">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>What areas would you like assistance with?</h5>
                                        <input type="hidden" name="questions[nutrition_goals][like_assistance_with]" value="What areas would you like assistance with?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Teach me about healthier eating" id="likeassistancewith1">
                                                <label class="form-check-label" for="likeassistancewith1">Teach me about healthier eating
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Help confirming I am on the right track" id="likeassistancewith2">
                                                <label class="form-check-label" for="likeassistancewith2">Help confirming I am on the right track</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Hold me accountable to my goals and provide support" id="likeassistancewith3">
                                                <label class="form-check-label" for="likeassistancewith3">Hold me accountable to my goals and provide support</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Navigating through social media mixed messages" id="likeassistancewith4">
                                                <label class="form-check-label" for="likeassistancewith4">Navigating through social media mixed messages</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][like_assistance_with][]" value="Other" id="likeassistancewith5">
                                                <label class="form-check-label" for="likeassistancewith5">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>What is your biggest nutrition challenge? </h5>
                                        <input type="hidden" name="questions[nutrition_goals][biggest_nutrition_challenge]" value="What is your biggest nutrition challenge?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Cravings" id="biggestnutritionchallenge1">
                                                <label class="form-check-label" for="biggestnutritionchallenge1">Cravings</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Don't know what I should eat" id="biggestnutritionchallenge2">
                                                <label class="form-check-label" for="biggestnutritionchallenge2">Don't know what I should eat</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Lack of time to prepare meals" id="biggestnutritionchallenge3">
                                                <label class="form-check-label" for="biggestnutritionchallenge3">Lack of time to prepare meals</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Eating out too often" id="biggestnutritionchallenge4">
                                                <label class="form-check-label" for="biggestnutritionchallenge4">Eating out too often</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Emotional eating / stress eating" id="biggestnutritionchallenge5">
                                                <label class="form-check-label" for="biggestnutritionchallenge5">Emotional eating / stress eating</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Family / peer pressure" id="biggestnutritionchallenge6">
                                                <label class="form-check-label" for="biggestnutritionchallenge6">Family / peer pressure</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Large portions" id="biggestnutritionchallenge7">
                                                <label class="form-check-label" for="biggestnutritionchallenge7">Large portions</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Lack of planning" id="biggestnutritionchallenge8">
                                                <label class="form-check-label" for="biggestnutritionchallenge8">Lack of planning</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Snacking when not hungry" id="biggestnutritionchallenge9">
                                                <label class="form-check-label" for="biggestnutritionchallenge9">Snacking when not hungry</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Sweet tooth" id="biggestnutritionchallenge10">
                                                <label class="form-check-label" for="biggestnutritionchallenge10">Sweet tooth</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][biggest_nutrition_challenge][]" value="Heavily impacted by social media 'Influences" id="biggestnutritionchallenge11">
                                                <label class="form-check-label" for="biggestnutritionchallenge11">Heavily impacted by social media 'Influences</label>
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
                                        <h5>Where do you currently get nutrition information from?</h5>
                                        <input type="hidden" name="questions[nutrition_goals][getnutrition]" value="Where do you currently get nutrition information from?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Parents" id="getnutrition1">
                                                <label class="form-check-label" for="getnutrition1">
                                                    Parents
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Siblings (brother, sister)" id="getnutrition2">
                                                <label class="form-check-label" for="getnutrition2">
                                                    Siblings (brother, sister)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Friends" id="getnutrition2">
                                                <label class="form-check-label" for="getnutrition2">
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
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Facebook" id="getnutrition3">
                                                <label class="form-check-label" for="getnutrition3">
                                                    Facebook
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Google" id="getnutrition3">
                                                <label class="form-check-label" for="getnutrition3">
                                                    Google
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="Checkbox" name="ans[nutrition_goals][getnutrition][]" value="Other" id="getnutrition4">
                                                <label class="form-check-label" for="getnutrition4">
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
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step" target="7">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step" target="9">Next</button>
                            </div>
                        </div>
                    </div>
                  
                    <div class="step-tab-box" id="div9">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Physical Activity and Exercise</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <h5>How many days per week and at what intensity do you normally train for your sport?</h5>
                                        <input type="hidden" name="questions[physical_activity_and_exercise][intensity]" value="How many days per week and at what intensity do you normally train for your sport?" />
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">Low intensity</th>
                                                        <th class="text-center">Moderate intensity</th>
                                                        <th class="text-center">High intensity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1-2</td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][1-2][]" value="Low intensity" id="perweekintensity-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][1-2][]" value="Moderate intensity" id="perweekintensity-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][1-2][]" value="High intensity" id="perweekintensity-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>3-4</td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][3-4][]" value="Low intensity" id="perweekintensity-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][3-4][]" value="Moderate intensity" id="perweekintensity-5"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][3-4][]" value="High intensity" id="perweekintensity-6"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>5+</td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][5+][]" value="Low intensity" id="perweekintensity-7"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][5+][]" value="Moderate intensity" id="perweekintensity-8"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[physical_activity_and_exercise][intensity][5+][]" value="High intensity" id="perweekintensity-9"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <h5>What type of physical activity do you mainly do or compete in? (more than one can apply)</h5>
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
                                        <h5>Do you <strong class="text-primary">CURRENTLY</strong> use any EXERCISE OR NUTRITION tracking devices/apps?  </h5>
                                        <input type="hidden" name="questions[physical_activity_and_exercise][tracking_device]" value="Do you CURRENTLY use any EXERCISE OR NUTRITION tracking devices/apps? " />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-1" value="Garmin or similar watch">
                                                <label class="form-check-label" for="trackingDevices-1">Garmin or similar watch</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-2" value="Oura ring">
                                                <label class="form-check-label" for="trackingDevices-2">Oura ring</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-3" value="Whoop band">
                                                <label class="form-check-label" for="trackingDevices-3">Whoop band</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-4" value="My Fitness Pal or similar">
                                                <label class="form-check-label" for="trackingDevices-4">My Fitness Pal or similar</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_activity_and_exercise][tracking_device]" id="trackingDevices-5" value="Other">
                                                <label class="form-check-label" for="trackingDevices-5">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>If answered yes to the above question, what do you mainly track? (e.g. exercise, food ,sleep)</label>
                                            <input type="hidden" name="questions[physical_activity_and_exercise][track]" value="If answered yes to the above question, what do you mainly track? (e.g. exercise, food, sleep)" />
                                            <input type="text" class="form-control" name="ans[physical_activity_and_exercise][track]" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                            <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step" target="8">Back</button>
                                <button type="button" class="btn btn-primary ms-auto next-step" id="submit-nutrition-form">Submit</button>
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
                    <p class="mb-2">Your form is submitted.</p>
                    <p class="mb-4">Kerry will now create your plan and let you know as soon as it’s ready (48-72hrs).</p>
                    <button type="button" class="btn btn-primary w-50" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    // localStorage.clear();

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

    document.addEventListener('DOMContentLoaded', function () {
        function saveFormData() {
            const formData = {};

            document.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.type === 'checkbox') {
                    if (!formData[input.name]) {
                        formData[input.name] = [];
                    }

                    if (input.checked) {
                        if (input.value === 'Other') {
                            formData[input.name].push('Other');

                            const otherInput = document.getElementById(`${input.id}-input`);
                            if (otherInput) {
                                formData[`${input.name}_other`] = otherInput.value;
                            }
                        } else {
                            formData[input.name].push(input.value);
                        }
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        if (input.value === 'Other') {
                            formData[input.name] = 'Other';

                            const otherInput = document.getElementById(`${input.id}-input`);
                            if (otherInput) {
                                formData[`${input.name}_other`] = otherInput.value;
                            }
                        } else {
                            formData[input.name] = input.value;
                        }
                    }
                } else {
                    formData[input.name] = input.value;
                }
            });

            console.log('🟢 Saved Form Data:', formData);
            localStorage.setItem('nutritionFormData', JSON.stringify(formData));
        }

        function restoreFormData() {
            const savedData = JSON.parse(localStorage.getItem('nutritionFormData'));
            console.log('🔵 Restoring Form Data:', savedData);

            if (savedData) {
                document.querySelectorAll('input, select, textarea').forEach(input => {
                    if (input.type === 'checkbox') {
                        if (savedData[input.name]?.includes(input.value)) {
                            input.checked = true;
                        }

                        if (input.value === 'Other' && savedData[input.name]?.includes('Other')) {
                            input.checked = true;
                            input.dispatchEvent(new Event('change'));

                            const otherInputValue = savedData[`${input.name}_other`];
                            if (otherInputValue) {
                                createOtherInput(input, otherInputValue);
                            }
                        }
                    } else if (input.type === 'radio') {
                        if (savedData[input.name] === 'Other' && input.value === 'Other') {
                            input.checked = true;
                            input.dispatchEvent(new Event('change'));

                            const otherInputValue = savedData[`${input.name}_other`];
                            if (otherInputValue) {
                                createOtherInput(input, otherInputValue);

                                // Handle TrackingDetails visibility
                                handleTrackingDetailsVisibility(otherInputValue);
                            }
                        } else if (input.value === savedData[input.name]) {
                            input.checked = true;
                        }
                    } else {
                        input.value = savedData[input.name] || '';
                    }
                });
            }
        }

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

                otherInput.addEventListener('keyup', function () {
                    const inputValue = this.value.trim().toLowerCase();
                    handleTrackingDetailsVisibility(inputValue);
                    saveFormData();
                });
            }

            otherInput.value = value;
        }

        function handleTrackingDetailsVisibility(inputValue) {
            const trackingDetailsField = document.querySelector('[name="ans[physical_activity_and_exercise][track]"]').closest('.col-md-6');
            const trackingDetailsInput = trackingDetailsField.querySelector('input');

            if (inputValue === 'no') {
                trackingDetailsField.style.display = 'none';
                trackingDetailsInput.disabled = true;
                trackingDetailsInput.removeAttribute('required');
            } else {
                trackingDetailsField.style.display = 'block';
                trackingDetailsInput.disabled = false;
                trackingDetailsInput.setAttribute('required', 'required');
            }
        }

        // Restore data on page load
        restoreFormData();

        document.querySelector('form').addEventListener('submit', function () {
            console.log('✅ Form submitted — Clearing LocalStorage');
            localStorage.removeItem('nutritionFormData');
        });

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

                saveFormData();
            });
        });

        // Handle checkbox changes
        document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.addEventListener('change', saveFormData);
        });

        // Handle radio button changes
        document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                let radioName = this.name;

                const trackingDetailsField = document.querySelector('[name="ans[physical_activity_and_exercise][track]"]').closest('.col-md-6');
                const trackingDetailsInput = trackingDetailsField.querySelector('input');

                trackingDetailsField.style.display = 'block';
                trackingDetailsInput.disabled = false;
                trackingDetailsInput.setAttribute('required', 'required');

                document.querySelectorAll(`input[type="radio"][name="${radioName}"]`).forEach(function (otherRadio) {
                    if (otherRadio !== radio) {
                        let otherInputId = `${otherRadio.id}-input`;
                        let otherInput = document.getElementById(otherInputId);

                        if (otherInput) {
                            otherInput.remove();
                        }
                    }
                });

                saveFormData();
            });
        });
    });



    document.addEventListener("DOMContentLoaded", () => {
        const stepCircles = document.querySelectorAll('.tab-steps');
        const stepTabs = document.querySelectorAll(".step-tab-box");
        const showStepButtons = document.querySelectorAll('.showStepTab');
        const submitButton = document.getElementById('submit-nutrition-form');
        const form = document.getElementById('nutrition-screen-form');

        let currentStep = 0; // Track the active step index

        // Initially, show only the first step-tab-box
        stepTabs.forEach((tab, index) => {
            tab.style.display = index === 0 ? "block" : "none";
        });

        // Function to validate all fields in the current step
        function validateStep(stepIndex) {
            // Skip validation for stepIndex 5 and 6
            if (stepIndex === 5 || stepIndex === 6) {
                return true; // Skip validation
            }
            const stepTab = stepTabs[stepIndex]; // Get current step tab
            const inputs = stepTab.querySelectorAll('input, textarea, select');
            let isValid = true;
            const errorMessage = "Note: All questions are required. Please fill them out or select answers.";

            // Skip validation for the intensity question in this step (identified by the hidden input name)
            const skipValidationQuestion = stepTab.querySelector('input[name="questions[physical_activity_and_exercise][intensity]"]');

            // If this question exists, we skip validation for the related checkboxes
            if (skipValidationQuestion) {
                // Skip the validation logic for this specific set of checkboxes
                const checkboxes = stepTab.querySelectorAll('input[name^="ans[physical_activity_and_exercise][intensity]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.disabled = false; // Ensure all checkboxes are enabled for skipping validation
                });
            }

            // Loop through each input to validate
            inputs.forEach(input => {

                if (input.name === "referredBy") {
                    return; // Skip validation for this field
                }
                
                const isHidden = input.offsetParent === null || getComputedStyle(input).display === 'none';

                if (input.disabled || isHidden) {
                    return; 
                }
                
                // Reset border color for input before applying red borders
                input.style.border = "";

                // Skip validation if this input is part of the "intensity" question
                if (
                    skipValidationQuestion &&
                    input.name.includes("physical_activity_and_exercise][intensity")
                ) {
                    return; // Skip validation for this input
                }
                
                // Check for validation errors (radio, checkbox, text fields)
                if (
                    (input.type === "radio" || input.type === "checkbox") &&
                    input.name &&
                    !document.querySelector(`input[name="${input.name}"]:checked`)
                ) {
                    input.style.border = "1px solid red";
                    isValid = false;
                } else if (
                    (input.type === "text" || input.type === "date" || input.tagName.toLowerCase() === "textarea" || input.tagName.toLowerCase() === "select") &&
                    !input.value.trim()
                ) {
                    input.style.border = "1px solid red";
                    isValid = false;
                }
            });

            // Display a general error message if the step is invalid
            const cardBody = stepTab.querySelector('.card-body');
            let errorMessageSpan = cardBody.querySelector('.general-error-message');

            if (!errorMessageSpan) {
                // Create error message span if not present
                errorMessageSpan = document.createElement("span");
                errorMessageSpan.className = "text-danger general-error-message";
                errorMessageSpan.textContent = errorMessage;
                cardBody.appendChild(errorMessageSpan);
            }

            errorMessageSpan.style.display = isValid ? "none" : "block";
            return isValid;
        }

        // Show step tabs on direct click
        showStepButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetStep = parseInt(button.getAttribute('target'), 10) - 1;
                console.log("Clicked button for step:", targetStep + 1);

                if (targetStep > currentStep && !validateStep(currentStep)) {
                    console.warn("Validation failed at step:", currentStep + 1);
                    return;
                }

                stepCircles.forEach((step, index) => {
                    step.classList.toggle('active', index <= targetStep);
                });

                stepTabs.forEach((tab, index) => {
                    tab.style.display = index === targetStep ? "block" : "none";
                });

                currentStep = targetStep;

                // Debugging logs
                console.log("Current step set to:", currentStep + 1);
                console.log("Scrolling to top...");

                setTimeout(() => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 100);
            });
        });

        let redirectUrl = null;
        // Handle form submission
        submitButton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default form submission

            // Validate the last step
            if (!validateStep(currentStep)) {
                return;
            }

            // Serialize form data
            const formData = new FormData(form);

            // Perform AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $.ajax({
                url: "{{ route('front.pre-plan-details.store') }}",
                method: 'POST',
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                success: function (response) {
                    // Show thank you modal
                    $('#thankYouModal').modal('show');
                    redirectUrl = response.redirect_url;
                    // Optional: Redirect after showing modal
                    // setTimeout(function () {
                    //     window.location.href = response.redirect_url;
                    // }, 3000); // Redirect after 3 seconds
                },
                error: function (xhr, status, error) {
                    console.error('Form submission failed:', xhr.responseText);
                    alert('Something went wrong! Please try again.');
                }
            });
        });

        $('#thankYouModal').on('hidden.bs.modal', function () {
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        })
    });

    // Initialize the dropdown with Select2 for search functionality
    // $('#raceEthnicityCulture').select2({
    //     placeholder: 'Select or search',
    //     allowClear: true
    // })

    // Fetch options from the server or AI API
    $.ajax({
        url: '{{ route("front.get-race-ethnicity-culture-options") }}', // Endpoint to fetch top 20 options
        method: 'GET',
        success: function (response) {
            if (response.error) {
                console.error('Error fetching options:', response.error);
                return;
            }

            // Append AI-generated options
            if (response.options && response.options.data) {
                // Append AI-generated options
                response.options.data.forEach(function (option) {
                    $('#raceEthnicityCulture').append(
                        $('<option>', { value: option.label, text: option.label }) // Use 'value' for both key and text
                    );
                });
            } else {
                response.options.forEach(function (option) {
                    $('#raceEthnicityCulture').append(
                        $('<option>', { value: option.label, text: option.label })
                    );
                });
            }

            // Add the "Other" option
            $('#raceEthnicityCulture').append(
                $('<option>', { value: 'other', text: 'Other' })
            );
        },
        error: function () {
            console.error('Failed to fetch options.');
        }
    });

    // Show an input box when "Other" is selected
    $('#raceEthnicityCulture').on('change', function () {
        if ($(this).val() === 'other') {
            $('#otherInputContainer').show();
        } else {
            $('#otherInputContainer').hide();
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

</script>
<script>
    $(document).ready(function () {
        // Check if a value is already selected on page load (in case of form pre-population)
        if ($('#bloodTest1').prop('checked')) {
            $('#fileUploadSection').show();
        } else {
            $('#fileUploadSection').hide();
        }

        // Toggle file upload visibility based on radio button selection
        $('input[name="ans[medical_history][blood_test]"]').on('change', function () {
            if ($('#bloodTest1').prop('checked')) {
                $('#fileUploadSection').show();  // Show file upload when "Yes" is selected
            } else {
                $('#bloodTestFile').val('');  // Reset the file input when "No" is selected
                $('#fileUploadSection').hide();  // Hide file upload when "No" is selected
            }
        });

        if ($('#bodycomposition1').prop('checked')) {
            $('#bodycompositionFileInput').show();
        } else {
            $('#bodycompositionFileInput').hide();
        }

        // Toggle file upload visibility based on radio button selection
        $('input[name="ans[physical_measures][bodycomposition]"]').on('change', function () {
            if ($('#bodycomposition1').prop('checked')) {
                $('#bodycompositionFileInput').show();  // Show file upload when "Yes" is selected
            } else {
                $('#bodycompositionFile').val('');  // Reset the file input when "No" is selected
                $('#bodycompositionFileInput').hide();  // Hide file upload when "No" is selected
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
    // document.addEventListener("DOMContentLoaded", () => {
    //     // const nextButtons = document.querySelectorAll('.next-step');
    //     // const prevButtons = document.querySelectorAll('.prev-step');
    //     const stepCircles = document.querySelectorAll('.tab-steps');
    //     const stepTabs = document.querySelectorAll(".step-tab-box");

    //     let currentStep = 0; // Track the active step index

    //     // Initially, show only the first step-tab-box
    //     stepTabs.forEach((tab, index) => {
    //         if (index === 0) {
    //             tab.style.display = "block";
    //         } else {
    //             tab.style.display = "none";
    //         }
    //     });
        
    //     // Show step tabs on direct click
    //     document.querySelectorAll('.showStepTab').forEach(button => {
    //         button.addEventListener('click', () => {
    //             const target = parseInt(button.getAttribute('target'), 10) - 1;
    //             if (target >= 0 && target < stepCircles.length) {
    //                 // Set all steps before the target to active
    //                 stepCircles.forEach((step, index) => {
    //                     if (index <= target) {
    //                         step.classList.add('active');
    //                     } else {
    //                         step.classList.remove('active');
    //                     }
    //                 });

    //                 // Update step tabs
    //                 stepTabs.forEach(tab => tab.style.display = "none");
    //                 stepTabs[target].style.display = "block";
    //                 currentStep = target;
    //             }
    //         });
    //     });
    // });


    // $(document).ready(function () {
    //     $('#submit-nutrition-form').on('click', function () {
    //         // Serialize form data
    //         event.preventDefault(); // Prevent the default form submission
    //         var formData = $('#nutrition-screen-form').serialize();

    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //             }
    //         });
    //         // Serialize form data
    //         // Perform AJAX request
    //         $.ajax({
    //             url: "{{ route('front.pre-plan-details.store') }}",
    //             method: 'POST',
    //             data: formData,
    //             success: function (response) {
    //                 // Show thank you modal
    //                 $('#thankYouModal').modal('show');

    //                 // Optional: Redirect after showing modal
    //                 setTimeout(function () {
    //                     window.location.href = response.redirect_url;
    //                 }, 3000); // Redirect after 3 seconds
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error('Form submission failed:', xhr.responseText);
    //                 alert('Something went wrong! Please try again.');
    //             }
    //         });
    //     });
    // });

</script>
@endsection