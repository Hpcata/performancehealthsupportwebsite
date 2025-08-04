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
        const quizId = getCompletedQuizId();
        sessionStorage.removeItem('completed_quiz_id');
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

                // Show the current step
                showStep(currentStep);

                // Show continue quiz indicator if we're on step 1
                if (currentStep === 1) {
                    const indicator = document.getElementById('continue-quiz-indicator');
                    if (indicator) {
                        indicator.style.display = 'block';
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

    // Save quiz state to session storage
    function saveQuizState() {
        const state = {
            quizId: getCurrentQuizId(),
            currentStep: currentStep,
            timestamp: Date.now()
        };
        sessionStorage.setItem(QUIZ_STORAGE_KEY, JSON.stringify(state));
    }

    // Clear quiz state from session storage
    function clearQuizState() {
        sessionStorage.removeItem(QUIZ_STORAGE_KEY);
    }

    // Show specific step
    function showStep(stepNumber) {
        const allSteps = document.querySelectorAll('.quiz-step');
        const imageSection = document.getElementById('quiz-image-section');

        allSteps.forEach((step, index) => {
            if (parseInt(step.getAttribute('data-step')) === stepNumber) {
                step.style.display = 'block';
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
    }

    // Quiz step navigation functionality
    const nextButtons = document.querySelectorAll('.next-step-btn');
    const backButtons = document.querySelectorAll('.back-step-btn');
    const foodCheckboxes = document.querySelectorAll('.food-checkbox');
    const foodContainers = document.querySelectorAll('.food-image-container');
    const startQuizBtn = document.getElementById('start-quiz-btn');

    // Start quiz functionality
    if (startQuizBtn) {
        startQuizBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Check if there's an existing quiz in progress
            const currentQuizId = getCurrentQuizId();

            if (currentQuizId) {
                // Continue existing quiz
                showStep(2);
                return;
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

    // Collect step data based on step type
    function collectStepData(stepNumber) {
        const stepData = {};

        switch(stepNumber) {
            case 2: // Carbohydrate selection
                stepData['nutrition-form'] = {
                    'Select the foods that are high in carbohydrate.': collectCheckboxData('carb-')
                };
                break;
            case 3: // Protein selection
                stepData['nutrition-form'] = {
                    'Select the foods that are high in protein.': collectCheckboxData('protein-')
                };
                break;
            case 4: // Fat selection
                stepData['nutrition-form'] = {
                    'Select the foods that are high in fat.': collectCheckboxData('fat-')
                };
                break;
            case 5: // healthy fat selection (step 4)
                stepData['nutrition-form'] = {
                    'Select the foods that are high in healthy fats.': collectCheckboxData('healthy-fat-')
                };
                break;
            case 6: // iron selection (step 5)
                stepData['nutrition-form'] = {
                    'Which one of these foods has the most iron?': collectCheckboxData('iron-')
                };
                break;
            case 7: // Multiple choice questions
                const multipleChoiceData = collectMultipleChoiceData();
                // Convert to proper format for step 7
                stepData['nutrition-form'] = {};
                Object.keys(multipleChoiceData).forEach((questionText, index) => {
                    stepData['nutrition-form'][questionText] = multipleChoiceData[questionText];
                });
                break;
            default:
                return null;
        }

        return stepData;
    }

    // Collect checkbox data for food selection steps
    function collectCheckboxData(prefix) {
        const answers = {};

        try {
            const checkboxes = document.querySelectorAll(`input[id^="${prefix}"]`);
            const unsureRadio = document.querySelector(`input[id="${prefix}unsure"]`);

            // Check if unsure is selected
            const isUnsureSelected = unsureRadio && unsureRadio.checked;

            if (isUnsureSelected) {
                // If unsure is selected, mark all foods as unselected with "Unsure" option
                checkboxes.forEach(checkbox => {
                    const label = getLabelText(checkbox);
                    if (label) {
                        answers[label] = {
                            value: 0,
                            option: "Unsure",
                            correct: 0
                        };
                    }
                });

                // Add unsure option
                answers['unsure'] = {
                    value: 1,
                    option: 'unsure',
                    correct: 0
                };
            } else {
                // If unsure is not selected, process food selections
                checkboxes.forEach(checkbox => {
                    const label = getLabelText(checkbox);
                    if (label) {
                        const isChecked = checkbox.checked;

                        // Use the food name as the key instead of checkbox ID
                        answers[label] = {
                            value: isChecked ? 1 : 0,
                            option: isChecked ? "High" : "Unsure",
                            correct: 0 // Will be determined by backend
                        };
                    }
                });

                // Add unsure option as unselected
                answers['unsure'] = {
                    value: 0,
                    option: 'unsure',
                    correct: 0
                };
            }

            // Additional validation check
            const selectedFoods = Object.keys(answers).filter(key => key !== 'unsure' && answers[key].value === 1);
            const unsureSelected = answers['unsure'] && answers['unsure'].value === 1;

        } catch (error) {
            // Return empty answers object if there's an error
            return {};
        }

        return answers;
    }

    // Helper function to safely get label text
    function getLabelText(checkbox) {
        try {
            // Method 1: Try to find label by for attribute
            const label = document.querySelector(`label[for="${checkbox.id}"]`);
            if (label && label.textContent) {
                return label.textContent.trim();
            }

            // Method 2: Try next sibling
            if (checkbox.nextElementSibling && checkbox.nextElementSibling.textContent) {
                return checkbox.nextElementSibling.textContent.trim();
            }

            // Method 3: Try to find label within parent container
            const parentContainer = checkbox.closest('.food-image-container');
            if (parentContainer) {
                const labelElement = parentContainer.querySelector('.food-label');
                if (labelElement && labelElement.textContent) {
                    return labelElement.textContent.trim();
                }
            }

            // Method 4: Try to find any label in the same container
            const container = checkbox.parentElement;
            if (container) {
                const labelElement = container.querySelector('label');
                if (labelElement && labelElement.textContent) {
                    return labelElement.textContent.trim();
                }
            }

            // Fallback: use checkbox ID as label (remove common prefixes)
            console.warn(`Could not find label for checkbox ${checkbox.id}, using ID as fallback`);
            const fallbackLabel = checkbox.id
                .replace(/^(carb|protein|fat)-/, '') // Remove common prefixes
                .replace(/-/g, ' ') // Replace hyphens with spaces
                .replace(/\b\w/g, l => l.toUpperCase()); // Capitalize first letter of each word
            return fallbackLabel;
        } catch (error) {
            console.error('Error getting label text for checkbox:', checkbox.id, error);
            // Return a safe fallback
            return checkbox.id || 'Unknown';
        }
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
            } else {
                // If no answer selected, mark as unsure
                answers[questionText] = {
                    value: 1,
                    option: 'unsure',
                    correct: 0
                };
            }
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
            if (stepNumber === 7) {
                // For multiple choice, the data is flat
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

        // For food selection steps (2-6), check if any food is selected or unsure is selected
        if (stepNumber >= 2 && stepNumber <= 6) {
            // Check if any food item has value === 1 (selected)
            const foodItems = Object.keys(answers).filter(key => key !== 'unsure');
            const selectedFoodItems = foodItems.filter(food => answers[food] && answers[food].value === 1);
            const hasSelectedFood = selectedFoodItems.length > 0;
            const hasUnsureSelected = answers['unsure'] && answers['unsure'].value === 1;

            if (!hasSelectedFood && !hasUnsureSelected) {
                return false;
            }
        }

        // For multiple choice questions (step 7), check if all questions are answered
        if (stepNumber === 7) {
            const questionCount = Object.keys(answers).length;
            const answeredCount = Object.values(answers).filter(answer =>
                answer.value === 1 && answer.option !== 'unsure'
            ).length;

            if (answeredCount < questionCount) {
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

            // Calculate total answer counts (you can modify this based on your needs)
            const totalAnswerCounts = {
                'nutrition-form': 0,
                'sports-form': 0,
                'supplement-form': 0
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

                        // Clear current quiz state but keep completed quiz ID
                        clearQuizState();

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

    // Abandon quiz function
    function abandonQuiz() {
        return new Promise((resolve, reject) => {
            const currentQuizId = getCurrentQuizId();
            if (!currentQuizId) {
                reject('No quiz ID available');
                return;
            }

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
                        resolve(response);
                    } else {
                        reject(response.message || 'Failed to abandon quiz');
                    }
                },
                error: function(xhr) {
                    reject('Error abandoning quiz. Please try again.');
                }
            });
        });
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
                    if (currentStepNumber >= 2 && currentStepNumber <= 6) {
                        errorMessage = 'Please select any food items or choose "Unsure" before proceeding.';
                    } else if (currentStepNumber === 7) {
                        errorMessage = 'Please answer all questions before proceeding.';
                    }

                    showErrorMessage(errorMessage);
                    addErrorStyling(); // Add error styling
                    return;
                }

                // Save step data
                try {
                    await saveStepData(currentStepNumber, stepData);
                } catch (error) {
                    alert(error);
                    return;
                }

                // If this is the final step (step 7), complete the quiz
                if (currentStepNumber === 7) {
                    try {
                        // You can pass user ID here if available, or use null for anonymous users
                        await completeQuiz(null);
                        // clearQuizState(); // Clear state on completion - REMOVED
                        // clearCurrentQuizId(); // Clear quiz ID on completion - REMOVED
                    } catch (error) {
                        // Continue to results page even if completion fails
                    }
                }
            }

            // Navigate to next step
            showStep(nextStepNumber);
            removeErrorStyling(); // Remove error styling after successful navigation
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

    // Handle food item selection
    foodCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const container = this.closest('.food-image-container');
            if (this.checked) {
                container.classList.add('selected');
            } else {
                container.classList.remove('selected');
            }
        });
    });

    // Handle food container clicks (for better UX)
    foodContainers.forEach(container => {
        container.addEventListener('click', function (e) {
            // Don't trigger if clicking directly on the checkbox
            if (e.target.type === 'checkbox') return;

            const checkbox = this.querySelector('.food-checkbox');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });

    // Handle unsure radio button clicks
    document.querySelectorAll('.unsure-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked) {
                // Uncheck all food checkboxes in the same step
                const currentStep = this.closest('.quiz-step');
                const checkboxes = currentStep.querySelectorAll('.food-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    const container = checkbox.closest('.food-image-container');
                    container.classList.remove('selected');
                });
            }
        });
    });

    // Handle food checkbox clicks to uncheck unsure radio
    foodCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
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
    foodCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            removeErrorStyling();
            hideErrorMessage();
        });
    });

    // Add event listeners for unsure radio buttons
    const unsureRadios = document.querySelectorAll('.unsure-radio');
    unsureRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            removeErrorStyling();
            hideErrorMessage();
        });
    });

    // Add event listeners for multiple choice questions
    const multipleChoiceRadios = document.querySelectorAll('input[type="radio"]');
    multipleChoiceRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            removeErrorStyling();
            hideErrorMessage();
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

        // Uncheck all checkboxes
        foodCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        // Remove selected styling
        foodContainers.forEach(container => {
            container.classList.remove('selected');
        });

        // Uncheck all radio buttons
        const unsureRadios = document.querySelectorAll('.unsure-radio');
        unsureRadios.forEach(radio => {
            radio.checked = false;
        });

        // Reset global variables
        currentStep = 1;
        clearQuizState(); // Clear session storage on reset
        clearCurrentQuizId(); // Clear quiz ID on reset
        clearCompletedQuizId(); // Clear completed quiz ID on reset
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
        }
    };
});