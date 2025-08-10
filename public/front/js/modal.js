document.addEventListener('DOMContentLoaded', function () {
    // Global variables
    let currentStep = 1;

    // Quiz state management with session storage
    const QUIZ_STORAGE_KEY = 'quiz_state';
    const QUIZ_ID_KEY = 'current_quiz_id';

    // Generate unique session identifier
    function generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    // Get or create session ID
    function getSessionId() {
        let sessionId = sessionStorage.getItem('quiz_session_id');
        if (!sessionId) {
            sessionId = generateSessionId();
            sessionStorage.setItem('quiz_session_id', sessionId);
        }
        return sessionId;
    }

    // Get current quiz ID from session storage
    function getCurrentQuizId() {
        return sessionStorage.getItem(QUIZ_ID_KEY);
    }

    // Set current quiz ID in session storage
    function setCurrentQuizId(quizId) {
        sessionStorage.setItem(QUIZ_ID_KEY, quizId);
    }

    // Clear current quiz ID from session storage
    function clearCurrentQuizId() {
        const quizId = getCurrentQuizId();
        sessionStorage.removeItem(QUIZ_ID_KEY);
    }

    // Store completed quiz ID for signup/login process
    function storeCompletedQuizId(quizId) {
        sessionStorage.setItem('completed_quiz_id', quizId);
    }

    // Get completed quiz ID for signup/login process
    function getCompletedQuizId() {
        return sessionStorage.getItem('completed_quiz_id');
    }

    // Clear completed quiz ID (call this after successful signup/login)
    function clearCompletedQuizId() {
        sessionStorage.removeItem('completed_quiz_id');
    }

    // Get quiz state
    function getQuizState() {
        return sessionStorage.getItem(QUIZ_STORAGE_KEY);
    }

    // Check if user has a completed quiz waiting for signup/login
    function hasCompletedQuiz() {
        return getCompletedQuizId() !== null;
    }

    // Load quiz state from session storage
    function loadQuizState() {
        const savedState = sessionStorage.getItem(QUIZ_STORAGE_KEY);
        if (savedState) {
            try {
                const state = JSON.parse(savedState);

                // Check if quiz state is not too old (24 hours)
                const now = Date.now();
                const quizAge = now - (state.timestamp || 0);
                const maxAge = 24 * 60 * 60 * 1000; // 24 hours in milliseconds

                if (quizAge > maxAge) {
                    clearQuizState();
                    clearCurrentQuizId();
                    return false;
                }

                // Set the quiz ID from session storage if it exists
                if (state.quizId) {
                    setCurrentQuizId(state.quizId);
                }

                currentStep = state.currentStep || 1;

                // Show continue quiz indicator if we have an unfinished quiz
                if (currentStep > 1) {
                    const indicator = document.getElementById('continue-quiz-indicator');
                    if (indicator) {
                        indicator.style.display = 'block';
                    }

                    // Show start over button
                    const startOverBtn = document.getElementById('start-over-btn');
                    if (startOverBtn) {
                        startOverBtn.style.display = 'block';
                    }
                }

                return true;
            } catch (e) {
                sessionStorage.removeItem(QUIZ_STORAGE_KEY);
                clearCurrentQuizId();
            }
        }
        return false;
    }

    // Restore quiz answers from session storage
    function restoreQuizAnswers() {
        const savedState = sessionStorage.getItem(QUIZ_STORAGE_KEY);
        if (savedState) {
            try {
                const state = JSON.parse(savedState);
                const savedAnswers = state.answers || {};

                // Restore answers for each step
                Object.keys(savedAnswers).forEach(stepNumber => {
                    const stepAnswers = savedAnswers[stepNumber];
                    restoreStepAnswers(parseInt(stepNumber), stepAnswers);
                });
            } catch (e) {
                console.error('Error restoring quiz answers:', e);
            }
        } else {
            console.log('No saved quiz state found');
        }
    }

    // Restore answers for a specific step
    function restoreStepAnswers(stepNumber, stepAnswers) {
        const currentStepElement = document.querySelector(`#step-${stepNumber + 1}`);
        if (!currentStepElement) return;

        // Extract the actual answers from the nested structure
        let answers = {};
        if (stepAnswers && stepAnswers['nutrition-form']) {
            if (stepNumber === 9) {
                // For step 9 (multiple choice), the data is stored directly
                answers = stepAnswers['nutrition-form'];
            } else {
                // For food selection, extract from the question key
                const questionKey = Object.keys(stepAnswers['nutrition-form'])[0];
                answers = stepAnswers['nutrition-form'][questionKey];
            }
        }

        // Restore radio button selections for steps 2-5
        if (stepNumber >= 2 && stepNumber <= 5) {
            Object.keys(answers).forEach(foodName => {
                const answer = answers[foodName];
                if (answer && answer.value === 1) {
                    const option = answer.option.toLowerCase();
                    const prefix = getPrefixForStep(stepNumber);

                    // Try different ID patterns for radio buttons
                    const possibleIds = [
                        `${prefix}-${foodName.toLowerCase().replace(/\s+/g, '')}-${option}`,
                        `${prefix}-${foodName.toLowerCase().replace(/\s+/g, '')}-${option}`,
                        `${prefix}-${foodName.toLowerCase().replace(/[^a-z0-9]/g, '')}-${option}`
                    ];

                    let radio = null;
                    for (const id of possibleIds) {
                        radio = document.getElementById(id);
                        if (radio) break;
                    }

                    // If not found by ID, try to find by name and value
                    if (!radio) {
                        const radioName = `${prefix}-${foodName.toLowerCase().replace(/\s+/g, '')}`;
                        const radios = document.querySelectorAll(`input[name="${radioName}"]`);
                        for (const r of radios) {
                            if (r.value === option) {
                                radio = r;
                                break;
                            }
                        }
                    }

                    if (radio) {
                        radio.checked = true;
                    }
                }
            });
        }

        // Restore radio button selections for step 6 (Iron selection)
        if (stepNumber === 6) {
            Object.keys(answers).forEach(optionName => {
                const answer = answers[optionName];
                if (answer && answer.value === 1) {
                    // Find radio button by value
                    const radio = document.querySelector(`input[name="iron-selection"][value="${optionName}"]`);
                    if (radio) {
                        radio.checked = true;
                    }
                }
            });
        }

        // Restore radio button selections for step 7 (Calcium selection)
        if (stepNumber === 7) {
            Object.keys(answers).forEach(optionName => {
                const answer = answers[optionName];
                if (answer && answer.value === 1) {
                    // Find radio button by value
                    const radio = document.querySelector(`input[name="calcium-selection"][value="${optionName}"]`);
                    if (radio) {
                        radio.checked = true;
                    }
                }
            });
        }

        // Restore radio button selections for step 8 (Fiber selection)
        if (stepNumber === 8) {
            Object.keys(answers).forEach(optionName => {
                const answer = answers[optionName];
                if (answer && answer.value === 1) {
                    // Find radio button by value
                    const radio = document.querySelector(`input[name="fibre-selection"][value="${optionName}"]`);
                    if (radio) {
                        radio.checked = true;
                    }
                }
            });
        }

        // Restore multiple choice selections for step 9
        if (stepNumber === 9) {
            Object.keys(answers).forEach(questionText => {
                const answer = answers[questionText];
                if (answer && answer.value === 1) {
                    const option = answer.option;
                    // Find the question container that contains this question text
                    const questionContainers = currentStepElement.querySelectorAll('.question-container');
                    questionContainers.forEach(container => {
                        const questionHeader = container.querySelector('.question-header');
                        if (questionHeader && questionHeader.textContent.trim() === questionText) {
                            const radio = container.querySelector(`input[value="${option}"]`);
                            if (radio) {
                                radio.checked = true;
                            }
                        }
                    });
                }
            });
        }
    }

    // Get prefix for step
    function getPrefixForStep(stepNumber) {
        switch(stepNumber) {
            case 2: return 'carb';
            case 3: return 'protein';
            case 4: return 'fat';
            case 5: return 'healthy-fat';
            case 6: return 'iron';
            case 7: return 'calcium';
            case 8: return 'fibre';
            default: return '';
        }
    }

    // Save quiz state to session storage
    function saveQuizState() {
        // Get existing state to preserve answers
        const existingState = sessionStorage.getItem(QUIZ_STORAGE_KEY);
        let existingAnswers = {};

        if (existingState) {
            try {
                const parsedState = JSON.parse(existingState);
                existingAnswers = parsedState.answers || {};
            } catch (e) {
                console.error('Error parsing existing state:', e);
            }
        }

        const state = {
            quizId: getCurrentQuizId(),
            currentStep: currentStep,
            timestamp: Date.now(),
            answers: existingAnswers // Preserve existing answers
        };
        sessionStorage.setItem(QUIZ_STORAGE_KEY, JSON.stringify(state));
    }

    // Save step answers to session storage
    function saveStepAnswers(stepNumber, stepData) {
        const savedState = sessionStorage.getItem(QUIZ_STORAGE_KEY);
        if (savedState) {
            try {
                const state = JSON.parse(savedState);
                if (!state.answers) {
                    state.answers = {};
                }
                state.answers[stepNumber] = stepData;
                sessionStorage.setItem(QUIZ_STORAGE_KEY, JSON.stringify(state));
            } catch (e) {
                console.error('Error saving step answers:', e);
            }
        } else {
            console.error('No saved state found when trying to save step answers');
        }
    }

    // Clear quiz state from session storage
    function clearQuizState() {
        sessionStorage.removeItem(QUIZ_STORAGE_KEY);
    }

    // Utility: scroll the quiz modal to the top (handles various containers and mobile viewport)
    function scrollQuizToTop() {
        try {
            const selectors = [
                '#quizModal .modal-content',
                '#quizModal .modal-dialog',
                '#quizModal .signup-modal',
                '#quizModal .signup-container',
                '#quizModal .form-section-content'
            ];
            let didScroll = false;
            selectors.forEach(selector => {
                const el = document.querySelector(selector);
                if (el) {
                    if (typeof el.scrollTo === 'function') {
                        el.scrollTo({ top: 0, behavior: 'auto' });
                    }
                    el.scrollTop = 0;
                    didScroll = true;
                }
            });

            // Fallback: scroll document/viewport (some mobile browsers scroll page instead of modal)
            if (!didScroll) {
                window.scrollTo({ top: 0, behavior: 'auto' });
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
            }
        } catch (e) {
            // no-op
        }
    }

    // Show specific step
    function showStep(stepNumber) {
        const allSteps = document.querySelectorAll('.quiz-step');
        const imageSection = document.getElementById('quiz-image-section');

        allSteps.forEach((step, index) => {
            const stepDataStep = parseInt(step.getAttribute('data-step'));
            if (stepDataStep === stepNumber) {
                step.style.display = 'flex';
            } else {
                step.style.display = 'none';
            }
        });

        // Handle image section visibility
        if (stepNumber === 1) {
            if (imageSection) imageSection.style.display = 'block';
        } else {
            if (imageSection) imageSection.style.display = 'none';
        }

        currentStep = stepNumber;
        saveQuizState();

        // Ensure the modal content is positioned at the top after step change (especially on mobile)
        requestAnimationFrame(() => {
            scrollQuizToTop();
        });
    }

    // Quiz step navigation functionality
    const nextButtons = document.querySelectorAll('.next-step-btn');
    const backButtons = document.querySelectorAll('.back-step-btn');
    const foodContainers = document.querySelectorAll('.food-image-container');
    const startQuizBtn = document.getElementById('start-quiz-btn');

    // Start quiz functionality
    if (startQuizBtn) {
        startQuizBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Check if we have an existing quiz state
            const savedState = sessionStorage.getItem(QUIZ_STORAGE_KEY);
            if (savedState) {
                try {
                    const state = JSON.parse(savedState);
                    const savedAnswers = state.answers || {};

                    // Find the highest step number that has answers
                    const answeredSteps = Object.keys(savedAnswers).map(Number).filter(step => step > 0);
                    let resumeStep = 1;

                    if (answeredSteps.length > 0) {
                        // Resume from the step after the highest answered step
                        const highestAnsweredStep = Math.max(...answeredSteps);
                        resumeStep = highestAnsweredStep + 1;

                        // If we have completed all steps (step 9), show the results screen
                        if (resumeStep > 9) {
                            // Quiz is completed, show results/completion screen
                            // showStep(10); // Assuming step 10 is the results screen
                            $('#quizModal').modal('hide');
                            openSingupFreePopup(false, true);
                            return;
                        }

                    } else {
                        // No answers found, start from step 2
                        resumeStep = 2;
                    }

                    // If we have a step to resume from, continue from there
                    if (resumeStep > 1) {
                        showStep(resumeStep);
                        // Restore all previous answers after a short delay to ensure DOM is ready
                        setTimeout(() => {
                            restoreQuizAnswers();
                        }, 100);
                        return;
                    }
                } catch (e) {
                    // If there's an error parsing the state, start fresh
                    console.warn('Error parsing saved quiz state, starting fresh');
                }
            } else {
                // Check if there's an existing quiz in progress
                const currentQuizId = getCurrentQuizId();

                if (currentQuizId) {
                    // Continue existing quiz
                    showStep(2);
                    return;
                }
            }


            $.ajax({
                url: window.quizConfig.startQuizUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.quizConfig.csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        setCurrentQuizId(response.quiz_id);

                        // Clear all form selections for fresh quiz
                        clearAllFormSelections();

                        // Navigate to the next step (step 2)
                        showStep(2);
                    } else {
                        alert('Error starting quiz: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error starting quiz. Please try again.');
                }
            });
        });
    }

        // Start over button functionality
    const startOverBtn = document.getElementById('start-over-btn');
    if (startOverBtn) {
        startOverBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Get current quiz ID before clearing
            const currentQuizId = getCurrentQuizId();

            // First, abandon the current quiz if it exists
            if (currentQuizId) {
                $.ajax({
                    url: window.quizConfig.abandonUrl,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.quizConfig.csrfToken
                    },
                    data: {
                        quiz_id: currentQuizId
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Previous quiz abandoned successfully');
                        } else {
                            console.warn('Failed to abandon previous quiz:', response.message);
                        }
                        // Continue with starting fresh quiz regardless of abandon result
                        startFreshQuiz();
                    },
                    error: function(xhr) {
                        console.warn('Error abandoning previous quiz, continuing with fresh start');
                        // Continue with starting fresh quiz even if abandon fails
                        startFreshQuiz();
                    }
                });
            } else {
                // No current quiz to abandon, start fresh directly
                startFreshQuiz();
            }

            function startFreshQuiz() {
                // Clear all saved state
                clearQuizState();
                clearCurrentQuizId();
                clearCompletedQuizId();

                // Hide continue indicator and start over button
                const indicator = document.getElementById('continue-quiz-indicator');
                if (indicator) {
                    indicator.style.display = 'none';
                }
                startOverBtn.style.display = 'none';

                // Start fresh quiz
                $.ajax({
                    url: window.quizConfig.startQuizUrl,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.quizConfig.csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            setCurrentQuizId(response.quiz_id);

                            // Clear all form selections for fresh quiz
                            clearAllFormSelections();

                            showStep(2);
                        } else {
                            alert('Error starting quiz: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error starting quiz. Please try again.');
                    }
                });
            }
        });
    }

    // Collect step data based on step type
    function collectStepData(stepNumber) {
        const stepData = {};

        switch(stepNumber) {
            case 2: // Carbohydrate selection
                const carbData = collectRadioData('carb-');
                stepData['nutrition-form'] = {
                    'Do you think these foods are high or low in carbohydrate? (Select one answer per food)': carbData
                };
                break;
            case 3: // Protein selection
                const proteinData = collectRadioData('protein-');
                stepData['nutrition-form'] = {
                    'Do you think these foods are high or low in protein? (Select one answer per food)': proteinData
                };
                break;
            case 4: // Fat selection
                const fatData = collectRadioData('fat-');
                stepData['nutrition-form'] = {
                    'Do you think these foods are high or low in fat? (Select one answer per food)': fatData
                };
                break;
            case 5: // Healthy fat selection
                const healthyFatData = collectRadioData('healthy-fat-');
                stepData['nutrition-form'] = {
                    'Do you think these foods are high or low in healthy fats? (Select one answer per food)': healthyFatData
                };
                break;
            case 6: // Iron selection
                const ironData = collectSingleChoiceData('iron-selection');
                stepData['nutrition-form'] = {
                    'Which of these foods has the most iron? (Select one answer)': ironData
                };
                break;
            case 7: // Calcium selection
                const calciumData = collectSingleChoiceData('calcium-selection');
                stepData['nutrition-form'] = {
                    'Which of these foods has the most calcium? (Select one answer)': calciumData
                };
                break;
            case 8: // Fiber selection
                const fibreData = collectSingleChoiceData('fibre-selection');
                stepData['nutrition-form'] = {
                    'Which of these foods has the most fibre? (Select one answer)': fibreData
                };
                break;
            case 9: // Multiple choice questions
                const multipleChoiceData = collectMultipleChoiceData();
                // For step 9, store the multiple choice data directly
                stepData['nutrition-form'] = multipleChoiceData;
                break;
            default:
                console.log('No case matched for step:', stepNumber);
                return null;
        }

        return stepData;
    }

    // Collect radio button data for food selection steps
    function collectRadioData(prefix) {
        const answers = {};

        try {
            // Get all radio buttons for this prefix
            const radioButtons = document.querySelectorAll(`input[name^="${prefix}"]`);

            // Group radio buttons by food item
            const foodGroups = {};
            radioButtons.forEach(radio => {
                const name = radio.name;
                // Remove the prefix and the option suffix to get the food name
                const foodName = name.replace(`${prefix}`, '').replace('-high', '').replace('-low', '').replace('-unsure', '');

                if (!foodGroups[foodName]) {
                    foodGroups[foodName] = [];
                }
                foodGroups[foodName].push(radio);
            });

            // Process each food group
            Object.keys(foodGroups).forEach(foodName => {
                const radios = foodGroups[foodName];
                const selectedRadio = radios.find(radio => radio.checked);

                if (selectedRadio) {
                    const value = selectedRadio.value; // 'high', 'low', or 'unsure'
                    answers[foodName] = {
                        value: 1,
                        option: value.charAt(0).toUpperCase() + value.slice(1), // Capitalize first letter
                        correct: 0 // Will be determined by backend
                    };
                }
                // If no selection made, don't store anything for this food
            });

        } catch (error) {
            console.error('Error collecting radio data:', error);
        }

        return answers;
    }

    // Collect single choice data for radio button questions
    function collectSingleChoiceData(radioName) {
        const answers = {};

        try {
            // Get the selected radio button
            const selectedRadio = document.querySelector(`input[name="${radioName}"]:checked`);

            if (selectedRadio) {
                const selectedValue = selectedRadio.value;

                // Store only the selected option
                answers[selectedValue] = {
                    value: 1,
                    option: null,
                    correct: 0
                };
            }
            // If no selection, return empty object

        } catch (error) {
            console.error('Error collecting single choice data:', error);
        }

        return answers;
    }

    // Collect multiple choice data
    function collectMultipleChoiceData() {
        const answers = {};
        const questions = document.querySelectorAll('.question-container');

        questions.forEach((question, index) => {
            const questionText = question.querySelector('.question-header').textContent.trim();
            const selectedRadio = question.querySelector('input[type="radio"]:checked');

            if (selectedRadio) {
                answers[questionText] = {
                    value: 1,
                    option: selectedRadio.value,
                    correct: 0
                };
            }
            // If no answer selected, don't store anything for this question
        });

        return answers;
    }

    // Validate step data
    function validateStepData(stepNumber, stepData) {
        // Skip validation for step 1 (welcome screen)
        if (stepNumber === 1) {
            return true;
        }

        // Extract the actual answers from the nested structure
        let answers = {};
        if (stepData && stepData['nutrition-form']) {
            if (stepNumber === 9) {
                // For step 9 (multiple choice), the data is stored directly
                answers = stepData['nutrition-form'];
            } else {
                // For food selection, extract from the question key
                const questionKey = Object.keys(stepData['nutrition-form'])[0];
                answers = stepData['nutrition-form'][questionKey];
            }
        } else {
            console.error('No nutrition-form data found in stepData');
            return false;
        }

        // For food selection steps (2-5), check if all foods have radio button selections
        if (stepNumber >= 2 && stepNumber <= 5) {
            // Get all food items from the DOM to validate against
            let prefix = '';
            if (stepNumber === 2) prefix = 'carb-';
            else if (stepNumber === 3) prefix = 'protein-';
            else if (stepNumber === 4) prefix = 'fat-';
            else if (stepNumber === 5) prefix = 'healthy-fat-';

            // Get all radio buttons for this prefix to find all food items
            const radioButtons = document.querySelectorAll(`input[name^="${prefix}"]`);
            const allFoodNames = new Set();

            radioButtons.forEach(radio => {
                const name = radio.name;
                // Remove the prefix and the option suffix to get the food name
                const foodName = name.replace(prefix, '').replace('-high', '').replace('-low', '').replace('-unsure', '');
                allFoodNames.add(foodName);
            });

            // Check if all food items have selections
            const unselectedFoods = [];
            allFoodNames.forEach(foodName => {
                if (!answers[foodName]) {
                    unselectedFoods.push(foodName);
                }
            });

            if (unselectedFoods.length > 0) {
                // Add error styling to unselected food items
                unselectedFoods.forEach(foodName => {
                    addErrorStylingToFoodItem(foodName, stepNumber);
                });
                return false;
            }

            // Remove error styling from all food items if validation passes
            allFoodNames.forEach(foodName => {
                removeErrorStylingFromFoodItem(foodName, stepNumber);
            });
        }

        // For single selection steps (6-8), check if exactly one item is selected
        if (stepNumber >= 6 && stepNumber <= 8) {
            const selectedOptions = Object.keys(answers);
            const hasSelection = selectedOptions.length === 1;

            // User must select exactly one option
            if (!hasSelection) {
                // Show error styling on all food items if none selected
                // Get all radio buttons for this step to show error on all options
                let radioName = '';
                if (stepNumber === 6) radioName = 'iron-selection';
                else if (stepNumber === 7) radioName = 'calcium-selection';
                else if (stepNumber === 8) radioName = 'fibre-selection';

                const allRadios = document.querySelectorAll(`input[name="${radioName}"]`);
                allRadios.forEach(radio => {
                    const optionName = radio.value;
                    addErrorStylingToFoodItem(optionName, stepNumber);
                });
                return false;
            }

            // Remove error styling from all food items if validation passes
            // Get all radio buttons for this step to remove error styling
            let radioName = '';
            if (stepNumber === 6) radioName = 'iron-selection';
            else if (stepNumber === 7) radioName = 'calcium-selection';
            else if (stepNumber === 8) radioName = 'fibre-selection';

            const allRadios = document.querySelectorAll(`input[name="${radioName}"]`);
            allRadios.forEach(radio => {
                const optionName = radio.value;
                removeErrorStylingFromFoodItem(optionName, stepNumber);
            });
        }

        // For multiple choice questions (step 9), check if all questions are answered
        if (stepNumber === 9) {
            // Since we now only store answered questions, we need to check against all questions in the DOM
            const allQuestions = document.querySelectorAll('.question-container');
            const answeredQuestions = Object.keys(answers);

            if (answeredQuestions.length < allQuestions.length) {
                return false;
            }
        }

        return true;
    }

    // Save step data to backend
    function saveStepData(stepNumber, stepData) {
        return new Promise((resolve, reject) => {
            const currentQuizId = getCurrentQuizId();
            if (!currentQuizId) {
                reject('No quiz ID available');
                return;
            }

            $.ajax({
                url: window.quizConfig.saveStepUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.quizConfig.csrfToken
                },
                data: {
                    quiz_id: currentQuizId,
                    step: stepNumber,
                    stepData: JSON.stringify(stepData)
                },
                success: function(response) {
                    if (response.success) {
                        resolve(response);
                    } else {
                        reject(response.message || 'Failed to save step');
                    }
                },
                error: function(xhr) {
                    reject('Error saving step. Please try again.');
                }
            });
        });
    }

    // Complete quiz function
    function completeQuiz(userId) {
        return new Promise((resolve, reject) => {
            const currentQuizId = getCurrentQuizId();
            if (!currentQuizId) {
                reject('No quiz ID available');
                return;
            }

            // Calculate nutrition score from stored answers
            const nutritionScore = calculateNutritionScore();

            // Calculate total answer counts
            const totalAnswerCounts = {
                'nutrition-form': nutritionScore,
            };

            $.ajax({
                url: window.quizConfig.completeUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.quizConfig.csrfToken
                },
                data: {
                    quiz_id: currentQuizId,
                    totalAnswerCounts: totalAnswerCounts,
                    user_id: userId
                },
                success: function(response) {
                    if (response.success) {

                        // Store completed quiz ID for signup/login process
                        storeCompletedQuizId(currentQuizId);

                        // Don't clear quiz state - keep answers for results screen
                        // clearQuizState(); // REMOVED - keep answers in quiz_state

                        resolve(response);
                    } else {
                        reject(response.message || 'Failed to complete quiz');
                    }
                },
                error: function(xhr) {
                    reject('Error completing quiz. Please try again.');
                }
            });
        });
    }

    // Calculate nutrition score from stored answers
    function calculateNutritionScore() {
        let totalScore = 0;

        // Get stored answers from sessionStorage
        const savedState = sessionStorage.getItem(QUIZ_STORAGE_KEY);
        if (!savedState) {
            console.log('No saved state found');
            return 0;
        }

        try {
            const state = JSON.parse(savedState);
            const savedAnswers = state.answers || {};

            // Process each step's answers
            for (let step = 2; step <= 9; step++) {
                const stepAnswers = savedAnswers[step];
                if (stepAnswers) {
                    const stepScore = calculateStepScore(step, stepAnswers);
                    totalScore += stepScore;
                }
            }
        } catch (e) {
            console.error('Error calculating nutrition score:', e);
            return 0;
        }

        return totalScore;
    }

    // Calculate score for a specific step
    function calculateStepScore(step, stepAnswers) {
        let stepScore = 0;

        // The answers are stored directly in stepAnswers, not nested under 'nutrition-form'
        let answers = stepAnswers;

        if (!answers) {
            console.log(`No answers found for step ${step}`);
            return 0;
        }

        // Process answers based on step type
        switch(step) {
            case 2: // Carbohydrate questions
            case 3: // Protein questions
            case 4: // Fat questions
            case 5: // Healthy fat questions
                stepScore = calculateRadioScore(answers, step);
                break;
            case 6: // Iron questions
            case 7: // Calcium questions
            case 8: // Fiber questions
                stepScore = calculateSingleChoiceScore(answers);
                break;
            case 9: // Multiple choice questions
                stepScore = calculateMultipleChoiceScore(answers);
                break;
        }

        return stepScore;
    }

    // Calculate score for radio button questions (High/Low/Unsure)
    function calculateRadioScore(answers, step) {
        let score = 0;

        // Define correct answers for each step specifically
        let correctAnswers = {};

        switch(step) {
            case 2: // Carbohydrate questions
                correctAnswers = {
                    'chicken': 'Low',
                    'bakedbeans': 'High',
                    'grainbread': 'High',
                    'avocado': 'Low',
                    'weetbix': 'High',
                    'fruityogurt': 'High',
                    'crumpets': 'High',
                    'cream': 'Low'
                };
                break;
            case 3: // Protein questions
                correctAnswers = {
                    'salmon': 'High',
                    'baked-beans': 'High',
                    'grapes': 'Low',
                    'hummus': 'Low',
                    'cornflakes-cereal': 'Low',
                    'almonds': 'High',
                    'flavoured-milk': 'High',
                    'ice-cream': 'Low',
                    'oat-milk': 'Low'
                };
                break;
            case 4: // Fat questions
                correctAnswers = {
                    'avocado': 'High',
                    'baked-beans': 'Low',
                    'cottage-cheese': 'Low',
                    'peanut-butter': 'High',
                    'crumpets': 'Low',
                    'tasty-cheese': 'High'
                };
                break;
            case 5: // Healthy fat questions
                correctAnswers = {
                    'butter': 'Low',
                    'oliveoil': 'High',
                    'milk': 'Low',
                    'chips': 'Low',
                    'salmon': 'High',
                    'chocolate': 'Low',
                    'macadamia': 'High'
                };
                break;
        }

        // Check each answer
        for (const [food, answerData] of Object.entries(answers)) {
            if (answerData && answerData.option && correctAnswers[food]) {
                if (answerData.option === correctAnswers[food]) {
                    score += 1;
                }
            }
        }

        return score;
    }

    // Calculate score for single choice questions
    function calculateSingleChoiceScore(answers) {
        let score = 0;

        // Define correct answers for single choice questions
        const correctAnswers = {
            // Step 6 - Iron
            'Grilled steak, 130g': true,

            // Step 7 - Calcium
            'Firm tofu, 100g': true,

            // Step 8 - Fiber
            'Raw oats, 1/2 cup': true
        };

        // Check each answer
        for (const [food, answerData] of Object.entries(answers)) {
            if (answerData && answerData.value === 1 && correctAnswers[food]) {
                score += 1;
            }
        }

        return score;
    }

    // Calculate score for multiple choice questions
    function calculateMultipleChoiceScore(answers) {
        let score = 0;

        // Define correct answers for multiple choice questions with complete questions
        const correctAnswers = {
            'Approximately how many decisions do we make every day about what we eat?': 'Over 200',
            'Which of the following is NOT a \'Macronutrient\'?': 'Iron'
        };

        // Check each answer
        for (const [question, answerData] of Object.entries(answers)) {
            if (answerData && answerData.option) {
                // Clean the question text to handle variations (remove newlines, extra spaces)
                const cleanQuestion = question.trim().replace(/\s+/g, ' ').replace(/\n/g, ' ');

                // Check if the cleaned question matches any of our correct answers
                for (const [correctQuestion, correctAnswer] of Object.entries(correctAnswers)) {
                    const cleanCorrectQuestion = correctQuestion.trim().replace(/\s+/g, ' ');

                    if (cleanQuestion === cleanCorrectQuestion) {
                        if (answerData.option === correctAnswer) {
                            score += 1;
                        }
                        break;
                    }
                }
            }
        }

        return score;
    }

    // Handle next button clicks with validation and saving
    nextButtons.forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const currentStepElement = this.closest('.quiz-step');
            const nextStepNumber = parseInt(this.getAttribute('data-next'));
            const currentStepNumber = parseInt(currentStepElement.getAttribute('data-step'));

            // Skip validation for step 1 (welcome screen)
            if (currentStepNumber > 1) {
                // Collect and validate step data
                const stepData = collectStepData(currentStepNumber);

                if (!validateStepData(currentStepNumber, stepData)) {
                    let errorMessage = 'Please select at least one option before proceeding.';

                    // Customize error message based on step type
                    if (currentStepNumber >= 2 && currentStepNumber <= 5) {
                        // Steps 2-5: High/Low/Unsure radio buttons for each food
                        errorMessage = 'Please select High, Low, or Unsure for each food item before proceeding.';
                    } else if (currentStepNumber >= 6 && currentStepNumber <= 8) {
                        // Steps 6-8: Single choice questions (select one food or Unsure)
                        errorMessage = 'Please select one food item or choose "Unsure" before proceeding.';
                    } else if (currentStepNumber === 9) {
                        // Step 9: Multiple choice questions
                        errorMessage = 'Please answer all questions before proceeding.';
                    }

                    showErrorMessage(errorMessage);
                    addErrorStyling(); // Add error styling
                    return;
                }

                // Save step data
                try {
                    await saveStepData(currentStepNumber, stepData);
                    // Also save answers to session storage for restoration
                    // Extract the actual answers from the nested structure
                    let answers;
                    if (currentStepNumber === 9) {
                        // For step 9 (multiple choice), the data is stored directly
                        answers = stepData['nutrition-form'];
                    } else {
                        // For other steps, extract from the question key
                        answers = stepData['nutrition-form'] ?
                            Object.values(stepData['nutrition-form'])[0] :
                            stepData['nutrition-form'];
                    }
                    saveStepAnswers(currentStepNumber, answers);
                } catch (error) {
                    alert(error);
                    return;
                }

                // If this is the final step (step 9), complete the quiz
                if (currentStepNumber === 9) {
                    try {
                        // You can pass user ID here if available, or use null for anonymous users
                        await completeQuiz(null);
                        // clearQuizState(); // Clear state on completion - REMOVED
                        // clearCurrentQuizId(); // Clear quiz ID on completion - REMOVED

                        // Show results/completion screen instead of next step
                        setTimeout(() => {
                            // showStep(10); // Assuming step 10 is the results screen
                            $('#quizModal').modal('hide');
                            openSingupFreePopup(false, true);
                            removeErrorStyling(); // Remove error styling after successful navigation
                        }, 100);
                        return; // Don't continue to the next step logic
                    } catch (error) {
                        // Continue to results page even if completion fails
                        setTimeout(() => {
                            //showStep(10); // Show results screen even if completion fails
                            // show error msg
                            removeErrorStyling();
                        }, 100);
                        return;
                    }
                }
            }

            // Navigate to next step (only if not completing the quiz)
            // Add a small delay to ensure saveStepAnswers completes
            setTimeout(() => {
                showStep(nextStepNumber);
                removeErrorStyling(); // Remove error styling after successful navigation
            }, 100);
        });
    });

    // Handle back button clicks
    backButtons.forEach(button => {
        button.addEventListener('click', function () {
            const prevStepNumber = this.getAttribute('data-prev');
            showStep(parseInt(prevStepNumber));
            removeErrorStyling(); // Remove error styling on back button click
        });
    });

    // Handle food container clicks (for better UX) - only for steps 6-8 (iron, calcium, fibre selection)
    foodContainers.forEach(container => {
        container.addEventListener('click', function (e) {
            // Don't trigger if clicking directly on the radio button
            if (e.target.type === 'radio') return;

            // Only apply this behavior for steps 6-8 (iron, calcium, fibre selection)
            const currentStep = this.closest('.quiz-step');
            if (currentStep) {
                const currentStepNumber = parseInt(currentStep.getAttribute('data-step'));
                // Only allow container clicks for steps 6, 7, 8 (iron, calcium, fibre selection)
                if (currentStepNumber >= 6 && currentStepNumber <= 8) {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change'));
                    }
                }
            }
        });
    });

    // Handle unsure radio button clicks
    document.querySelectorAll('.unsure-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked) {
                // Uncheck all food radio buttons in the same step
                const currentStep = this.closest('.quiz-step');
                const foodRadios = currentStep.querySelectorAll('input[type="radio"]:not(.unsure-radio)');
                foodRadios.forEach(foodRadio => {
                    foodRadio.checked = false;
                });
            }
        });
    });

    // Handle food radio button clicks to uncheck unsure radio
    document.querySelectorAll('#quizModal input[type="radio"]:not(.unsure-radio)').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked) {
                // Uncheck unsure radio in the same step
                const currentStep = this.closest('.quiz-step');
                const unsureRadio = currentStep.querySelector('.unsure-radio');
                if (unsureRadio) {
                    unsureRadio.checked = false;
                }
            }
        });
    });

    // Add event listeners to remove error styling when user starts selecting
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            removeErrorStyling();
            hideErrorMessage();

            // Save current step answers whenever user makes a selection
            const currentStep = this.closest('.quiz-step');
            if (currentStep) {
                const currentStepNumber = parseInt(currentStep.getAttribute('data-step'));
                if (currentStepNumber >= 2 && currentStepNumber <= 9) {
                    const stepData = collectStepData(currentStepNumber);
                    if (stepData) {
                        // Extract the actual answers from the nested structure
                        let answers;
                        if (currentStepNumber === 9) {
                            // For step 9 (multiple choice), the data is stored directly
                            answers = stepData['nutrition-form'];
                        } else {
                            // For other steps, extract from the question key
                            answers = stepData['nutrition-form'] ?
                                Object.values(stepData['nutrition-form'])[0] :
                                stepData['nutrition-form'];
                        }
                        saveStepAnswers(currentStepNumber, answers);
                    }
                }
            }
        });
    });

    // Add event listeners for unsure radio buttons
    const unsureRadios = document.querySelectorAll('.unsure-radio');
    unsureRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            removeErrorStyling();
            hideErrorMessage();

            // Save current step answers whenever user makes a selection
            const currentStep = this.closest('.quiz-step');
            if (currentStep) {
                const currentStepNumber = parseInt(currentStep.getAttribute('data-step'));
                if (currentStepNumber >= 2 && currentStepNumber <= 9) {
                    const stepData = collectStepData(currentStepNumber);
                    if (stepData) {
                        // Extract the actual answers from the nested structure
                        let answers;
                        if (currentStepNumber === 9) {
                            // For step 9 (multiple choice), the data is stored directly
                            answers = stepData['nutrition-form'];
                        } else {
                            // For other steps, extract from the question key
                            answers = stepData['nutrition-form'] ?
                                Object.values(stepData['nutrition-form'])[0] :
                                stepData['nutrition-form'];
                        }
                        saveStepAnswers(currentStepNumber, answers);
                    }
                }
            }
        });
    });

    // Add event listeners for multiple choice questions
    const multipleChoiceRadios = document.querySelectorAll('input[type="radio"]');
    multipleChoiceRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            removeErrorStyling();
            hideErrorMessage();

            // Handle radio button changes for food selection steps (2-5)
            const currentStep = this.closest('.quiz-step');
            if (currentStep) {
                const currentStepNumber = parseInt(currentStep.getAttribute('data-step'));

                // Only handle radio button changes for steps 2-5 (food selection steps)
                if (currentStepNumber >= 2 && currentStepNumber <= 5) {
                    // Find the food name from the radio button name
                    const radioName = this.name;
                    const foodName = radioName.replace(/^(carb|protein|fat)-/, '').replace(/-high$|-low$|-unsure$/, '');

                    // Remove error styling from this food item
                    removeErrorStylingFromFoodItem(foodName, currentStepNumber - 1);
                }

                // Save current step answers whenever user makes a selection
                if (currentStepNumber >= 2 && currentStepNumber <= 9) {
                    const stepData = collectStepData(currentStepNumber);
                    if (stepData) {
                        // Extract the actual answers from the nested structure
                        let answers;
                        if (currentStepNumber === 9) {
                            // For step 9 (multiple choice), the data is stored directly
                            answers = stepData['nutrition-form'];
                        } else {
                            // For other steps, extract from the question key
                            answers = stepData['nutrition-form'] ?
                                Object.values(stepData['nutrition-form'])[0] :
                                stepData['nutrition-form'];
                        }
                        saveStepAnswers(currentStepNumber, answers);
                    } else {
                        console.warn(`No step data collected for step ${currentStepNumber}`);
                    }
                }
            }
        });
    });

    // Reset quiz when modal is closed
    const quizModal = document.getElementById('quizModal');
    if (quizModal) {
        // Handle modal opening - check for existing quiz state
        quizModal.addEventListener('show.bs.modal', function () {
            // Try to load existing quiz state
            const hasExistingState = loadQuizState();

            if (!hasExistingState) {
                // No existing state, reset to first step
                resetQuizToFirstStep();
            } else {
                // We have existing state, check if quiz is completed
                const savedState = sessionStorage.getItem(QUIZ_STORAGE_KEY);
                if (savedState) {
                    try {
                        const state = JSON.parse(savedState);
                        const savedAnswers = state.answers || {};
                        const answeredSteps = Object.keys(savedAnswers).map(Number).filter(step => step > 0);

                        // Check if quiz is completed (has step 9 answers)
                        if (answeredSteps.includes(9)) {
                            // Quiz is completed, show results screen
                            //showStep(10);
                            // $('#quizModal').modal('hide');
                            // openSingupFreePopup(false, true);
                            // Restore all answers for the results screen
                            setTimeout(() => {
                                restoreQuizAnswers();
                            }, 100);
                        } else {
                            // Quiz is not completed, show step 1 with continue indicator
                            showStep(1);
                            // Only restore answers if we actually have saved answers
                            const hasAnswers = Object.keys(savedAnswers).length > 0;
                            if (hasAnswers) {
                                // Restore all previous answers after a short delay to ensure DOM is ready
                                setTimeout(() => {
                                    restoreQuizAnswers();
                                }, 100);
                            }
                        }
                    } catch (e) {
                        console.error('Error checking for saved answers:', e);
                        // Fallback to step 1
                        showStep(1);
                    }
                } else {
                    // No saved state, show step 1
                    showStep(1);
                }
            }
        });

        // Handle modal closing
        quizModal.addEventListener('hidden.bs.modal', function () {
            // Don't clear state on modal close - let user continue
            // Only clear if they explicitly want to start over
        });
    }

    // Function to reset quiz to first step
    function resetQuizToFirstStep() {
        // Reset to first step
        const allSteps = document.querySelectorAll('.quiz-step');
        allSteps.forEach((step, index) => {
            if (step.getAttribute('data-step') === '1') {
                return;
            }
            if (index === 0) {
                step.style.display = 'block';
            } else {
                step.style.display = 'none';
            }
        });

        // Show image section for first step and reset images
        const imageSection = document.getElementById('quiz-image-section');
        const mainImage = document.getElementById('quiz-main-image');
        const signupImage = document.getElementById('quiz-signup-image');

        if (imageSection) imageSection.style.display = 'block';
        if (mainImage) mainImage.style.display = 'block';
        if (signupImage) signupImage.style.display = 'none';

        // Clear all form selections
        clearAllFormSelections();

        // Reset global variables
        currentStep = 1;
        clearQuizState(); // Clear session storage on reset
        clearCurrentQuizId(); // Clear quiz ID on reset
        clearCompletedQuizId(); // Clear completed quiz ID on reset

        // Hide the continue quiz indicator
        const indicator = document.getElementById('continue-quiz-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }

        // Clear error styling
        hideErrorMessage();
        removeErrorStyling();
    }

    // Function to clear all form selections
    function clearAllFormSelections() {
        // Remove selected styling
        foodContainers.forEach(container => {
            container.classList.remove('selected');
        });

        // Uncheck all radio buttons (including unsure radios)
        const allRadios = document.querySelectorAll('input[type="radio"]');
        allRadios.forEach(radio => {
            radio.checked = false;
        });
    }

    // Load quiz state on page load
    loadQuizState();

    // Show error message
    function showErrorMessage(message) {
        // Remove any existing error message
        hideErrorMessage();

        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.id = 'quiz-error-message';
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 14px;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 400px;
            text-align: center;
        `;
        errorDiv.innerHTML = `<strong>⚠️</strong> ${message}`;

        // Add to body
        document.body.appendChild(errorDiv);

        // Auto-hide after 4 seconds
        setTimeout(() => {
            hideErrorMessage();
        }, 4000);
    }

    // Hide error message
    function hideErrorMessage() {
        const existingError = document.getElementById('quiz-error-message');
        if (existingError) {
            existingError.remove();
        }
    }

    // Add error styling to food items
    function addErrorStyling() {
        const currentStepElement = document.querySelector('.quiz-step[style*="display: block"]');
        if (currentStepElement) {
            const currentStepNumber = parseInt(currentStepElement.getAttribute('data-step'));

            if (currentStepNumber === 7) {
                // For step 7 (multiple choice), highlight unanswered questions
                const questionContainers = currentStepElement.querySelectorAll('.question-container');
                questionContainers.forEach(container => {
                    const selectedRadio = container.querySelector('input[type="radio"]:checked');
                    if (!selectedRadio) {
                        container.style.border = '2px solid #dc3545';
                        container.style.borderRadius = '8px';
                        container.style.padding = '12px';
                        container.style.backgroundColor = 'rgba(220, 53, 69, 0.1)';
                    }
                });
            } else {
                // For food selection steps (2-6), highlight food containers
                const foodContainers = currentStepElement.querySelectorAll('.food-image-container');
                foodContainers.forEach(container => {
                    container.style.border = '2px solid #dc3545';
                    container.style.borderRadius = '8px';
                    container.style.boxShadow = '0 0 5px rgba(220, 53, 69, 0.3)';
                });

                // Also highlight unsure option
                const unsureOption = currentStepElement.querySelector('.unsure-option');
                if (unsureOption) {
                    unsureOption.style.border = '2px solid #dc3545';
                    unsureOption.style.borderRadius = '4px';
                    unsureOption.style.padding = '8px';
                    unsureOption.style.backgroundColor = 'rgba(220, 53, 69, 0.1)';
                }
            }
        }
    }

    // Add error styling to a specific food item
    function addErrorStylingToFoodItem(foodName, stepNumber) {
        const currentStep = document.querySelector(`#step-${stepNumber + 1}`);
        if (!currentStep) return;

        // Find the food item container by looking for the food label text
        const foodLabels = currentStep.querySelectorAll('.food-label');
        let targetFoodContainer = null;

        foodLabels.forEach(label => {
            if (label.textContent.trim().toLowerCase() === foodName.toLowerCase()) {
                targetFoodContainer = label.closest('.food-item');
            }
        });

        if (targetFoodContainer) {
            // Add error class to the food item container
            targetFoodContainer.classList.add('error');

            // Add visual error styling
            targetFoodContainer.style.border = '2px solid #dc3545';
            targetFoodContainer.style.borderRadius = '8px';
            targetFoodContainer.style.boxShadow = '0 0 5px rgba(220, 53, 69, 0.3)';
            targetFoodContainer.style.backgroundColor = 'rgba(220, 53, 69, 0.05)';

            // Add error message if it doesn't exist
            if (!targetFoodContainer.querySelector('.food-error-message')) {
                const errorMessage = document.createElement('div');
                errorMessage.className = 'food-error-message';

                // Customize error message based on step type
                if (stepNumber >= 2 && stepNumber <= 4) {
                    errorMessage.textContent = 'Please select High, Low, or Unsure';
                } else if (stepNumber >= 5 && stepNumber <= 8) {
                    errorMessage.textContent = 'Please select this food or choose Unsure';
                } else {
                    errorMessage.textContent = 'Please select an option';
                }

                errorMessage.style.cssText = 'color: #dc3545; font-size: 12px; margin-top: 5px; text-align: center; font-weight: 500;';
                targetFoodContainer.appendChild(errorMessage);
            }
        }
    }

    // Remove error styling from a specific food item
    function removeErrorStylingFromFoodItem(foodName, stepNumber) {
        const currentStep = document.querySelector(`#step-${stepNumber + 1}`);
        if (!currentStep) return;

        // Find the food item container by looking for the food label text
        const foodLabels = currentStep.querySelectorAll('.food-label');
        let targetFoodContainer = null;

        foodLabels.forEach(label => {
            if (label.textContent.trim().toLowerCase() === foodName.toLowerCase()) {
                targetFoodContainer = label.closest('.food-item');
            }
        });

        if (targetFoodContainer) {
            // Remove error class from the food item container
            targetFoodContainer.classList.remove('error');

            // Remove visual error styling
            targetFoodContainer.style.border = '';
            targetFoodContainer.style.borderRadius = '';
            targetFoodContainer.style.boxShadow = '';
            targetFoodContainer.style.backgroundColor = '';

            // Remove error message if it exists
            const errorMessage = targetFoodContainer.querySelector('.food-error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
        }
    }

    // Remove error styling from food items
    function removeErrorStyling() {
        const allFoodContainers = document.querySelectorAll('.food-image-container');
        allFoodContainers.forEach(container => {
            container.style.border = '';
            container.style.borderRadius = '';
            container.style.boxShadow = '';
        });

        const allUnsureOptions = document.querySelectorAll('.unsure-option');
        allUnsureOptions.forEach(option => {
            option.style.border = '';
            option.style.borderRadius = '';
            option.style.padding = '';
            option.style.backgroundColor = '';
        });

        // Also remove error styling from step 7 question containers
        const allQuestionContainers = document.querySelectorAll('.question-container');
        allQuestionContainers.forEach(container => {
            container.style.border = '';
            container.style.borderRadius = '';
            container.style.padding = '';
            container.style.backgroundColor = '';
        });

        // Remove individual food item error styling
        const allFoodItems = document.querySelectorAll('.food-item');
        allFoodItems.forEach(item => {
            item.classList.remove('error');
            const errorMessage = item.querySelector('.food-error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
        });
    }

    // Global functions for external access (simple quiz ID access)
    window.QuizManager = {
        // Get completed quiz ID for any purpose
        getCompletedQuizId: function() {
            return getCompletedQuizId();
        },

        // Check if user has completed quiz
        hasCompletedQuiz: function() {
            return hasCompletedQuiz();
        },

        // Clear completed quiz ID when needed
        clearCompletedQuiz: function() {
            clearCompletedQuizId();
        },

        // Get current session ID for debugging
        getSessionId: function() {
            return getSessionId();
        },

        // Get current quiz ID (if still in progress)
        getCurrentQuizId: function() {
            return getCurrentQuizId();
        },

        // Get quiz state
        getQuizState: function() {
            return getQuizState();
        }
    };
});