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
                </div>
            </div>

            <div class="tab-main-box">
                <form id="nutrition-screen-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $userId }}" />
                    <input type="hidden" name="payment_id" value="{{ $paymentId }}" />
                    <div class="step-tab-box" id="div1">
                        <div class="card">
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
                                            <label>Address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="referredBy" placeholder="">
                                            <label>Referred by</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-floating my-3">
                                            <input type="text" class="form-control" name="other" placeholder="">
                                            <label>Race/ethnicity/culture</label>
                                        </div>
                                    </div>
                                    
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
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <h5>Have you recently been diagnosed with any of the following: </h5>
                                        <input type="hidden" name="questions[medical_history][diagnosed]" value="Have you recently been diagnosed with any of the following:">

                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" value="Sports-related injury" name="ans[medical_history][diagnosed][]" id="diagnosed1">
                                                <label class="form-check-label" for="diagnosed1">
                                                    Sports-related injury
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
                                                <input class="form-check-input" type="checkbox" name="ans[medical_history][diagnosed][]" value="Disordered eating" id="diagnosed4">
                                                <label class="form-check-label" for="diagnosed4">
                                                    Disordered eating
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[medical_history][diagnosed][]" value="Other" id="diagnosed5">
                                                <label class="form-check-label" for="diagnosed5">
                                                    Other:
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>Provide details of any prescription medications (if taking any):</label>
                                            <input type="hidden" name="questions[medical_history][prescription_meds]" value="Provide details of any prescription medications (if taking any):">
                                            <input type="text" class="form-control" name="ans[medical_history][prescription_meds]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>List any dietary vitamins or supplements you are <strong>currently</strong> taking (if any):</label>
                                            <input type="hidden" name="questions[medical_history][vitamins_supplements]" value="List any dietary vitamins or supplements you are currently taking (if any):">
                                            <input type="text" class="form-control" name="ans[medical_history][vitamins_supplements]" placeholder="">
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
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][bodycomposition]" value="Changing (fluctuating)" id="bodycomposition1">
                                                <label class="form-check-label" for="bodycomposition1">
                                                    Changing (fluctuating)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[physical_measures][bodycomposition]" value="Unsure" id="bodycomposition2">
                                                <label class="form-check-label" for="bodycomposition2">
                                                    Unsure
                                                </label>
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

                    <div class="step-tab-box " id="div4">
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
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][livingwith]" value="Other" id="livingwith5">
                                                <label class="form-check-label" for="livingwith5">
                                                    Other:
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>Who does most of the cooking at home? </h5>
                                        <input type="hidden" name="questions[social_information][cookinghome]" value="Who does most of the cooking at home?" />
                                        <div class="form-floating my-3">
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
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="You" id="cookinghome3">
                                                <label class="form-check-label" for="lcookinghome3">
                                                    You
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Partner" id="cookinghome4">
                                                <label class="form-check-label" for="cookinghome4">
                                                    Partner
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookinghome]" value="Other" id="cookinghome5">
                                                <label class="form-check-label" for="cookinghome5">
                                                    Other:
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
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[social_information][cookingskills]" value="Other" id="cookingskills6">
                                                <label class="form-check-label" for="cookingskills6">
                                                    Other:
                                                </label>
                                            </div>
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
                                        <h5>Do you have any special dietary needs (e.g. Coeliac  - Gluten free) </h5>
                                        <input type="hidden" name="questions[dietary_information][dietaryneeds]" value="Do you have any special dietary needs (e.g. Coeliac  - Gluten free)" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[dietary_information][dietaryneeds]" value="No" id="dietaryneeds1">
                                                <label class="form-check-label" for="dietaryneeds1">
                                                    No
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[dietary_information][dietaryneeds]" value="If Yes, please specify" id="dietaryneeds2">
                                                <label class="form-check-label" for="dietaryneeds2">
                                                    If Yes, please specify
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[dietary_information][dietaryneeds]" value="Other" id="dietaryneeds3">
                                                <label class="form-check-label" for="dietaryneeds3">
                                                    Other:
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Do you tend to follow any particular way of eating? (more than 1 box can be checked)</h5>
                                        <input type="hidden" name="questions[dietary_information][wayofeating]" value="Do you have any special dietary needs (e.g. Coeliac  - Gluten free)" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="No" id="wayofeating1">
                                                <label class="form-check-label" for="wayofeating1">No</label>
                                            </div>
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
                                                <input class="form-check-input" type="checkbox" name="ans[dietary_information][wayofeating][]" value="Other" id="wayofeating7">
                                                <label class="form-check-label" for="wayofeating7">Other:</label>
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
                                                        <td>Supper</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][supper]" value="Not hungry" id="Supper-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][supper]" value="Beginning to feel hungry" id="Supper-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][supper]" value="Pretty hungry" id="Supper-3"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][supper]" value="Very hungry" id="Supper-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[dietary_information][hunger][supper]" value="Starving" id="Supper-5"></td>
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
                                            <label>What are the most common takeaways you eat? Pizza, Indian, Chinese etc</label>
                                            <input type="hidden" name="questions[dietary_information][common_takeaways]" value="What are the most common takeaways you eat? Pizza, Indian, Chinese etc" />
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
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][flavour_taste][]" value="1" id="Flavour/taste-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][flavour_taste][]" value="2" id="Flavour/taste-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][flavour_taste][]" value="3" id="Flavour/taste-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Convenience</td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][convenience][]" value="1" id="Convenience-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][convenience][]" value="2" id="Convenience-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][convenience][]" value="3" id="Convenience-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nutritional value</td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][nutritional_value][]" value="1" id="Nutritionalvalue-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][nutritional_value][]" value="2" id="Nutritionalvalue-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="ans[dietary_information][flavour_taste][nutritional_value][]" value="3" id="Nutritionalvalue-3"></td>
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

                    <div class="step-tab-box " id="div6">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Nutrition Goals</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Which of the following nutrition related goals are you interested in working on?</h5>
                                        <input type="hidden" name="questions[nutrition_goals][related_goals]" value="Which of the following nutrition related goals are you interested in working on?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="asn[nutrition_goals][related_goals][]" value="Reduce bodyweight (i.e. weight loss)" id="relatedgoals1">
                                                <label class="form-check-label" for="relatedgoals1">Reduce bodyweight (i.e. weight loss)</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="asn[nutrition_goals][related_goals][]" value="Increase bodyweight (i.e. gain mass)" id="relatedgoals2">
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
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Improve health/immunity" id="relatedgoals5">
                                                <label class="form-check-label" for="relatedgoals5">Improve health/immunity</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Improving Sports Performance/Recovery" id="relatedgoals6">
                                                <label class="form-check-label" for="relatedgoals6">Improving Sports Performance/Recovery</label>
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
                                                <input class="form-check-input" type="checkbox" name="ans[nutrition_goals][related_goals][]" value="Competition nutrition strategies" id="relatedgoals9">
                                                <label class="form-check-label" for="relatedgoals9">Competition nutrition strategies</label>
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
                                    <div class="col-md-12">
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
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>What are the 3 top things you want to obtain from the consultation? </label>
                                            <input type="hidden" name="questions[nutrition_goals][topthings]" value="What are the 3 top things you want to obtain from the consultation?" />
                                            <input type="text" class="form-control" name="ans[nutrition_goals][topthings]" id="topthings" placeholder="">                                        
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Where do you currently get nutrition information from?</h5>
                                        <input type="hidden" name="questions[nutrition_goals][getnutrition]" value="Where do you currently get nutrition information from?" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[nutrition_goals][getnutrition]" value="Parents" id="getnutrition1">
                                                <label class="form-check-label" for="getnutrition1">
                                                    Parents
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[nutrition_goals][getnutrition]" value="Siblings (brother, sister)" id="getnutrition2">
                                                <label class="form-check-label" for="getnutrition2">
                                                    Siblings (brother, sister)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[nutrition_goals][getnutrition]" value="Social media (Instagram)" id="getnutrition3">
                                                <label class="form-check-label" for="getnutrition3">
                                                    Social media (Instagram)
                                                </label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[nutrition_goals][getnutrition]" value="Other" id="getnutrition4">
                                                <label class="form-check-label" for="getnutrition4">
                                                    Other:
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step" target="5">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step" target="7">Next</button>
                            </div>
                        </div>
                    </div>

                    <div class="step-tab-box " id="div7">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Nutrition Knowledge Questions</h4>
                                <p class="m-0">Answers to the 4 questions below will assist in providing targeted information. </p>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <h5>1. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">carbohydrate</strong>? (click on <strong class="text-primary">one</strong> box per food)</h5>
                                        <input type="hidden" name="questions[nutrition_knowledge][foods_carbohydrate]" value="Do you think these foods are high or low in carbohydrate?" />
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
                                                        <td>Chicken</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][chicken]" value="High" id="Chicken-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][chicken]" value="Low" id="Chicken-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][chicken]" value="Unsure" id="Chicken-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Baked beans</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][baked_beans]" value="High" id="Bakedbeans-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][baked_beans]" value="Low" id="Bakedbeans-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][baked_beans]" value="Unsure" id="Bakedbeans-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Grain bread</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][grain_bread]" value="High" id="GrainBread-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][grain_bread]" value="Low" id="GrainBread-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][grain_bread]" value="Unsure" id="GrainBread-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Avocado</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][avocado]" value="High" id="Avocado-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][avocado]" value="Low" id="Avocado-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][avocado]" value="Unsure" id="Avocado-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Weet-bix</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][weet_bix]" value="High" id="Weet-bix-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][weet_bix]" value="Low" id="Weet-bix-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][weet_bix]" value="Unsure" id="Weet-bix-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Fruit yoghurt</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][fruit_yoghurt]" value="High" id="FruitYoghurt-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][fruit_yoghurt]" value="Low" id="FruitYoghurt-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][fruit_yoghurt]" value="Unsure" id="FruitYoghurt-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Crumpets</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][crumpets]" value="High" id="Crumpets-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][crumpets]" value="Low" id="Crumpets-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][crumpets]" value="Unsure" id="Crumpets-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cream</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][cream]" value="High" id="Cream-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][cream]" value="Low" id="Cream-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_carbohydrate][cream]" value="Unsure" id="Cream-3"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <h5>2. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">protein</strong>? (click on <strong class="text-primary">one</strong> box per food)</h5>
                                        <input type="hidden" name="questions[nutrition_knowledge][foods_protein]" value="Do you think these foods are high or low in protein?" />
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
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][salmon]" value="High" id="Salmon-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][salmon]" value="Low" id="Salmon-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][salmon]" value="Unsure" id="Salmon-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Baked beans</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][baked_beans]" value="High" id="Bakedbeans-11"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][baked_beans]" value="Low" id="Bakedbeans-12"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][baked_beans]" value="Unsure" id="Bakedbeans-13"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Fruit</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][fruit]" value="High" id="Fruit-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][fruit]" value="Low" id="Fruit-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][fruit]" value="Unsure" id="Fruit-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Hummus</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][hummus]" value="High" id="Hummus-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][hummus]" value="Low" id="Hummus-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][hummus]" value="Unsure" id="Hummus-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cornflakes cereal</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][cornflakes_cereal]" value="High" id="CornflakesCereal-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][cornflakes_cereal]" value="Low" id="CornflakesCereal-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][cornflakes_cereal]" value="Unsure" id="CornflakesCereal-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Almonds</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][almonds]" value="High" id="Almonds-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][almonds]" value="Low" id="Almonds-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][almonds]" value="Unsure" id="Almonds-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Flavoured milk</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][flavoured_milk]" value="High" id="FlavouredMilk-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][flavoured_milk]" value="Low" id="FlavouredMilk-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][flavoured_milk]" value="Unsure" id="FlavouredMilk-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ice cream</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][ice_cream]" value="High" id="IceCream-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][ice_cream]" value="Low" id="IceCream-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][ice_cream]" value="Unsure" id="IceCream-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Almond/oat milk</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][almond_oat_milk]" value="High" id="Almond-oat-milk-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][almond_oat_milk]" value="Low" id="Almond-oat-milk-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_protein][almond_oat_milk]" value="Unsure" id="Almond-oat-milk-3"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <h5>3. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">fat</strong>? (click on <strong class="text-primary">one</strong> box per food)</h5>
                                        <input type="hidden" name="questions[nutrition_knowledge][foods_fat]" value="Do you think these foods are high or low in fat?" />
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
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][avocado]" value="High" id="Avocado-11"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][avocado]" value="Low" id="Avocado-12"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][avocado]" value="Unsure" id="Avocado-13"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Baked beans</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][backed_beans]" value="High" id="BakedBeans-21"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][backed_beans]" value="Low" id="BakedBeans-22"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][backed_beans]" value="Unsure" id="BakedBeans-23"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cottage cheese</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][cottage_cheese]" value="High" id="CottageCheese-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][cottage_cheese]" value="Low" id="CottageCheese-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][cottage_cheese]" value="Unsure" id="CottageCheese-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Peanut butter</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][peanut_butter]" value="High" id="PeanutButter-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][peanut_butter]" value="Low" id="PeanutButter-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][peanut_butter]" value="Unsure" id="PeanutButter-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Crumpets</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][crumpets]" value="High" id="Crumpets-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][crumpets]" value="Low" id="Crumpets-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][crumpets]" value="Unsure" id="Crumpets-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cheddar/Tatsy cheese</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][cheddar_tatsy_cheese]" value="High" id="CheddarTatsyCheese-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][cheddar_tatsy_cheese]" value="Low" id="CheddarTatsyCheese-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_fat][cheddar_tatsy_cheese]" value="Unsure" id="CheddarTatsyCheese-3"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-lg-12">
                                        <h5>4. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">healthy fats</strong>? (click on <strong class="text-primary">one</strong> box per food)</h5>
                                        <input type="hidden" name="questions[nutrition_knowledge][foods_healthy_fat]" value="Do you think these foods are high or low in healthy fat?" />
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
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][butter]" value="High" id="Butter-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][butter]" value="Low" id="Butter-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][butter]" value="Unsure" id="Butter-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Extra virgin olive oil</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][extra_virgin_olive_oil]"value="High" id="OliveOil-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][extra_virgin_olive_oil]" value="Low" id="OliveOil-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][extra_virgin_olive_oil]" value="Unsure" id="OliveOil-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Whole milk</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][whole_milk]" value="High" id="WholeMilk-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][whole_milk]" value="Low" id="WholeMilk-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][whole_milk]" value="Unsure" id="WholeMilk-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Potato crisps</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][potato_crisps]" value="High" id="PotatoCrisps-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][potato_crisps]" value="Low" id="PotatoCrisps-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][potato_crisps]" value="Unsure" id="PotatoCrisps-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Salmon</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][salmon]" value="High" id="Salmon-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][salmon]" value="Low" id="Salmon-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][salmon]" value="Unsure" id="Salmon-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dark chocolate</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][dark_chocolate]" value="High" id="DarkChocolate-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][dark_chocolate]" value="Low" id="DarkChocolate-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][dark_chocolate]" value="Unsure" id="DarkChocolate-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Macadamia nuts</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][macadamia_nuts]" value="High" id="MacadamiaNuts-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][macadamia_nuts]" value="Low" id="MacadamiaNuts-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition_knowledge][foods_healthy_fat][macadamia_nuts]" value="Unsure" id="MacadamiaNuts-3"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                                <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step" target="6">Back</button>
                                <button id="next" type="button" class="btn btn-primary ms-auto showStepTab next-step" target="8">Next</button>
                            </div>
                        </div>
                    </div>

                    <div class="step-tab-box " id="div8">
                        <div class="card">
                            <div class="bg-white card-header p-4">
                                <h4 class="m-0">Physical Activity and Exercise</h4>
                            </div>
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <h5>How many days per week and at what intensity do you normally train for your sport?</h5>
                                        <input type="hidden" name="questions[pysical_activity_and_exercise][intensity]" value="How many days per week and at what intensity do you normally train for your sport?" />
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
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][1-2][]" value="Low intensity" id="perweekintensity-1"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][1-2][]" value="Moderate intensity" id="perweekintensity-2"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][1-2][]" value="High intensity" id="perweekintensity-3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>3-4</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][3-4][]" value="Low intensity" id="perweekintensity-4"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][3-4][]" value="Moderate intensity" id="perweekintensity-5"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][3-4][]" value="High intensity" id="perweekintensity-6"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>5+</td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][5+][]" value="Low intensity" id="perweekintensity-7"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][5+][]" value="Moderate intensity" id="perweekintensity-8"></td>
                                                        <td class="text-center"><input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][intensity][5+][]" value="High intensity" id="perweekintensity-9"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <h5>What type of physical activity do you mainly do or compete in? (more than one can apply)</h5>
                                        <input type="hidden" name="questions[pysical_activity_and_exercise][physical_activity]" value="What type of physical activity do you mainly do or compete in? (more than one can apply)" />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][physical_activity]" id="physicalActivity-1" value="Action Sports - Surfing, Freestyle BMX, Skateboarding">
                                                <label class="form-check-label" for="physicalActivity-1">Action Sports - Surfing, Freestyle BMX, Skateboarding</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][physical_activity]" id="physicalActivity-2" value="Combat sports- Boxing, Brazilian Jiu Jitsu, Martial arts">
                                                <label class="form-check-label" for="physicalActivity-2">Combat sports- Boxing, Brazilian Jiu Jitsu, Martial arts</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][physical_activity]" id="physicalActivity-3" value="Team sports - rugby league/union, volleyball, touch football, soccer">
                                                <label class="form-check-label" for="physicalActivity-3">Team sports - rugby league/union, volleyball, touch football, soccer</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][physical_activity]" id="physicalActivity-4" value="Cardiovascular exercise such as jogging/running, cycling, swimming, hiking">
                                                <label class="form-check-label" for="physicalActivity-4">Cardiovascular exercise such as jogging/running, cycling, swimming, hiking</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][physical_activity]" id="physicalActivity-5" value="Weight (resistance) training">
                                                <label class="form-check-label" for="physicalActivity-5">Weight (resistance) training</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][physical_activity]" id="physicalActivity-6" value="Other">
                                                <label class="form-check-label" for="physicalActivity-6">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <h5>Do you <strong class="text-primary">CURRENTLY</strong> use any EXERCISE OR NUTRITION tracking devices/apps?  </h5>
                                        <input type="hidden" name="questions[pysical_activity_and_exercise][tracking_device]" value="Do you CURRENTLY use any EXERCISE OR NUTRITION tracking devices/apps? " />
                                        <div class="form-floating my-3">
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][tracking_device]" id="trackingDevices-1" value="Garmin or similar watch">
                                                <label class="form-check-label" for="trackingDevices-1">Garmin or similar watch</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][tracking_device]" id="trackingDevices-2" value="Oura ring">
                                                <label class="form-check-label" for="trackingDevices-2">Oura ring</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][tracking_device]" id="trackingDevices-3" value="Whoop band">
                                                <label class="form-check-label" for="trackingDevices-3">Whoop band</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][tracking_device]" id="trackingDevices-4" value="My Fitness Pal or similar">
                                                <label class="form-check-label" for="trackingDevices-4">My Fitness Pal or similar</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="ans[pysical_activity_and_exercise][tracking_device]" id="trackingDevices-5" value="Other">
                                                <label class="form-check-label" for="trackingDevices-5">Other:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="no-form-floating form-floating my-3">
                                            <label>If answered yes to the above question, what do you mainly track? (e.g. exercise, food sleep)</label>
                                            <input type="hidden" name="questions[pysical_activity_and_exercise][track]" value="If answered yes to the above question, what do you mainly track? (e.g. exercise, food sleep)" />
                                            <input type="text" class="form-control" name="ans[pysical_activity_and_exercise][track]" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white text-end py-3 card-footer d-flex px-4">
                            <button id="prev" type="button" class="btn btn-secondary me-auto showStepTab prev-step" target="7">Back</button>
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
                    <p class="mb-2">Your form submit successful.</p>
                    <p class="mb-4">Your plan will be finalized and ready within the next 24 hours.</p>
                    <button type="button" class="btn btn-primary w-50" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Listen for changes on checkboxes with "Other" value
        document.querySelectorAll('input[type="checkbox"][value="Other"]').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                let otherInputId = `${this.id}-input`;
                let otherInput = document.getElementById(otherInputId);

                if (this.checked) {
                    // Create and add a text input field with the original name
                    if (!otherInput) {
                        otherInput = document.createElement('input');
                        otherInput.type = 'text';
                        otherInput.className = 'form-control mt-2';
                        otherInput.name = this.name; // Use the checkbox name
                        otherInput.id = otherInputId;
                        otherInput.placeholder = 'Please specify...';
                        this.parentNode.appendChild(otherInput);
                    }
                } else {
                    // Remove the input field if it exists
                    if (otherInput) {
                        otherInput.remove();
                    }
                }
            });
        });

        // Listen for changes on radio buttons with "Other" value
        document.querySelectorAll('input[type="radio"][value="Other"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                let otherInputId = `${this.id}-input`;
                let otherInput = document.getElementById(otherInputId);

                if (this.checked) {
                    // Create and add a text input field with the original name
                    if (!otherInput) {
                        otherInput = document.createElement('input');
                        otherInput.type = 'text';
                        otherInput.className = 'form-control mt-2';
                        otherInput.name = this.name; // Use the radio button name
                        otherInput.id = otherInputId;
                        otherInput.placeholder = 'Please specify...';
                        this.parentNode.appendChild(otherInput);
                    }
                } else {
                    // Remove the input field if it exists
                    if (otherInput) {
                        otherInput.remove();
                    }
                }
            });
        });

        // Listen for changes on other radio buttons (not the "Other" option)
        document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                // Find the radio button group (same name attribute)
                let radioName = this.name;

                // If another radio button is selected, remove any "Other" input field created
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
        const stepTab = stepTabs[stepIndex]; // Get current step tab
        const inputs = stepTab.querySelectorAll('input, textarea, select');
        let isValid = true;
        const errorMessage = "Note: All questions are required. Please fill them out or select answers.";

        // Loop through each input to validate
        inputs.forEach(input => {
            // Reset border color for input before applying red borders
            input.style.border = "";

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

                // Optional: Redirect after showing modal
                setTimeout(function () {
                    window.location.href = response.redirect_url;
                }, 3000); // Redirect after 3 seconds
            },
            error: function (xhr, status, error) {
                console.error('Form submission failed:', xhr.responseText);
                alert('Something went wrong! Please try again.');
            }
        });
    });
});


</script>


<script>
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