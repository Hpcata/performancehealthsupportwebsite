<!-- Nutrition Quiz Modal -->
<div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="quizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="signup-container quiz-container">
                <div class="signup-modal">
                    <button type="button" class="close-button" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>

                    <div class="form-section-content steps quiz" style="display: flex; align-items: center; min-height: 100%;">
                        <!-- Step 0: Welcome Screen -->
                        <div class="quiz-step" id="step-1" data-step="1" style="padding-bottom:0; width: 100%;">
                            <div>
                                <h2 class="welcome-title quiz-popup-title" style="margin-bottom:12px;">Nutrition
                                    knowledge quiz</h2>
                                <p class="quiz-popup-subtitle" style="margin-bottom:36px;">Take our 5-minute food
                                    quiz to learn how well you're fuelling your performance, and where you can level
                                    up.</p>

                                <!-- Continue Quiz Indicator -->
                                <div id="continue-quiz-indicator" style="display: none; background: #e3f2fd;
                                     border: 1px solid #2196f3; border-radius: 4px; padding: 12px; margin-bottom: 20px;
                                     color: #1976d2; font-size: 14px;">
                                    <strong>📝 Quiz in Progress</strong><br>
                                    You have an unfinished quiz. Click "Let's go!" to continue where you left off.
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-signup next-step-btn" id="start-quiz-btn" data-next="2"
                                        style="width:100%;">Let's go!</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Carbohydrate Selection -->
                        <div class="quiz-step" id="step-2" data-step="2" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 1/6</h3>
                                <div class="step-instruction">Select the foods that are high in
                                    <strong>carbohydrate.</strong>
                                </div>
                            </div>

                            <div class="food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/1.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="carb-chicken">
                                        <label for="carb-chicken" class="food-label">Chicken</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/2.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="carb-beans">
                                        <label for="carb-beans" class="food-label">Baked beans</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/3.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="carb-bread">
                                        <label for="carb-bread" class="food-label">Grain bread</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/4.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="carb-avocado">
                                        <label for="carb-avocado" class="food-label">Avocado</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/5.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="carb-weetbix">
                                        <label for="carb-weetbix" class="food-label">Weet-bix</label>
                                    </div>
                                </div>

                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/6.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="carb-yogurt">
                                        <label for="carb-yogurt" class="food-label">Fruit yogurt</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/7.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="carb-crumpets">
                                        <label for="carb-crumpets" class="food-label">Crumpets</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/8.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="carb-cream">
                                        <label for="carb-cream" class="food-label">Cream</label>
                                    </div>
                                </div>
                            </div>

                            <div class="unsure-option">
                                <label for="carb-unsure" class="unsure-label">Unsure?</label>
                                <input type="radio" class="unsure-radio" id="carb-unsure" name="carb-unsure">
                            </div>

                            <div class="quiz-navigation">
                                <button class="btn btn-back back-step-btn" data-prev="1">Back</button>
                                <button class="btn btn-signup next-step-btn" data-next="3">Next</button>
                            </div>
                        </div>

                        <!-- Step 2: Protein Selection -->
                        <div class="quiz-step" id="step-3" data-step="3" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 2/6</h3>
                                <div class="step-instruction">Select the foods that are high in <strong>protein.</strong></div>
                            </div>

                            <div class="five-grid food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/9.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-salmon">
                                        <label for="protein-salmon" class="food-label">Salmon</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/2.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-beans">
                                        <label for="protein-beans" class="food-label">Baked beans</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/10.png') }} "
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-fruit">
                                        <label for="protein-fruit" class="food-label">Fruit</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/11.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-hummus">
                                        <label for="protein-hummus" class="food-label">Hummus</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/12.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-cornflakes">
                                        <label for="protein-cornflakes" class="food-label">Cornflakes cereal</label>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/13.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-almonds">
                                        <label for="protein-almonds" class="food-label">Almonds</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/14.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-milk">
                                        <label for="protein-milk" class="food-label">Flavoured milk</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/15.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-almonds">
                                        <label for="protein-almonds" class="food-label">Ice cream</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/16.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="protein-milk">
                                        <label for="protein-milk" class="food-label">Almond/oat milk</label>
                                    </div>
                                </div>
                            </div>

                            <div class="unsure-option">
                                <input type="radio" class="unsure-radio" id="protein-unsure" name="protein-unsure">
                                <label for="protein-unsure" class="unsure-label">Unsure?</label>
                            </div>

                            <div class="quiz-navigation">
                                <button class="btn btn-back back-step-btn" data-prev="2">Back</button>
                                <button class="btn btn-signup next-step-btn" data-next="4">Next</button>
                            </div>
                        </div>

                        <!-- Step 3: Fat Selection -->
                        <div class="quiz-step" id="step-4" data-step="4" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 3/6</h3>
                                <div class="step-instruction">Select the foods that are high in <strong>fat.</strong></div>
                            </div>

                            <div class="food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/4.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="fat-avocado">
                                        <label for="fat-avocado" class="food-label">Avocado</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/2.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="fat-beans">
                                        <label for="fat-beans" class="food-label">Baked beans</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/17.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="fat-cottagecheese">
                                        <label for="fat-cottagecheese" class="food-label">Cottage cheese</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/18.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="fat-peanutbutter">
                                        <label for="fat-peanutbutter" class="food-label">Peanut butter</label>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/19.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="fat-crumpets">
                                        <label for="fat-crumpets" class="food-label">Crumpets</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/20.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="fat-cheddar">
                                        <label for="fat-cheddar" class="food-label">Cheddar/Tasty cheese</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/21.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="fat-cheddar">
                                        <label for="fat-cheddar" class="food-label">Cheddar/Tasty cheese</label>
                                    </div>
                                </div>
                            </div>

                            <div class="unsure-option">
                                <input type="radio" class="unsure-radio" id="fat-unsure" name="fat-unsure">
                                <label for="fat-unsure" class="unsure-label">Unsure?</label>
                            </div>

                            <div class="quiz-navigation">
                                <button class="btn btn-back back-step-btn" data-prev="3">Back</button>
                                <button class="btn btn-signup next-step-btn" data-next="5">Next</button>
                            </div>
                        </div>

                        <!-- Step 4: Healthy Fat Selection -->
                        <div class="quiz-step" id="step-5" data-step="5" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 4/6</h3>
                                <div class="step-instruction">Select the foods that are high in <strong>healthy fats.</strong></div>
                            </div>

                            <div class="food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/22.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="healthy-fat-butter">
                                        <label for="healthy-fat-butter" class="food-label">Butter</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/23.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="healthy-fat-extra-virgin-olive-oil">
                                        <label for="healthy-fat-extra-virgin-olive-oil" class="food-label">Extra virgin olive oil</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/24.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="healthy-fat-whole-milk">
                                        <label for="healthy-fat-whole-milk" class="food-label">Whole milk</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/25.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="healthy-fat-potato-chips">
                                        <label for="healthy-fat-potato-chips" class="food-label">Potato chips</label>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/26.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="healthy-fat-salmon">
                                        <label for="healthy-fat-salmon" class="food-label">Salmon</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/27.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="healthy-fat-dark-chocolate">
                                        <label for="healthy-fat-dark-chocolate" class="food-label">Dark chocolate</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/28.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="healthy-fat-macadamia-nuts">
                                        <label for="healthy-fat-macadamia-nuts" class="food-label">Macadamia nuts</label>
                                    </div>
                                </div>
                            </div>

                            <div class="unsure-option">
                                <input type="radio" class="unsure-radio" id="healthy-fat-unsure" name="healthy-fat-unsure">
                                <label for="healthy-fat-unsure" class="unsure-label">Unsure?</label>
                            </div>

                            <div class="quiz-navigation">
                                <button class="btn btn-back back-step-btn" data-prev="4">Back</button>
                                <button class="btn btn-signup next-step-btn" data-next="6">Next</button>
                            </div>
                        </div>

                        <!-- Step 5: Iron Selection -->
                        <div class="quiz-step" id="step-6" data-step="6" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 5/6</h3>
                                <div class="step-instruction">Which one of these foods has the most <strong>iron.</strong></div>
                            </div>

                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/29.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="iron-spinach">
                                        <label for="iron-spinach" class="food-label">Spinach, cooked, 1/2 cup</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/30.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="iron-brown-rice">
                                        <label for="iron-brown-rice" class="food-label">Brown rice, cooked, 1 cup</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/31.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="iron-grilled-steak">
                                        <label for="iron-grilled-steak" class="food-label">Grilled steak, 130g</label>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid two-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/32.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="iron-tuna">
                                        <label for="iron-tuna" class="food-label">Tuna, small tin, 125g</label>
                                    </div>
                                </div>


                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/33.png') }}"
                                                alt="Bowl of healthy food" class="" /></div>
                                        <input type="checkbox" class="food-checkbox" id="iron-almonds-cashews">
                                        <label for="iron-almonds-cashews" class="food-label">Almonds/cashews, 30 nuts</label>
                                    </div>
                                </div>

                            </div>

                            <div class="unsure-option">
                                <input type="radio" class="unsure-radio" id="iron-unsure" name="iron-unsure">
                                <label for="iron-unsure" class="unsure-label">Unsure?</label>
                            </div>

                            <div class="quiz-navigation">
                                <button class="btn btn-back back-step-btn" data-prev="5">Back</button>
                                <button class="btn btn-signup next-step-btn" data-next="7">Next</button>
                            </div>
                        </div>

                        <!-- Step 6: Multiple Choice Questions -->
                        <div class="quiz-step" id="step-7" data-step="7" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 6/6</h3>
                            </div>

                            <!-- Question 1 -->
                            <div class="question-container">
                                <div class="question-header">Approximately how many decisions do we make every day
                                    about what we eat?</div>
                                <div class="radio-options">
                                    <div class="radio-option">
                                        <input type="radio" id="q1-50-100" name="q1" value="50-100">
                                        <label for="q1-50-100">50-100</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q1-100-200" name="q1" value="100-200">
                                        <label for="q1-100-200">100-200</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q1-200-300" name="q1" value="200-300">
                                        <label for="q1-200-300">200-300</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q1-300-400" name="q1" value="300-400">
                                        <label for="q1-300-400">300-400</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 2 -->
                            <div class="question-container">
                                <div class="question-header">What percentage of people struggle with making healthy
                                    food choices?</div>
                                <div class="radio-options">
                                    <div class="radio-option">
                                        <input type="radio" id="q2-60" name="q2" value="60%">
                                        <label for="q2-60">60%</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q2-70" name="q2" value="70%">
                                        <label for="q2-70">70%</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q2-80" name="q2" value="80%">
                                        <label for="q2-80">80%</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q2-90" name="q2" value="90%">
                                        <label for="q2-90">90%</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 3 -->
                            <div class="question-container">
                                <div class="question-header">How many calories does the average person consume in a
                                    day?</div>
                                <div class="radio-options">
                                    <div class="radio-option">
                                        <input type="radio" id="q3-1500-2000" name="q3" value="1500-2000">
                                        <label for="q3-1500-2000">1500-2000</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q3-2000-2500" name="q3" value="2000-2500">
                                        <label for="q3-2000-2500">2000-2500</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q3-2500-3000" name="q3" value="2500-3000">
                                        <label for="q3-2500-3000">2500-3000</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q3-3000-3500" name="q3" value="3000-3500">
                                        <label for="q3-3000-3500">3000-3500</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 4 -->
                            <div class="question-container">
                                <div class="question-header">What is the most common reason people give up on their
                                    nutrition goals?</div>
                                <div class="radio-options">
                                    <div class="radio-option">
                                        <input type="radio" id="q4-lack-time" name="q4" value="Lack of time">
                                        <label for="q4-lack-time">Lack of time</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q4-lack-knowledge" name="q4"
                                            value="Lack of knowledge">
                                        <label for="q4-lack-knowledge">Lack of knowledge</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q4-lack-motivation" name="q4"
                                            value="Lack of motivation">
                                        <label for="q4-lack-motivation">Lack of motivation</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q4-lack-support" name="q4" value="Lack of support">
                                        <label for="q4-lack-support">Lack of support</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 5 -->
                            <div class="question-container">
                                <div class="question-header">How many different nutrients does the human body need
                                    to function properly?</div>
                                <div class="radio-options">
                                    <div class="radio-option">
                                        <input type="radio" id="q5-20-30" name="q5" value="20-30">
                                        <label for="q5-20-30">20-30</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q5-30-40" name="q5" value="30-40">
                                        <label for="q5-30-40">30-40</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q5-40-50" name="q5" value="40-50">
                                        <label for="q5-40-50">40-50</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="q5-50-60" name="q5" value="50-60">
                                        <label for="q5-50-60">50-60</label>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation">
                                <button class="btn btn-back back-step-btn" data-prev="6">Back</button>
                                <button class="btn btn-signup next-step-btn" data-next="8">Submit</button>
                            </div>
                        </div>

                        <!-- Step 7: Results/Completion -->
                        <div class="quiz-step completed" id="step-8" data-step="8" style="display: none;">
                            <div class="quiz-final-container" style="display: flex; min-height: 600px;">
                                <!-- Left: Text and form -->
                                <div class="quiz-final-left"
                                    style="flex: 1; padding: 40px 32px 32px 0px; display: flex; flex-direction: column; justify-content: center;">
                                    <h2 style="color: var(--Blue-700, #1751AA);
                                        font-family: Poppins;
                                        font-size: 28px;
                                        font-style: italic;
                                        font-weight: 800;
                                        line-height: 36px; /* 128.571% */
                                        text-transform: uppercase;"
                                        >
                                        WELL DONE LEGEND! YOU SCORED 11% BETTER THAN THE AVERAGE.
                                    </h2>
                                    <div style="color: var(--kerry-grey-600-main, #3B3B3B);
                                        font-size: 16px;
                                        font-style: normal;
                                        font-weight: 400;
                                        line-height: normal;
                                        margin-bottom:36px;"
                                        >
                                        Keen to find out more? Sign up for free to unlock your results and get
                                        access to useful tools to help you take control of your nutrition.
                                    </div>
                                    <input type="email" placeholder="Enter email address" class="form-control"
                                        style="margin-bottom: 16px;">
                                    <div class="or-divider" style="margin-left:0;margin-right:0;">
                                        <div class="divider-line"></div>
                                        <span class="or-text">OR</span>
                                        <div class="divider-line"></div>
                                    </div>
                                    <div class="social-buttons">
                                        <button class="social-button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M11.7643 2.24729C9.71461 2.21578 7.70395 2.80909 5.99975 3.94829C1.9895 6.62804 0.423504 11.7918 2.26925 16.2483C4.11425 20.7048 8.8715 23.2458 13.6025 22.3053C18.3335 21.3641 21.7558 17.194 21.7558 12.3708H21.7513V11.2458H12.7513V14.2458H18.6163C18.2678 15.5505 17.5604 16.7315 16.5745 17.6544C15.5885 18.5772 14.3634 19.2051 13.0385 19.4666C11.3965 19.796 9.69112 19.5445 8.21417 18.755C6.73723 17.9656 5.58059 16.6873 4.94225 15.1391C4.29907 13.593 4.21318 11.8716 4.69928 10.2692C5.18539 8.66681 6.21326 7.28313 7.607 6.35503C8.99776 5.42235 10.6695 5.00212 12.336 5.16631C14.0024 5.33049 15.56 6.06893 16.742 7.25508L18.7895 5.20906C16.9237 3.34331 14.4027 2.28046 11.7643 2.24729Z"
                                                    fill="#EA4335" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M4.72797 14.5527L2.32422 16.3662C4.20522 20.7477 8.91372 23.2385 13.602 22.3062C15.7059 21.8854 17.621 20.8051 19.0695 19.2222L16.8045 17.4102C15.9341 18.3091 14.8451 18.9665 13.6441 19.3179C12.4432 19.6692 11.1716 19.7024 9.95395 19.4143C8.73631 19.1262 7.61443 18.5266 6.69829 17.6743C5.78215 16.8221 5.10319 15.7464 4.72797 14.5527Z"
                                                    fill="#34A853" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M3.37198 6.50391C1.40398 9.28116 0.905983 12.9576 2.26948 16.2479C2.30023 16.3229 2.36548 16.4721 2.36548 16.4721C3.38098 15.8856 4.13098 15.3711 4.84498 14.8851C4.44106 13.8024 4.30299 12.6384 4.44237 11.4912C4.58176 10.344 4.99453 9.24708 5.64598 8.29257C4.13098 7.12107 4.13098 7.12116 3.37198 6.50391Z"
                                                    fill="#FBBC05" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M12.752 11.2461V14.2461H18.617C18.2941 15.4385 17.6701 16.5277 16.805 17.4095L19.0715 19.2215C20.7951 17.3563 21.7536 14.9108 21.7565 12.3711H21.752V11.2461H12.752Z"
                                                    fill="#4788F4" />
                                            </svg>
                                            Continue with Google
                                            <div>&nbsp;</div>
                                        </button>
                                        <button class="social-button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <g clip-path="url(#clip0_2763_5867)">
                                                    <path
                                                        d="M24 12C24 5.37264 18.6274 0 12 0C5.37264 0 0 5.37264 0 12C0 17.6275 3.87456 22.3498 9.10128 23.6467V15.6672H6.62688V12H9.10128V10.4198C9.10128 6.33552 10.9498 4.4424 14.9597 4.4424C15.72 4.4424 17.0318 4.59168 17.5685 4.74048V8.06448C17.2853 8.03472 16.7933 8.01984 16.1822 8.01984C14.2147 8.01984 13.4544 8.76528 13.4544 10.703V12H17.3741L16.7006 15.6672H13.4544V23.9122C19.3963 23.1946 24.0005 18.1354 24.0005 12H24Z"
                                                        fill="#0866FF" />
                                                    <path
                                                        d="M16.7007 15.6662L17.3742 11.999H13.4545V10.702C13.4545 8.76429 14.2148 8.01885 16.1823 8.01885C16.7934 8.01885 17.2854 8.03373 17.5686 8.06349V4.73949C17.0319 4.59021 15.7201 4.44141 14.9598 4.44141C10.9498 4.44141 9.10135 6.33453 9.10135 10.4188V11.999H6.62695V15.6662H9.10135V23.6457C10.0297 23.8761 11.0007 23.999 12.0001 23.999C12.4921 23.999 12.9774 23.9688 13.454 23.9112V15.6662H16.7002H16.7007Z"
                                                        fill="white" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2763_5867">
                                                        <rect width="24" height="24" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            Continue with Facebook
                                            <div>&nbsp;</div>
                                        </button>
                                        <button class="social-button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20"
                                                viewBox="0 0 21 20" fill="none" style="margin-right: 18px">
                                                <g clip-path="url(#clip0_2763_5873)">
                                                    <path
                                                        d="M18.6593 15.5861C18.3569 16.2848 17.9988 16.928 17.584 17.5194C17.0186 18.3255 16.5557 18.8835 16.1989 19.1934C15.6458 19.702 15.0533 19.9625 14.4187 19.9773C13.9632 19.9773 13.4138 19.8477 12.7743 19.5847C12.1327 19.323 11.5431 19.1934 11.004 19.1934C10.4386 19.1934 9.83219 19.323 9.18357 19.5847C8.53396 19.8477 8.01064 19.9847 7.61053 19.9983C7.00203 20.0242 6.39551 19.7563 5.7901 19.1934C5.40369 18.8563 4.92037 18.2786 4.34138 17.4601C3.72016 16.586 3.20944 15.5725 2.80933 14.417C2.38082 13.1689 2.16602 11.9603 2.16602 10.7902C2.16602 9.44984 2.45564 8.29383 3.03574 7.32509C3.49165 6.54697 4.09818 5.93316 4.85729 5.48255C5.6164 5.03195 6.43662 4.80233 7.31992 4.78764C7.80324 4.78764 8.43705 4.93714 9.22468 5.23096C10.0101 5.52576 10.5144 5.67526 10.7355 5.67526C10.9008 5.67526 11.461 5.50045 12.4107 5.15195C13.3089 4.82875 14.0669 4.69492 14.6878 4.74764C16.3705 4.88344 17.6347 5.54675 18.4754 6.74177C16.9705 7.6536 16.2261 8.93072 16.2409 10.5691C16.2545 11.8452 16.7174 12.9071 17.6272 13.7503C18.0396 14.1417 18.5001 14.4441 19.0124 14.6589C18.9013 14.9812 18.784 15.2898 18.6593 15.5861V15.5861ZM14.8002 0.400114C14.8002 1.40034 14.4348 2.33425 13.7064 3.19867C12.8274 4.22629 11.7642 4.8201 10.6113 4.7264C10.5966 4.60641 10.5881 4.48011 10.5881 4.3474C10.5881 3.38718 11.0061 2.35956 11.7484 1.51934C12.119 1.09392 12.5904 0.74019 13.162 0.458013C13.7323 0.180046 14.2718 0.0263242 14.7792 0C14.794 0.133715 14.8002 0.267438 14.8002 0.400101V0.400114Z"
                                                        fill="black" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2763_5873">
                                                        <rect width="20" height="20" fill="white"
                                                            transform="translate(0.5)" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            Sign in with Apple
                                            <div>&nbsp;</div>
                                        </button>
                                    </div>
                                    <div style="margin-top: 24px; color: #6b7280; font-size: 0.95rem;">
                                        Already have an account? <a href="#" class="login-link"
                                            style="color: #2563eb;">Log in</a>
                                    </div>
                                </div>
                                <!-- Right: Image -->
                                <div class="quiz-final-right"
                                    style="flex: 1; display: flex; align-items: center; justify-content: end; ">
                                    <div style="">
                                        <img src="{{ frontAssets('images/quiz/vector.png') }}" alt="Quiz Result"
                                            style="max-width: 100%; max-height: 100%; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="image-section" id="quiz-image-section">
                        <img src="{{ frontAssets('images/quiz/quiz-bg.png') }}" alt="Bowl of healthy food" class="food-image"
                            id="quiz-main-image" />
                        <img src="{{ frontAssets('images/quiz/signup-bg.png') }}" alt="Signup background" class="food-image signup-image"
                            id="quiz-signup-image" style="display: none;" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>