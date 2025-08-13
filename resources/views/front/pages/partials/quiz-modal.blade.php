<!-- Nutrition Quiz Modal -->
<style>
    /* Radio button container positioning */
    .radio-button-container {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }

    /* Hide default radio button */
    .radio-button-container input[type="radio"] {
        display: none;
    }

    /* Custom radio button styling */
    .radio-label {
        display: inline-block;
        width: 24px;
        height: 24px;
        border: 2px solid #ddd;
        border-radius: 50%;
        background-color: white;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
    }

    /* Radio button hover effect */
    .radio-label:hover {
        border-color: #007bff;
        transform: scale(1.1);
    }

    /* Radio button selected state */
    .radio-button-container input[type="radio"]:checked + .radio-label {
        border-color: #007bff;
        background-color: #007bff;
    }

    /* Radio button selected state inner circle */
    .radio-button-container input[type="radio"]:checked + .radio-label::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: white;
    }

    /* Food image container positioning */
    .food-image-container {
        position: relative;
    }

    /* Ensure food items have proper spacing */
    .food-item {
        position: relative;
    }
</style>

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
                                <h2 class="welcome-title quiz-popup-title" style="margin-bottom:12px;">Nutrition<br />
                                    knowledge quiz</h2>
                                <p class="quiz-popup-subtitle" style="margin-bottom:36px;">Take our 2 minute food
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
                                    <button class="btn-signup" id="start-quiz-btn" data-next="2"
                                        style="width:100%;">Let's go!</button>
                                    <button class="btn-back" id="start-over-btn"
                                        style="margin-top: 10px; display: none;">Start Over</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Carbohydrate Selection -->
                        <div class="quiz-step" id="step-2" data-step="2" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 1/8</h3>
                                <div class="step-instruction">Do you think these foods are high or low in
                                    carbohydrate? (Select one answer per food)
                                </div>
                            </div>

                            <div class="food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/1.webp') }}"
                                                alt="Chicken" class="" /></div>
                                        <div class="food-label">Chicken</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="carb-chicken-high" name="carb-chicken"
                                                    value="high">
                                                <label for="carb-chicken-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-chicken-low" name="carb-chicken"
                                                    value="low">
                                                <label for="carb-chicken-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-chicken-unsure" name="carb-chicken"
                                                    value="unsure">
                                                <label for="carb-chicken-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/2.webp') }}"
                                                alt="Baked beans" class="" /></div>
                                        <div class="food-label">Baked beans</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="carb-bakedbeans-high" name="carb-bakedbeans"
                                                    value="high">
                                                <label for="carb-bakedbeans-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-bakedbeans-low" name="carb-bakedbeans"
                                                    value="low">
                                                <label for="carb-bakedbeans-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-bakedbeans-unsure"
                                                    name="carb-bakedbeans" value="unsure">
                                                <label for="carb-bakedbeans-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/3.webp') }}"
                                                alt="Grain bread" class="" /></div>
                                        <div class="food-label">Grain bread</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="carb-grainbread-high" name="carb-grainbread"
                                                    value="high">
                                                <label for="carb-grainbread-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-grainbread-low" name="carb-grainbread"
                                                    value="low">
                                                <label for="carb-grainbread-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-grainbread-unsure"
                                                    name="carb-grainbread" value="unsure">
                                                <label for="carb-grainbread-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/4.webp') }}"
                                                alt="Avocado" class="" /></div>
                                        <div class="food-label">Avocado</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="carb-avocado-high" name="carb-avocado"
                                                    value="high">
                                                <label for="carb-avocado-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-avocado-low" name="carb-avocado"
                                                    value="low">
                                                <label for="carb-avocado-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-avocado-unsure" name="carb-avocado"
                                                    value="unsure">
                                                <label for="carb-avocado-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/5.webp') }}"
                                                alt="Weet-bix" class="" /></div>
                                        <div class="food-label">Weet-bix</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="carb-weetbix-high" name="carb-weetbix"
                                                    value="high">
                                                <label for="carb-weetbix-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-weetbix-low" name="carb-weetbix"
                                                    value="low">
                                                <label for="carb-weetbix-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-weetbix-unsure" name="carb-weetbix"
                                                    value="unsure">
                                                <label for="carb-weetbix-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/6.webp') }}"
                                                alt="Fruit yogurt" class="" /></div>
                                        <div class="food-label">Fruit yogurt</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="carb-fruityogurt-high"
                                                    name="carb-fruityogurt" value="high">
                                                <label for="carb-fruityogurt-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-fruityogurt-low"
                                                    name="carb-fruityogurt" value="low">
                                                <label for="carb-fruityogurt-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-fruityogurt-unsure"
                                                    name="carb-fruityogurt" value="unsure">
                                                <label for="carb-fruityogurt-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/7.webp') }}"
                                                alt="Crumpets" class="" /></div>
                                        <div class="food-label">Crumpets</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="carb-crumpets-high" name="carb-crumpets"
                                                    value="high">
                                                <label for="carb-crumpets-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-crumpets-low" name="carb-crumpets"
                                                    value="low">
                                                <label for="carb-crumpets-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-crumpets-unsure" name="carb-crumpets"
                                                    value="unsure">
                                                <label for="carb-crumpets-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/8.webp') }}"
                                                alt="Cream" class="" /></div>
                                        <div class="food-label">Cream</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="carb-cream-high" name="carb-cream"
                                                    value="high">
                                                <label for="carb-cream-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-cream-low" name="carb-cream"
                                                    value="low">
                                                <label for="carb-cream-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="carb-cream-unsure" name="carb-cream"
                                                    value="unsure">
                                                <label for="carb-cream-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation">
                                <button class=" btn-back back-step-btn" data-prev="1">Back</button>
                                <button class=" btn-signup next-step-btn" data-next="3">Next</button>
                            </div>
                        </div>

                        <!-- Step 2: Protein Selection -->
                        <div class="quiz-step" id="step-3" data-step="3" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 2/8</h3>
                                <div class="step-instruction">Do you think these foods are high or low in protein?
                                    (Select one answer per food)</div>
                            </div>

                            <div class="five-grid food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/9.webp') }}"
                                                alt="Salmon" class="" /></div>
                                        <div class="food-label">Salmon</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-salmon-high" name="protein-salmon"
                                                    value="high">
                                                <label for="protein-salmon-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-salmon-low" name="protein-salmon"
                                                    value="low">
                                                <label for="protein-salmon-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-salmon-unsure" name="protein-salmon"
                                                    value="unsure">
                                                <label for="protein-salmon-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/2.webp') }}"
                                                alt="Baked beans" class="" /></div>
                                        <div class="food-label">Baked beans</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-baked-beans-high"
                                                    name="protein-baked-beans" value="high">
                                                <label for="protein-baked-beans-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-baked-beans-low"
                                                    name="protein-baked-beans" value="low">
                                                <label for="protein-baked-beans-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-baked-beans-unsure"
                                                    name="protein-baked-beans" value="unsure">
                                                <label for="protein-baked-beans-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/10.webp') }}"
                                                alt="Grapes" class="" /></div>
                                        <div class="food-label">Grapes</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-grapes-high" name="protein-grapes"
                                                    value="high">
                                                <label for="protein-grapes-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-grapes-low" name="protein-grapes"
                                                    value="low">
                                                <label for="protein-grapes-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-grapes-unsure" name="protein-grapes"
                                                    value="unsure">
                                                <label for="protein-grapes-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/11.webp') }}"
                                                alt="Hummus" class="" /></div>
                                        <div class="food-label">Hummus</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-hummus-high" name="protein-hummus"
                                                    value="high">
                                                <label for="protein-hummus-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-hummus-low" name="protein-hummus"
                                                    value="low">
                                                <label for="protein-hummus-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-hummus-unsure" name="protein-hummus"
                                                    value="unsure">
                                                <label for="protein-hummus-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/12.webp') }}"
                                                alt="Cornflakes cereal" class="" /></div>
                                        <div class="food-label">Cornflakes cereal</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-cornflakes-cereal-high"
                                                    name="protein-cornflakes-cereal" value="high">
                                                <label for="protein-cornflakes-cereal-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-cornflakes-cereal-low"
                                                    name="protein-cornflakes-cereal" value="low">
                                                <label for="protein-cornflakes-cereal-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-cornflakes-cereal-unsure"
                                                    name="protein-cornflakes-cereal" value="unsure">
                                                <label for="protein-cornflakes-cereal-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/13.webp') }}"
                                                alt="Almonds" class="" /></div>
                                        <div class="food-label">Almonds</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-almonds-high" name="protein-almonds"
                                                    value="high">
                                                <label for="protein-almonds-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-almonds-low" name="protein-almonds"
                                                    value="low">
                                                <label for="protein-almonds-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-almonds-unsure"
                                                    name="protein-almonds" value="unsure">
                                                <label for="protein-almonds-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/14.webp') }}"
                                                alt="Flavoured milk" class="" /></div>
                                        <div class="food-label">Flavoured milk</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-flavoured-milk-high"
                                                    name="protein-flavoured-milk" value="high">
                                                <label for="protein-flavoured-milk-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-flavoured-milk-low"
                                                    name="protein-flavoured-milk" value="low">
                                                <label for="protein-flavoured-milk-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-flavoured-milk-unsure"
                                                    name="protein-flavoured-milk" value="unsure">
                                                <label for="protein-flavoured-milk-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/15.webp') }}"
                                                alt="Ice cream" class="" /></div>
                                        <div class="food-label">Ice cream</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-ice-cream-high" name="protein-ice-cream"
                                                    value="high">
                                                <label for="protein-ice-cream-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-ice-cream-low" name="protein-ice-cream" value="low">
                                                <label for="protein-ice-cream-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-ice-cream-unsure" name="protein-ice-cream"
                                                    value="unsure">
                                                <label for="protein-ice-cream-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/16.webp') }}"
                                                alt="Oat milk" class="" /></div>
                                        <div class="food-label">Oat milk</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="protein-oat-milk-high" name="protein-oat-milk"
                                                    value="high">
                                                <label for="protein-oat-milk-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-oat-milk-low" name="protein-oat-milk"
                                                    value="low">
                                                <label for="protein-oat-milk-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="protein-oat-milk-unsure" name="protein-oat-milk"
                                                    value="unsure">
                                                <label for="protein-oat-milk-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation">
                                <button class=" btn-back back-step-btn" data-prev="2">Back</button>
                                <button class=" btn-signup next-step-btn" data-next="4">Next</button>
                            </div>
                        </div>

                        <!-- Step 3: Fat Selection -->
                        <div class="quiz-step" id="step-4" data-step="4" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 3/8</h3>
                                <div class="step-instruction">Do you think these foods are high or low in fat?
                                    (Select one answer per food)</div>
                            </div>

                            <div class="food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/4.webp') }}"
                                                alt="Avocado" class="" /></div>
                                        <div class="food-label">Avocado</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="fat-avocado-high" name="fat-avocado" value="high">
                                                <label for="fat-avocado-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-avocado-low" name="fat-avocado" value="low">
                                                <label for="fat-avocado-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-avocado-unsure" name="fat-avocado"
                                                    value="unsure">
                                                <label for="fat-avocado-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/2.webp') }}"
                                                alt="Baked beans" class="" /></div>
                                        <div class="food-label">Baked beans</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="fat-baked-beans-high" name="fat-baked-beans"
                                                    value="high">
                                                <label for="fat-baked-beans-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-baked-beans-low" name="fat-baked-beans"
                                                    value="low">
                                                <label for="fat-baked-beans-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-baked-beans-unsure" name="fat-baked-beans"
                                                    value="unsure">
                                                <label for="fat-baked-beans-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/17.webp') }}"
                                                alt="Cottage cheese" class="" /></div>
                                        <div class="food-label">Cottage cheese</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="fat-cottage-cheese-high" name="fat-cottage-cheese"
                                                    value="high">
                                                <label for="fat-cottage-cheese-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-cottage-cheese-low" name="fat-cottage-cheese"
                                                    value="low">
                                                <label for="fat-cottage-cheese-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-cottage-cheese-unsure" name="fat-cottage-cheese"
                                                    value="unsure">
                                                <label for="fat-cottage-cheese-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/18.webp') }}"
                                                alt="Peanut butter" class="" /></div>
                                        <div class="food-label">Peanut butter</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="fat-peanut-butter-high" name="fat-peanut-butter"
                                                    value="high">
                                                <label for="fat-peanut-butter-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-peanut-butter-low" name="fat-peanut-butter"
                                                    value="low">
                                                <label for="fat-peanut-butter-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-peanut-butter-unsure" name="fat-peanut-butter"
                                                    value="unsure">
                                                <label for="fat-peanut-butter-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/19.webp') }}"
                                                alt="Crumpets" class="" /></div>
                                        <div class="food-label">Crumpets</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="fat-crumpets-high" name="fat-crumpets" value="high">
                                                <label for="fat-crumpets-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-crumpets-low" name="fat-crumpets" value="low">
                                                <label for="fat-crumpets-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-crumpets-unsure" name="fat-crumpets" value="unsure">
                                                <label for="fat-crumpets-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/20.webp') }}"
                                                alt="Cheddar/Tasty cheese" class="" /></div>
                                        <div class="food-label">Cheddar/Tasty cheese</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="fat-tasty-cheese-high" name="fat-tasty-cheese"
                                                    value="high">
                                                <label for="fat-tasty-cheese-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-tasty-cheese-low" name="fat-tasty-cheese"
                                                    value="low">
                                                <label for="fat-tasty-cheese-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="fat-tasty-cheese-unsure" name="fat-tasty-cheese"
                                                    value="unsure">
                                                <label for="fat-tasty-cheese-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation">
                                <button class=" btn-back back-step-btn" data-prev="3">Back</button>
                                <button class=" btn-signup next-step-btn" data-next="5">Next</button>
                            </div>
                        </div>

                        <!-- Step 4: Healthy Fat Selection -->
                        <div class="quiz-step" id="step-5" data-step="5" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 4/8</h3>
                                <div class="step-instruction">Do you think these foods are high or low in healthy
                                    fats? (Select one answer per food)</div>
                            </div>

                            <div class="food-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/22.webp') }}"
                                                alt="Butter" class="" /></div>
                                        <div class="food-label">Butter</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-butter-high" name="healthy-fat-butter"
                                                    value="high">
                                                <label for="healthy-fat-butter-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-butter-low" name="healthy-fat-butter"
                                                    value="low">
                                                <label for="healthy-fat-butter-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-butter-unsure" name="healthy-fat-butter"
                                                    value="unsure">
                                                <label for="healthy-fat-butter-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/23.webp') }}"
                                                alt="Extra Virgin Olive Oil" class="" /></div>
                                        <div class="food-label">Extra Virgin Olive Oil</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-oliveoil-high" name="healthy-fat-oliveoil"
                                                    value="high">
                                                <label for="healthy-fat-oliveoil-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-oliveoil-low" name="healthy-fat-oliveoil"
                                                    value="low">
                                                <label for="healthy-fat-oliveoil-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-oliveoil-unsure" name="healthy-fat-oliveoil"
                                                    value="unsure">
                                                <label for="healthy-fat-oliveoil-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/24.webp') }}"
                                                alt="Full cream milk" class="" /></div>
                                        <div class="food-label">Full cream milk</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-milk-high" name="healthy-fat-milk" value="high">
                                                <label for="healthy-fat-milk-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-milk-low" name="healthy-fat-milk" value="low">
                                                <label for="healthy-fat-milk-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-milk-unsure" name="healthy-fat-milk"
                                                    value="unsure">
                                                <label for="healthy-fat-milk-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/25.webp') }}"
                                                alt="Potato chips" class="" /></div>
                                        <div class="food-label">Potato chips</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-chips-high" name="healthy-fat-chips"
                                                    value="high">
                                                <label for="healthy-fat-chips-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-chips-low" name="healthy-fat-chips" value="low">
                                                <label for="healthy-fat-chips-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-chips-unsure" name="healthy-fat-chips"
                                                    value="unsure">
                                                <label for="healthy-fat-chips-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/26.webp') }}"
                                                alt="Salmon" class="" /></div>
                                        <div class="food-label">Salmon</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-salmon-high" name="healthy-fat-salmon"
                                                    value="high">
                                                <label for="healthy-fat-salmon-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-salmon-low" name="healthy-fat-salmon"
                                                    value="low">
                                                <label for="healthy-fat-salmon-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-salmon-unsure" name="healthy-fat-salmon"
                                                    value="unsure">
                                                <label for="healthy-fat-salmon-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/27.webp') }}"
                                                alt="Dark chocolate" class="" /></div>
                                        <div class="food-label">Dark chocolate</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-chocolate-high" name="healthy-fat-chocolate"
                                                    value="high">
                                                <label for="healthy-fat-chocolate-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-chocolate-low" name="healthy-fat-chocolate"
                                                    value="low">
                                                <label for="healthy-fat-chocolate-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-chocolate-unsure" name="healthy-fat-chocolate"
                                                    value="unsure">
                                                <label for="healthy-fat-chocolate-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/28.webp') }}"
                                                alt="Macadamia Nuts" class="" /></div>
                                        <div class="food-label">Macadamia Nuts</div>
                                        <div class="radio-options">
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-macadamia-high" name="healthy-fat-macadamia"
                                                    value="high">
                                                <label for="healthy-fat-macadamia-high">High</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-macadamia-low" name="healthy-fat-macadamia"
                                                    value="low">
                                                <label for="healthy-fat-macadamia-low">Low</label>
                                            </div>
                                            <div class="radio-option">
                                                <input type="radio" id="healthy-fat-macadamia-unsure" name="healthy-fat-macadamia"
                                                    value="unsure">
                                                <label for="healthy-fat-macadamia-unsure">Unsure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation">
                                <button class=" btn-back back-step-btn" data-prev="4">Back</button>
                                <button class=" btn-signup next-step-btn" data-next="6">Next</button>
                            </div>
                        </div>

                        <!-- Step 5: Iron Selection -->
                        <div class="quiz-step" id="step-6" data-step="6" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 5/8</h3>
                                <div class="step-instruction">Which of these foods has the most iron? (Select one answer)</div>
                            </div>

                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/34.svg') }}" alt="Spinach" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="iron-selection" id="iron-spinach" value="Spinach, cooked, 1/2 cup">
                                            <label for="iron-spinach" class="radio-label"></label>
                                        </div>
                                        <label for="iron-spinach" class="food-label">Spinach, cooked, 1/2 cup</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/35.svg') }}" alt="Brown rice" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="iron-selection" id="iron-brown-rice" value="Brown rice, cooked, 1 cup">
                                            <label for="iron-brown-rice" class="radio-label"></label>
                                        </div>
                                        <label for="iron-brown-rice" class="food-label">Brown rice, cooked, 1 cup</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/36.svg') }}" alt="Grilled steak" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="iron-selection" id="iron-grilled-steak" value="Grilled steak, 130g">
                                            <label for="iron-grilled-steak" class="radio-label"></label>
                                        </div>
                                        <label for="iron-grilled-steak" class="food-label">Grilled steak, 130g</label>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/37.svg') }}"
                                                alt="Tuna" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="iron-selection" id="iron-tuna" value="Tuna, small tin, 90g">
                                            <label for="iron-tuna" class="radio-label"></label>
                                        </div>
                                        <label for="iron-tuna" class="food-label">Tuna, small tin, 90g</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/38.svg') }}"
                                                alt="Almonds/cashews" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="iron-selection" id="iron-almonds-cashews" value="Almonds/cashews, ~30 nuts">
                                            <label for="iron-almonds-cashews" class="radio-label"></label>
                                        </div>
                                        <label for="iron-almonds-cashews" class="food-label">Almonds/cashews, ~30 nuts</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/unsure.svg') }}"
                                                alt="Unsure" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="iron-selection" id="iron-unsure" value="Unsure">
                                            <label for="iron-unsure" class="radio-label"></label>
                                        </div>
                                        <label for="iron-unsure" class="food-label">Unsure</label>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation">
                                <button class=" btn-back back-step-btn" data-prev="5">Back</button>
                                <button class=" btn-signup next-step-btn" data-next="7">Next</button>
                            </div>
                        </div>

                        <!-- Step 6: Calcium Selection -->
                        <div class="quiz-step" id="step-7" data-step="7" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 6/8</h3>
                                <div class="step-instruction">Which of these foods has the most calcium? (Select one answer)</div>
                            </div>

                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/39.svg') }}"
                                                alt="Baby spinach" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="calcium-selection" id="calcium-baby-spinach" value="Baby spinach, 1 cup">
                                            <label for="calcium-baby-spinach" class="radio-label"></label>
                                        </div>
                                        <label for="calcium-baby-spinach" class="food-label">Baby spinach, 1
                                            cup</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/40.svg') }}"
                                                alt="Firm tofu" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="calcium-selection" id="calcium-firm-tofu" value="Firm tofu, 100g">
                                            <label for="calcium-firm-tofu" class="radio-label"></label>
                                        </div>
                                        <label for="calcium-firm-tofu" class="food-label">Firm tofu, 100g</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/41.svg') }}"
                                                alt="Tuna" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="calcium-selection" id="calcium-tuna" value="Tuna, small tin, 90g">
                                            <label for="calcium-tuna" class="radio-label"></label>
                                        </div>
                                        <label for="calcium-tuna" class="food-label">Tuna, small tin, 90
                                            g</label>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/42.svg') }}"
                                                alt="Almonds" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="calcium-selection" id="calcium-almonds" value="Almonds, 1/2 cup">
                                            <label for="calcium-almonds" class="radio-label"></label>
                                        </div>
                                        <label for="calcium-almonds" class="food-label">Almonds, 1/2 cup</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/unsure.svg') }}"
                                                alt="Unsure" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="calcium-selection" id="calcium-unsure" value="Unsure">
                                            <label for="calcium-unsure" class="radio-label"></label>
                                        </div>
                                        <label for="calcium-unsure" class="food-label">Unsure</label>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation">
                                <button class=" btn-back back-step-btn" data-prev="6">Back</button>
                                <button class=" btn-signup next-step-btn" data-next="8">Next</button>
                            </div>
                        </div>

                        <!-- Step 7: Fibre Selection -->
                        <div class="quiz-step" id="step-8" data-step="8" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 7/8</h3>
                                <div class="step-instruction">Which of these foods has the most fibre? (Select one answer)</div>
                            </div>

                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/43.svg') }}"
                                                alt="Banana" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="fibre-selection" id="fibre-banana" value="Banana, 1 large">
                                            <label for="fibre-banana" class="radio-label"></label>
                                        </div>
                                        <label for="fibre-banana" class="food-label">Banana, 1 large</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/44.svg') }}"
                                                alt="Raw oats" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="fibre-selection" id="fibre-raw-oats" value="Raw oats, 1/2 cup">
                                            <label for="fibre-raw-oats" class="radio-label"></label>
                                        </div>
                                        <label for="fibre-raw-oats" class="food-label">Raw oats, 1/2 cup</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/45.svg') }}"
                                                alt="Cashews" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="fibre-selection" id="fibre-cashews" value="Cashews, 1 handful">
                                            <label for="fibre-cashews" class="radio-label"></label>
                                        </div>
                                        <label for="fibre-cashews" class="food-label">Cashews, 1 handful</label>
                                    </div>
                                </div>
                            </div>
                            <div class="food-grid three-grid">
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/46.svg') }}"
                                                alt="Broccoli" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="fibre-selection" id="fibre-broccoli" value="Broccoli, 1/2 cup">
                                            <label for="fibre-broccoli" class="radio-label"></label>
                                        </div>
                                        <label for="fibre-broccoli" class="food-label">Broccoli, 1/2 cup</label>
                                    </div>
                                </div>
                                <div class="food-item">
                                    <div class="food-image-container">
                                        <div class="food-placeholder"><img src="{{ frontAssets('images/quiz/unsure.svg') }}"
                                                alt="Unsure" class="" /></div>
                                        <div class="radio-button-container">
                                            <input type="radio" name="fibre-selection" id="fibre-unsure" value="Unsure">
                                            <label for="fibre-unsure" class="radio-label"></label>
                                        </div>
                                        <label for="fibre-unsure" class="food-label">Unsure</label>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation">
                                <button class=" btn-back back-step-btn" data-prev="7">Back</button>
                                <button class=" btn-signup next-step-btn" data-next="9">Next</button>
                            </div>
                        </div>

                        <!-- Step 8: Multiple Choice Questions -->
                        <div class="quiz-step" id="step-9" data-step="9" style="display: none;">
                            <div class="quiz-step-header">
                                <h3 class="step-title">STEP 8/8</h3>
                            </div>

                            <div class=""
                                style="display: flex!important;flex-direction: column; justify-content: space-between;">
                                <!-- Question 1 -->
                                <div class="question-container">
                                    <div class="question-header">Approximately how many decisions do we make every
                                        day about what we eat?</div>
                                    <div class="radio-options">
                                        <div class="radio-option">
                                            <input type="radio" id="q1-option1" name="q1" value="50-100">
                                            <label for="q1-option1">50-100</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q1-option2" name="q1" value="100-150">
                                            <label for="q1-option2">100-150</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q1-option3" name="q1" value="150-200">
                                            <label for="q1-option3">150-200</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q1-option4" name="q1" value="Over 200">
                                            <label for="q1-option4">Over 200</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q1-unsure" name="q1" value="Unsure">
                                            <label for="q1-unsure">Unsure</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question 2 -->
                                <div class="question-container">
                                    <div class="question-header">Which of the following is NOT a 'Macronutrient'?
                                    </div>
                                    <div class="radio-options">
                                        <div class="radio-option">
                                            <input type="radio" id="q2-iron" name="q2" value="Iron">
                                            <label for="q2-iron">Iron</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q2-carbohydrate" name="q2" value="Carbohydrate">
                                            <label for="q2-carbohydrate">Carbohydrate</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q2-protein" name="q2" value="Protein">
                                            <label for="q2-protein">Protein</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q2-alcohol" name="q2" value="Alcohol">
                                            <label for="q2-alcohol">Alcohol</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q2-fat" name="q2" value="Fat">
                                            <label for="q2-fat">Fat</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="q2-unsure" name="q2" value="Unsure">
                                            <label for="q2-unsure">Unsure</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="quiz-navigation" style="margin-top: 201px;">
                                <button class=" btn-back back-step-btn" data-prev="8">Back</button>
                                <button class=" btn-signup next-step-btn">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div class="image-section" id="quiz-image-section">
                        <img src="{{ frontAssets('images/quiz-bg.webp') }}" alt="quiz-bg" class="food-image"
                            id="quiz-main-image" style="max-height:700px;"/>
                        <img src="{{ frontAssets('images/quiz/signup-bg.webp') }}" alt="Signup background"
                            class="food-image signup-image" id="quiz-signup-image" style="display: none;" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>