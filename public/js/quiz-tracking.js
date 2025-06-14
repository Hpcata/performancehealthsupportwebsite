// Quiz tracking functionality
$(document).ready(function() {
    // Track free quiz button clicks
    $('#takeFreeTest').on('click', function () {
        $.ajax({
            url: "/track-quiz-click",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Quiz click tracked');
            }
        });
    });

    // Track quiz progress
    function collectStepData(currentStep) {
        const form = document.querySelector(`#div${currentStep}`);
        if (!form) return {};

        const stepData = JSON.parse(localStorage.getItem("testStepsData")) || {};
        const formClass = Array.from(form.classList).find(cls => cls.endsWith('-form'));

        if (!formClass) {
            console.error('Form class not found for step:', currentStep);
            return {};
        }

        // Track progress
        $.ajax({
            url: "/track-quiz-progress",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                stepData: stepData,
                currentStep: currentStep
            },
            success: function(response) {
                console.log('Quiz progress tracked');
            }
        });

        return stepData;
    }

    // Track quiz completion
    function sendQuestionnaireData(user_id) {
        const stepsData = JSON.parse(localStorage.getItem("testStepsData"));
        const totalAnswerCounts = JSON.parse(localStorage.getItem("totalAnswerCounts"));
        
        if (!stepsData || !totalAnswerCounts) {
            console.error("No questionnaire data found!");
            return;
        }

        // Track completion
        $.ajax({
            url: "/track-quiz-completion",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                userId: user_id,
                email: $('#email').val()
            },
            success: function(response) {
                console.log('Quiz completion tracked');
            }
        });

        // Continue with existing questionnaire submission
        fetch('/submit-free-test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                userId: user_id,
                testData: stepsData,
                totalAnswerCount: totalAnswerCounts
            })
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                console.log("Questionnaire Data Saved Successfully:", response);
                $('#div9').css('display', 'block');
                $('#div8').css('display', 'none');
                $('#step-9').addClass('active');
                alert(response.message);
                updateMeterArrows('nutrition-form');
                localStorage.removeItem("testStepsData");
                localStorage.removeItem("totalAnswerCounts");
            } else {
                alert(response.message);
                console.error("Failed to Save Questionnaire Data:", response.message);
                localStorage.removeItem("testStepsData");
                localStorage.removeItem("totalAnswerCounts");
            }
        })
        .catch(error => console.error("Error Saving Questionnaire Data:", error));
    }
}); 