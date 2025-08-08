// Global functions
function validateCountryCode() {
    const countrySelect = document.getElementById("selected-code");

    // Handle case where countrySelect might be a string
    let selectedValue = "+61"; // Default

    if (countrySelect) {
        if (typeof countrySelect === "string") {
            // If it's already a string, use it directly
            selectedValue = countrySelect;
        } else if (countrySelect.textContent) {
            // If it's a DOM element (span), get its text content
            selectedValue = countrySelect.textContent.trim();
        } else if (countrySelect.value) {
            // If it's a DOM element with value property, get its value
            selectedValue = countrySelect.value;
        }
    }

    // Check if a valid country code is selected
    return (
        selectedValue &&
        selectedValue !== "" &&
        selectedValue !== "undefined" &&
        selectedValue.startsWith("+")
    );
}

// Step 1: Send OTP
function sendOtp() {
    const mobileInput = document.getElementById("mobile_number");
    const countryCodeSelect = document.getElementById("selected-code");
    const mobileInputValue = mobileInput.value.trim();

    // Get country code value - handle both element and string cases
    let countryCode = "+61"; // Default fallback

    if (countryCodeSelect) {
        if (typeof countryCodeSelect === "string") {
            // If it's already a string, use it directly
            countryCode = countryCodeSelect;
        } else if (countryCodeSelect.textContent) {
            // If it's a DOM element (span), get its text content
            countryCode = countryCodeSelect.textContent.trim();
        } else if (countryCodeSelect.value) {
            // If it's a DOM element with value property, get its value
            countryCode = countryCodeSelect.value;
        }
    }

    // Clear any existing errors first
    clearErrors();

    // Validation
    if (!validateCountryCode()) {
        showError("Please select a country code", "selected-code");
        return;
    }

    if (!mobileInputValue) {
        showError("Please enter your mobile number", "mobile_number");
        return;
    }

    // Validate mobile number format (7-15 digits)
    const mobileRegex = /^\d{7,15}$/;
    if (!mobileRegex.test(mobileInputValue)) {
        showError(
            "Please enter a valid mobile number (7-15 digits)",
            "mobile_number"
        );
        return;
    }

    // Combine country code and mobile number
    const fullMobileNumber = countryCode + mobileInputValue;

    // Reset resend attempts counter for new OTP
    window.resendAttempts = 0;

    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = "Sending OTP...";
    button.disabled = true;

    // Make API call
    fetch(window.otpRoutes.sendOtp, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            mobile_number: fullMobileNumber,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                if (data.user_exists) {
                    // User exists - this will be a login flow
                    showSuccess(
                        "OTP sent successfully! Please verify to login."
                    );
                    show30SecondTimer();
                    window.mobileNumber = fullMobileNumber; // Store for later use
                    // change image
                    $("#signupModalathlete .quiz-h2-img").addClass("d-none");
                    $("#signupModalathlete .signup-login-h2-img").removeClass(
                        "d-none"
                    );
                    showStep(2);
                    window.isLoginFlow = true; // Set to login flow
                    document.getElementById("phone-number").textContent =
                        fullMobileNumber;
                } else {
                    // New user - this will be a registration flow
                    showSuccess("OTP sent successfully to " + fullMobileNumber);
                    window.mobileNumber = fullMobileNumber; // Store for later use
                    show30SecondTimer();
                    // change image
                    $("#signupModalathlete .quiz-h2-img").addClass("d-none");
                    $("#signupModalathlete .signup-login-h2-img").removeClass(
                        "d-none"
                    );
                    showStep(2);
                    window.isLoginFlow = false; // Set to registration flow
                    document.getElementById("phone-number").textContent =
                        fullMobileNumber;
                }

                // Start countdown for resend
                startResendCountdown();
            } else {
                // Handle specific error cases
                if (data.errors && data.errors.mobile_number) {
                    showError(data.errors.mobile_number[0], "mobile_number");
                } else if (data.message) {
                    showError(data.message);
                } else {
                    showError("Failed to send OTP. Please try again.");
                }
                console.error("Send OTP failed:", data);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showError(
                "Network error. Please check your connection and try again."
            );
        })
        .finally(() => {
            // Reset button
            button.textContent = originalText;
            button.disabled = false;
        });
}

// Step 2: Verify OTP
function verifyOtp() {
    const otpInputs = document.querySelectorAll(".otp-input");
    const otp = Array.from(otpInputs)
        .map((input) => input.value)
        .join("");

    // Clear any existing errors first
    clearErrors();

    // Validation
    if (!otp || otp.length !== 6) {
        showError("Please enter the complete 6-digit OTP", "otp-input-group");
        return;
    }

    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = "Verifying...";
    button.disabled = true;

    // Make API call
    fetch(window.otpRoutes.verifyOtp, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            mobile_number: window.mobileNumber,
            otp: otp,
            isFromQuizPopup: $("#isFromQuizPopup").val(),
            completed_quiz_id: sessionStorage.getItem("completed_quiz_id"),
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                if (data.action === "login") {
                    // User exists - login successful
                    showSuccess(
                        "Login successful! Redirecting to your profile..."
                    );
                    sessionStorage.removeItem("quiz_state");
                    // Redirect to profile landing page
                    setTimeout(() => {
                        if (data.redirectUrl) {
                            window.location.href = data.redirectUrl;
                        } else {
                            window.location.href = "/404";
                        }
                    }, 10);
                } else {
                    // User doesn't exist - proceed to registration
                    showSuccess(
                        "OTP verified successfully! Please complete your registration."
                    );
                    showStep(3);
                }
            } else {
                // Handle specific error cases
                if (data.errors && data.errors.otp) {
                    showError(data.errors.otp[0], "otp-input-group");
                } else if (data.message) {
                    showError(data.message, "otp-input-group");
                } else {
                    showError(
                        "Invalid OTP. Please try again.",
                        "otp-input-group"
                    );
                }
                console.error("OTP verification failed:", data);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showError(
                "Network error. Please check your connection and try again.",
                "otp-input-group"
            );
        })
        .finally(() => {
            // Reset button
            button.textContent = originalText;
            button.disabled = false;
        });
}

// Step 3: Complete Registration
function completeRegistration() {
    const firstNameInput = document.getElementById("firstname");
    const emailInput = document.getElementById("email");

    // Check if elements exist
    if (!firstNameInput) {
        console.error("First name input element not found");
        showError("Form error: First name field not found");
        return;
    }

    if (!emailInput) {
        console.error("Email input element not found");
        showError("Form error: Email field not found");
        return;
    }

    const firstName = firstNameInput.value.trim();
    const email = emailInput.value.trim();
    const userType = document.querySelector(
        'input[name="userType"]:checked'
    ).value;
    const ageGroup = document.querySelector(
        'input[name="ageGroup"]:checked'
    ).value;
    const sport = $("#sportstype option:selected").val();

    // Clear any existing errors first
    clearErrors();

    // Validation
    if (!firstName) {
        showError("Please enter your first name", "firstname");
        return;
    }

    if (!email) {
        showError("Please enter your email address", "email");
        return;
    }

    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError("Please enter a valid email address", "email");
        return;
    }

    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = "Creating Account...";
    button.disabled = true;

    // Make API call
    fetch(window.otpRoutes.registerWithOtp, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            mobile_number: window.mobileNumber,
            first_name: firstName,
            email: email,
            userType: userType,
            ageGroup: ageGroup,
            sport: sport,
            isFromQuizPopup: $("#isFromQuizPopup").val(),
            completed_quiz_id: sessionStorage.getItem("completed_quiz_id"),
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showSuccess(data.message);

                // Keep button disabled and change text to indicate success
                button.textContent =
                    data.action === "login"
                        ? "Login Successful!"
                        : "Registration Successful!";
                button.disabled = true;
                button.style.opacity = "0.6";
                button.style.cursor = "not-allowed";

                sessionStorage.removeItem("quiz_state");

                // Redirect to profile landing page using the user ID from response
                setTimeout(() => {
                    if (data.user && data.user.id) {
                        // Use the redirect URL from the API response
                        window.location.href = data.redirectUrl;
                    } else {
                        // Fallback to dashboard if user ID is not available
                        window.location.href = "/404";
                    }
                }, 1000);
            } else {
                // Handle validation errors
                if (data.errors) {
                    // Display field-specific errors
                    Object.keys(data.errors).forEach((field) => {
                        const errorMessage = data.errors[field][0];
                        showError(errorMessage, field);
                    });
                } else if (data.message) {
                    showError(data.message);
                } else {
                    showError("Registration failed. Please try again.");
                }
                console.error("Registration failed:", data);

                // Reset button on failure
                button.textContent = originalText;
                button.disabled = false;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showError(
                "Network error. Please check your connection and try again."
            );

            // Reset button on error
            button.textContent = originalText;
            button.disabled = false;
        });
}

function show30SecondTimer() {
    const resendTimerSpan = document.getElementById("resend-timer");
    const resendBtn = document.getElementById("resend-otp-link");
    let countdown = 30;
    // Show the resend timer and disable the resend button for 30 seconds
    if (resendTimerSpan && resendBtn) {
        // Check if maximum attempts reached
        if (window.resendAttempts >= MAX_RESEND_ATTEMPTS) {
            // Replace "Resend code in" text with limit message
            const resendTextElement = resendTimerSpan.parentElement;
            if (resendTextElement) {
                resendTextElement.innerHTML = "You have reached resend limit";
            }

            // Hide the resend link completely
            resendBtn.style.display = "none";
            return;
        }

        resendBtn.disabled = true;
        resendBtn.style.display = "none";
        resendTimerSpan.style.display = "inline";
        resendTimerSpan.textContent = ` 00:30`;
        let interval = setInterval(() => {
            countdown--;
            // Format countdown as MM:SS (e.g., 00:30)
            const minutes = String(Math.floor(countdown / 60)).padStart(2, "0");
            const seconds = String(countdown % 60).padStart(2, "0");
            resendTimerSpan.textContent = ` ${minutes}:${seconds}`;
            if (countdown <= 0) {
                clearInterval(interval);
                if (window.resendAttempts >= MAX_RESEND_ATTEMPTS) {
                    // Replace "Resend code in" text with limit message
                    const resendTextElement = resendTimerSpan.parentElement;
                    if (resendTextElement) {
                        resendTextElement.innerHTML =
                            "You have reached resend limit";
                    }

                    // Hide the resend link completely
                    resendBtn.style.display = "none";
                } else {
                    resendBtn.disabled = false;
                    resendBtn.style.display = "inline";
                }
                resendTimerSpan.textContent = ` 00:30`;
                resendTimerSpan.style.display = "none";
            } else {
                // resendTimerSpan.textContent = ` 00:30`;
                // resendBtn.style.display = 'none';
            }
        }, 1000);
    }
}

// Resend OTP
function resendOtp() {
    // Check if maximum resend attempts reached
    if (window.resendAttempts >= MAX_RESEND_ATTEMPTS) {
        showError(
            "Maximum resend attempts reached. Please try again later or contact support."
        );
        return;
    }

    // Clear any existing errors first
    clearErrors();

    // Increment resend attempts counter
    window.resendAttempts++;

    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = "Sending...";
    button.disabled = true;

    // Make API call
    fetch(window.otpRoutes.resendOtp, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            mobile_number: window.mobileNumber,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const remainingAttempts =
                    MAX_RESEND_ATTEMPTS - window.resendAttempts;
                showSuccess(
                    `OTP resent successfully! ${
                        remainingAttempts > 0
                            ? `(${remainingAttempts} attempts remaining)`
                            : ""
                    }`
                );
                show30SecondTimer();
                // Start countdown again
                startResendCountdown();

                // If maximum attempts reached, disable the resend button permanently
                if (window.resendAttempts >= MAX_RESEND_ATTEMPTS) {
                    const resendButton =
                        document.querySelector(".resend-otp-btn");
                    if (resendButton) {
                        resendButton.textContent = "Max attempts reached";
                        resendButton.disabled = true;
                        resendButton.style.opacity = "0.6";
                        resendButton.style.cursor = "not-allowed";
                    }
                }
            } else {
                if (data.message) {
                    showError(data.message);
                } else {
                    showError("Failed to resend OTP. Please try again.");
                }
                console.error("Resend OTP failed:", data);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showError(
                "Network error. Please check your connection and try again."
            );
        })
        .finally(() => {
            // Reset button
            button.textContent = originalText;
            button.disabled = false;
        });
}

// Utility functions
function showStep(stepIndex) {
    console.log("showStep", stepIndex);
    // Hide all steps
    document.querySelectorAll(".step").forEach((step) => {
        step.style.display = "none";
    });

    // Show the target step
    const targetStep = document.querySelector(`.step-${stepIndex}`);
    if (targetStep) {
        targetStep.style.display = "block";
    }

    // Clear any existing errors when changing steps
    clearErrors();
}

function closeModal() {
    const modal = document.getElementById("signupModal");
    if (modal) {
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }
    }
}

function resetForm() {
    // Clear all inputs with error handling
    const mobileInput = document.getElementById("mobile_number");
    const firstNameInput = document.getElementById("firstname");
    const emailInput = document.getElementById("email");

    if (mobileInput) mobileInput.value = "";
    if (firstNameInput) firstNameInput.value = "";
    if (emailInput) emailInput.value = "";

    // Clear OTP inputs
    document.querySelectorAll(".otp-input").forEach((input) => {
        input.value = "";
    });

    // Reset country code to default
    const countrySelect = document.getElementById("selected-code");
    if (countrySelect) {
        if (typeof countrySelect === "string") {
            // If it's a string, update display
            const displayElement = document.querySelector(".selected-code");
            if (displayElement) {
                displayElement.textContent = "+61";
            }
        } else if (countrySelect.textContent !== undefined) {
            // If it's a DOM element (span), set text content
            countrySelect.textContent = "+61";
        } else if (countrySelect.value !== undefined) {
            // If it's a DOM element with value property, set value
            countrySelect.value = "+61";

            // Update display
            const displayElement = countrySelect.parentNode
                ? countrySelect.parentNode.querySelector(".selected-code")
                : document.querySelector(".selected-code");
            if (displayElement) {
                displayElement.textContent = "+61";
            }
        }
    }

    // Clear all errors and success messages
    clearErrors();

    // Reset global variables
    window.mobileNumber = "";
    window.isLoginFlow = false;
    window.resendAttempts = 0;

    // Reset button states
    const resendButton = document.querySelector(".resend-otp-btn");
    if (resendButton) {
        resendButton.textContent = "Resend OTP";
        resendButton.disabled = false;
        resendButton.style.opacity = "1";
        resendButton.style.cursor = "pointer";
        resendButton.style.display = "inline";
    }

    // Reset resend timer text to original
    const resendTimerSpan = document.getElementById("resend-timer");
    if (resendTimerSpan && resendTimerSpan.parentElement) {
        resendTimerSpan.parentElement.innerHTML =
            'Resend code in <span id="resend-timer">00:30</span>';
    }

    // Reset resend link
    const resendLink = document.getElementById("resend-otp-link");
    if (resendLink) {
        resendLink.style.display = "none";
    }

    // Show step 1
    showStep(1);
}

function showError(message, elementId = null) {
    // Remove any existing error messages
    const existingErrors = document.querySelectorAll(".error-message");
    existingErrors.forEach((error) => error.remove());

    // Create error message element
    const errorDiv = document.createElement("div");
    errorDiv.className = "error-message";
    errorDiv.textContent = message;
    errorDiv.style.color = "#dc3545";
    errorDiv.style.fontSize = "14px";
    errorDiv.style.marginTop = "0";
    errorDiv.style.marginBottom = "15px";
    errorDiv.style.padding = "12px 16px";
    errorDiv.style.backgroundColor = "#f8d7da";
    errorDiv.style.border = "1px solid #f5c6cb";
    errorDiv.style.borderRadius = "6px";
    errorDiv.style.display = "block";
    errorDiv.style.fontWeight = "500";
    errorDiv.style.textAlign = "center";
    errorDiv.style.boxShadow = "0 2px 4px rgba(220, 53, 69, 0.1)";
    errorDiv.style.position = "fixed";
    errorDiv.style.top = "20px";
    errorDiv.style.left = "50%";
    errorDiv.style.transform = "translateX(-50%)";
    errorDiv.style.zIndex = "9999";
    errorDiv.style.minWidth = "300px";

    // Add data-field attribute for tracking
    if (elementId) {
        errorDiv.setAttribute("data-field", elementId);
    }

    // Insert directly into body for guaranteed visibility
    document.body.appendChild(errorDiv);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.parentNode.removeChild(errorDiv);
        }
    }, 5000);
}

function showSuccess(message) {
    // Remove any existing success messages
    const existingSuccess = document.querySelectorAll(".success-message");
    existingSuccess.forEach((success) => success.remove());

    // Create success message element
    const successDiv = document.createElement("div");
    successDiv.className = "success-message";
    successDiv.textContent = message;
    successDiv.style.color = "#155724";
    successDiv.style.fontSize = "14px";
    successDiv.style.marginTop = "0";
    successDiv.style.marginBottom = "15px";
    successDiv.style.padding = "12px 16px";
    successDiv.style.backgroundColor = "#d4edda";
    successDiv.style.border = "1px solid #c3e6cb";
    successDiv.style.borderRadius = "6px";
    successDiv.style.display = "block";
    successDiv.style.fontWeight = "500";
    successDiv.style.textAlign = "center";
    successDiv.style.boxShadow = "0 2px 4px rgba(21, 87, 36, 0.1)";
    successDiv.style.position = "fixed";
    successDiv.style.top = "20px";
    successDiv.style.left = "50%";
    successDiv.style.transform = "translateX(-50%)";
    successDiv.style.zIndex = "9999";
    successDiv.style.minWidth = "300px";

    // Insert directly into body for guaranteed visibility
    document.body.appendChild(successDiv);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (successDiv.parentNode) {
            successDiv.parentNode.removeChild(successDiv);
        }
    }, 5000);
}

function clearErrors() {
    // Remove all error messages
    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach((error) => error.remove());

    // Remove all success messages
    const successMessages = document.querySelectorAll(".success-message");
    successMessages.forEach((success) => success.remove());

    // Remove any remaining invalid classes and border colors (for cleanup)
    const invalidElements = document.querySelectorAll(".is-invalid");
    invalidElements.forEach((element) => {
        element.classList.remove("is-invalid");
        element.style.borderColor = "";
    });
}

function clearFieldError(element) {
    // Clear error for specific field (only remove error messages, no styling to clear)
    const fieldId = element.id || element.getAttribute("data-field");
    if (fieldId) {
        const fieldErrors = document.querySelectorAll(
            `[data-field="${fieldId}"]`
        );
        fieldErrors.forEach((error) => error.remove());
    }
}

function startResendCountdown() {
    const resendButton = document.querySelector(".resend-otp-btn");
    if (!resendButton) return;

    // Check if maximum attempts reached
    if (window.resendAttempts >= MAX_RESEND_ATTEMPTS) {
        // Replace "Resend code in" text with limit message
        const resendTimerSpan = document.getElementById("resend-timer");
        if (resendTimerSpan && resendTimerSpan.parentElement) {
            resendTimerSpan.parentElement.innerHTML =
                "You have reached resend limit";
        }

        // Hide the resend button completely
        resendButton.style.display = "none";
        return;
    }

    let countdown = 60;
    resendButton.disabled = true;

    const countdownInterval = setInterval(() => {
        const remainingAttempts = MAX_RESEND_ATTEMPTS - window.resendAttempts;
        resendButton.textContent = `Resend OTP (${countdown}s) - ${remainingAttempts} attempts left`;
        countdown--;

        if (countdown < 0) {
            clearInterval(countdownInterval);
            if (window.resendAttempts >= MAX_RESEND_ATTEMPTS) {
                // Replace "Resend code in" text with limit message
                const resendTimerSpan = document.getElementById("resend-timer");
                if (resendTimerSpan && resendTimerSpan.parentElement) {
                    resendTimerSpan.parentElement.innerHTML =
                        "You have reached resend limit";
                }

                // Hide the resend button completely
                resendButton.style.display = "none";
            } else {
                resendButton.textContent = "Resend OTP";
                resendButton.disabled = false;
            }
        }
    }, 1000);
}

// Global variable to store mobile number
window.mobileNumber = "";

// Global variable to track if this is a login or registration flow
window.isLoginFlow = false;

// Global variable to track resend attempts
window.resendAttempts = 0;
const MAX_RESEND_ATTEMPTS = 3;

// DOM Content Loaded
document.addEventListener("DOMContentLoaded", function () {
    // OTP input handling
    const otpInputs = document.querySelectorAll(".otp-input");

    otpInputs.forEach((input, index) => {
        // Auto-tab to next input
        input.addEventListener("input", function () {
            if (this.value.length === 1) {
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            }
        });

        // Handle backspace
        input.addEventListener("keydown", function (e) {
            if (e.key === "Backspace" && this.value.length === 0) {
                if (index > 0) {
                    otpInputs[index - 1].focus();
                }
            }
        });

        // Clear field error on input
        input.addEventListener("input", function () {
            clearFieldError(this);
        });
    });

    // Mobile number input restrictions
    const mobileInput = document.getElementById("mobile_number");
    if (mobileInput) {
        // Remove non-digit characters on input
        mobileInput.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, "");
            clearFieldError(this);
        });

        // Prevent non-numeric paste
        mobileInput.addEventListener("paste", function (e) {
            e.preventDefault();
            const pastedText = (
                e.clipboardData || window.clipboardData
            ).getData("text");
            const numericOnly = pastedText.replace(/\D/g, "");
            this.value = numericOnly;
        });

        // Prevent non-numeric keypress
        mobileInput.addEventListener("keypress", function (e) {
            if (!/\d/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Clear field error on input
        mobileInput.addEventListener("input", function () {
            clearFieldError(this);
        });
    }

    // Other input field error clearing
    const firstNameInput = document.getElementById("firstname");
    if (firstNameInput) {
        firstNameInput.addEventListener("input", function () {
            clearFieldError(this);
        });
    }

    const emailInput = document.getElementById("email");
    if (emailInput) {
        emailInput.addEventListener("input", function () {
            clearFieldError(this);
        });
    }

    // Clear errors when user changes country code selection
    document.addEventListener("change", function (e) {
        if (e.target.id === "selected-code") {
            // Update the display text
            let selectedValue = "+61"; // Default

            if (typeof e.target === "string") {
                selectedValue = e.target;
            } else if (e.target.textContent) {
                selectedValue = e.target.textContent.trim();
            } else if (e.target.value) {
                selectedValue = e.target.value;
            }

            const displayElement = e.target.parentNode
                ? e.target.parentNode.querySelector(".selected-code")
                : document.querySelector(".selected-code");

            if (displayElement) {
                displayElement.textContent = selectedValue;
            }

            clearFieldError(e.target);
        }
    });

    // Clear general errors when clicking modal buttons
    document.addEventListener("click", function (e) {
        if (e.target.matches("#signupModal .btn")) {
            const generalErrors = document.querySelectorAll(
                ".error-message:not([data-field])"
            );
            generalErrors.forEach((error) => error.remove());
        }
    });

    // Initialize country code when modal opens
    $("#signupModal").on("shown.bs.modal", function () {
        // Ensure country code is set to default
        const countrySelect = document.getElementById("selected-code");
        if (countrySelect) {
            // Handle both element and string cases
            if (typeof countrySelect === "string") {
                // If it's a string, we can't set value, but we can update display
                const displayElement = document.querySelector(".selected-code");
                if (displayElement) {
                    displayElement.textContent = countrySelect || "+61";
                }
            } else if (countrySelect.textContent !== undefined) {
                // If it's a DOM element (span), set text content
                if (
                    !countrySelect.textContent ||
                    countrySelect.textContent.trim() === ""
                ) {
                    countrySelect.textContent = "+61"; // Default to Australia
                }
            } else if (countrySelect.value !== undefined) {
                // If it's a DOM element with value property, set value
                if (!countrySelect.value || countrySelect.value === "") {
                    countrySelect.value = "+61"; // Default to Australia
                }

                // Update the display text to show the selected value
                const displayElement =
                    countrySelect.parentNode.querySelector(".selected-code");
                if (displayElement) {
                    displayElement.textContent = countrySelect.value;
                }
            }
        }

        // Focus on mobile number input
        setTimeout(() => {
            const mobileInput = document.getElementById("mobile_number");
            if (mobileInput) {
                mobileInput.focus();
            }
        }, 100);
    });

    // Reset form when modal is closed
    $("#signupModal").on("hidden.bs.modal", function () {
        resetForm();
    });
});
