// Initialize Stripe immediately when the script loads
const script = document.createElement("script");
script.src = "https://js.stripe.com/v3/";

script.onload = function () {
    // Wait a moment to ensure Stripe is fully initialized
    setTimeout(function() {
        try {
            // Initialize Stripe immediately
            let stripe;
            if (window.purchasePlanConfig.env === "production" || window.purchasePlanConfig.env === "staging") {
                stripe = Stripe(
                    "pk_live_51Pfz1YLSisFoEruHvHpdQQZLynQoR3xqBDuBgpb84zTK3EnTlROWMjxVpZhrp1rLmaqCJbusOUNHUoTKBLK7CXru00CkS5tVbt"
                );
            } else {
                stripe = Stripe(
                    "pk_test_51Pfz1YLSisFoEruHJsESsPDWs6hAT5sKbgJrpx3ThRMPIO1pFJCG896zwDiQa34ulhfjHJb6cLErvc9s99air7xf00bfkV8AGc"
                );
            }

            const elements = stripe.elements();
            const style = {
                base: {
                    color: "#32325d",
                    border: "1px solid #32325d",
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: "antialiased",
                    fontSize: "16px",
                    "::placeholder": {
                        color: "#aab7c4",
                    },
                },
                invalid: {
                    color: "#fa755a",
                    iconColor: "#fa755a",
                },
            };

            // Create card element
            const card = elements.create("card", { style: style });
            const cardErrors = document.getElementById("card-errors");

            // Function to mount card element
            function mountCardElement() {
                const cardElement = document.getElementById("card-element");
                if (cardElement && !card._mounted) {
                    try {
                        card.mount("#card-element");
                        card._mounted = true;
                    } catch (error) {
                        console.error("Error mounting card element:", error);
                    }
                }
            }

            // Mount card element immediately if the element exists
            if (cardErrors) {
                mountCardElement();
            }

            // Handle card input changes
            card.on("change", function (event) {
                const displayError = document.getElementById("card-errors");
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = "";
                }
            });

            // Main initialization
            $(document).ready(function () {
                // Check if jQuery and Bootstrap modal are available
                if (typeof $ === "undefined") {
                    console.error("jQuery is not loaded");
                    alert("Error: jQuery is not loaded. Please refresh the page.");
                    return;
                }

                if (typeof $.fn.modal === "undefined") {
                    console.error("Bootstrap modal method not available");
                    alert("Error: Modal functionality not available. Please refresh the page.");
                    return;
                }

                // Check if modal exists
                const purchaseModal = $("#purchaseModal");
                if (purchaseModal.length === 0) {
                    alert("Error: Purchase modal not found. Please refresh the page.");
                    return;
                }

                // Ensure card is mounted when modal opens
                purchaseModal.on("shown.bs.modal", function () {
                    setTimeout(mountCardElement, 100);
                });

                // Reset modal on close
                purchaseModal.on("hidden.bs.modal", function () {
                    $("#payment-form")[0].reset();
                    $("#card-errors").text("");

                    // Reset coupon UI and state
                    resetCouponState();
                });

                // Function to reset coupon state
                function resetCouponState() {
                    const couponDetails = $("#coupon-details");
                    const toggleLink = $("#toggle-coupon-link");
                    const promoInput = $("#promo-code");
                    const promoMessage = $("#promo-message");

                    // Hide coupon section
                    couponDetails.addClass("d-none");

                    // Reset toggle link text
                    toggleLink.text("Add a Coupon Code");

                    // Clear all coupon data
                    promoInput.val("");
                    $("#discount").val("");
                    promoMessage.text("");

                    // Clear message styling
                    promoMessage.removeClass("text-success text-danger");

                    // Hide discount display
                    $("#current-discount-display").addClass("d-none");

                    // Always show payment details when modal is reset
                    $("#payment-details").show();
                }

                // Event listener for the 'Purchase Now' button
                $("body").on("click", ".purchase-now-btn", function () {
                    // Check if configuration is loaded
                    if (!window.purchasePlanConfig) {
                        alert(
                            "Error: Purchase plan configuration not loaded. Please refresh the page."
                        );
                        return;
                    }

                    const planId = $(this).data("plan-id");
                    const planName = $(this).data("plan-name");
                    const price = $(this).data("plan-price");

                    $("#purchaseModalLabel").text(
                        `Purchase ${planName} ($${price})`
                    );

                    // Check authentication status from config
                    const { isAuthenticated, isAdmin, userData } =
                        window.purchasePlanConfig;

                    if (isAuthenticated && !isAdmin) {
                        $("#registration-details").hide();
                        $("#payment-details").show();
                        $("#signed-in-details").removeClass("d-none");
                        $("#already-signed-in").addClass("d-none");

                        // Set user data if available
                        if (userData) {
                            $("#user_name").val(userData.name || "");
                            $("#user_email").val(userData.email || "");
                            $("#user_phone").val(userData.phone || "");
                            $("#signed-in-email").text(userData.email || "");
                        }
                    }

                    // Show modal
                    purchaseModal.modal("show");

                    // Ensure card element is mounted after modal is shown
                    setTimeout(mountCardElement, 200);

                    // Handle the form submission
                    $("#payment-form")
                        .off("submit")
                        .on("submit", function (event) {
                            event.preventDefault();

                            $("#submit").prop("disabled", true);

                            const formData = {
                                discountCode: $("#promo-code").val(),
                                discount: $("#discount").val(),
                                email: $("#user_email").val(),
                                name: $("#user_name").val(),
                                phone: $("#user_phone").val(),
                                password: $("#user_password").val(),
                            };

                            // Validate required fields
                            const requiredFields = [
                                "name",
                                "email",
                                "phone",
                                "password",
                            ];
                            const fieldNames = {
                                name: "name",
                                email: "email",
                                phone: "phone",
                                password: "password",
                            };

                            // Find all missing fields
                            const missingFields = requiredFields.filter(
                                (field) =>
                                    !formData[field] ||
                                    formData[field].trim() === ""
                            );

                            if (missingFields.length > 0 && $("#registration-details").css("display") !== "none") {
                                $("#submit").prop("disabled", false);

                                // Join missing fields with commas, and 'and' before the last one
                                const formattedList = missingFields
                                    .map((field) => fieldNames[field])
                                    .join(", ")
                                    .replace(/, ([^,]*)$/, " and $1");

                                const message = `<p style="color: red;">Please fill ${formattedList} field${
                                    missingFields.length > 1 ? "s" : ""
                                }</p>`;

                                $("#errorModalLabel").text("Error");
                                $("#errorModalBody").html(message);
                                new bootstrap.Modal(
                                    document.getElementById("errorModal")
                                ).show();
                                $("#purchaseModal").addClass("blur-background");
                                return;
                            }

                            // Handle payment based on discount
                            if (formData.discount == 100.0) {
                                processFreePayment(formData, planId, price);
                            } else {
                                processStripePayment(formData, planId, price);
                            }
                        });

                    // Function to process free payment (100% discount)
                    function processFreePayment(formData, planId, price) {
                        $.ajax({
                            url: window.purchasePlanConfig.paymentUrl,
                            method: "POST",
                            data: {
                                plan_id: planId,
                                price: price,
                                name: formData.name,
                                email: formData.email,
                                phone: formData.phone,
                                password: formData.password,
                                coupon_code: formData.discountCode,
                                _token: window.purchasePlanConfig.csrfToken,
                            },
                            success: handlePaymentSuccess,
                            error: handlePaymentError,
                        });
                    }

                    // Function to process Stripe payment
                    function processStripePayment(formData, planId, price) {
                        stripe
                            .createPaymentMethod({
                                type: "card",
                                card: card,
                                billing_details: {
                                    name: formData.name,
                                    email: formData.email,
                                    phone: formData.phone,
                                },
                            })
                            .then(function (result) {
                                if (result.error) {
                                    cardErrors.textContent =
                                        result.error.message;
                                    $("#submit").prop("disabled", false);
                                } else {
                                    $.ajax({
                                        url: window.purchasePlanConfig
                                            .paymentUrl,
                                        method: "POST",
                                        data: {
                                            payment_method_id: result.paymentMethod.id,
                                            plan_id: planId,
                                            price: price,
                                            name: formData.name,
                                            email: formData.email,
                                            phone: formData.phone,
                                            password: formData.password,
                                            coupon_code: formData.discountCode,
                                            _token: window.purchasePlanConfig.csrfToken,
                                        },
                                        success: handlePaymentSuccess,
                                        error: handlePaymentError,
                                    });
                                }
                            });
                    }

                    // Handle payment success
                    function handlePaymentSuccess(response) {
                        $("#submit").prop("disabled", false);

                        if (response.success) {
                            $("#purchaseModal").modal("hide");

                            if (response.data.submit_questionnaire) {
                                const { user_id, payment_id } = response.data;
                                if (response.redirect_url) {
                                    const redirectUrlWithUserId = `${response.redirect_url}?id=${payment_id}&user_id=${user_id}`;
                                    setTimeout(
                                        () =>
                                            (window.location.href =
                                                redirectUrlWithUserId),
                                        3000
                                    );
                                }
                            } else {
                                $("#thankYouModal").modal("show");
                            }
                        } else {
                            if (
                                response.message ===
                                "You have already purchased this plan. Please login to your account to manage your plans."
                            ) {
                                alert(response.message);
                                $("#purchaseModal").modal("hide");
                            } else {
                                alert("Payment failed: " + response.message);
                            }
                        }
                    }

                    // Handle payment error
                    function handlePaymentError(xhr, status, error) {
                        $("#submit").prop("disabled", false);

                        let message = "";
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            message =
                                '<ul class="mb-1">' +
                                Object.values(errors)
                                    .map(
                                        (value) =>
                                            `<li style="color: red;">${value[0]}</li>`
                                    )
                                    .join("") +
                                "</ul>";
                        } else if (
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        ) {
                            message = `<p style="color: red;">${xhr.responseJSON.message}</p>`;
                        } else {
                            message = `<p style="color: red;">Unexpected Error (${xhr.status}): ${error}</p>`;
                        }

                        $("#errorModalBody").html(message);
                        const errorModal = new bootstrap.Modal(
                            document.getElementById("errorModal")
                        );
                        errorModal.show();
                        $("#purchaseModal").addClass("blur-background");
                    }

                    // Handle coupon code application
                    $("#apply-promo-code").on("click", function () {
                        $("#promo-message").text("");
                        const promoCode = $("#promo-code").val().trim();
                        const promoMessage = $("#promo-message");

                        if (!promoCode) {
                            promoMessage
                                .text("Please enter a coupon code.")
                                .removeClass("text-success")
                                .addClass("text-danger");
                            return;
                        }

                        fetch(window.purchasePlanConfig.validateCouponCodeUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN":
                                    window.purchasePlanConfig.csrfToken,
                            },
                            body: JSON.stringify({
                                code: promoCode,
                                plan_id: planId,
                            }),
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                if (data.valid) {
                                    $("#discount").val(data.discount);

                                    // Update payment details visibility based on discount
                                    updatePaymentDetailsVisibility(data.discount);

                                    // Update toggle link text to show current discount
                                    $("#toggle-coupon-link").text(`Remove Coupon Code (${data.discount}% off)`);
                                } else {
                                    promoMessage
                                        .text(data.message)
                                        .removeClass("text-success")
                                        .addClass("text-danger");
                                }
                            })
                            .catch((error) => {
                                console.error(
                                    "Coupon validation error:",
                                    error
                                );
                                promoMessage
                                    .text(
                                        "Something went wrong. Please try again."
                                    )
                                    .removeClass("text-success")
                                    .addClass("text-danger");
                            });
                    });

                    // Handle manual coupon code input changes
                    $("#promo-code").on("input", function() {
                        const promoCode = $(this).val().trim();

                        // If user clears the coupon code, reset the discount and show payment details
                        if (!promoCode) {
                            $("#discount").val("");
                            $("#promo-message").text("").removeClass("text-success text-danger");
                            $("#current-discount-display").addClass("d-none");
                            $("#payment-details").show();
                            $("#toggle-coupon-link").text("Remove Coupon Code");
                        }
                    });

                    // Function to update payment details visibility based on discount
                    function updatePaymentDetailsVisibility(discount) {
                        const paymentDetails = $("#payment-details");
                        const discountDisplay = $("#current-discount-display");
                        const discountAmount = $("#discount-amount");

                        if (discount === "100.00" || discount == 100.0) {
                            // 100% discount - hide payment details
                            paymentDetails.hide();

                            // Show discount display
                            discountAmount.text("100% (FREE)");
                            discountDisplay.removeClass("d-none");
                        } else if (discount > 0) {
                            // Partial discount - show payment details
                            paymentDetails.show();

                            // Show discount display
                            discountAmount.text(`${discount}% off`);
                            discountDisplay.removeClass("d-none");
                        } else {
                            // No discount - show payment details
                            paymentDetails.show();

                            // Hide discount display
                            discountDisplay.addClass("d-none");
                        }
                    }

                    // Handle coupon toggle
                    $("#toggle-coupon-link").on("click", function (e) {
                        e.preventDefault();
                        const couponDetails = $("#coupon-details");
                        const isHidden = couponDetails.hasClass("d-none");

                        couponDetails.toggleClass("d-none");
                        this.textContent = isHidden ? "Remove Coupon Code" : "Add a Coupon Code";

                        if (!isHidden) {
                            // When hiding coupon section, clear all coupon-related data
                            $("#promo-code").val("");
                            $("#discount").val("");
                            $("#promo-message").text("");

                            // Hide discount display
                            $("#current-discount-display").addClass("d-none");

                            // Always show payment details when removing coupon
                            $("#payment-details").show();

                            // Clear any success/error styling
                            $("#promo-message").removeClass("text-success text-danger");
                        }
                    });
                });
            });
        } catch (error) {
            console.error("Error initializing Stripe:", error);
        }
    }, 100);
};

script.onerror = function () {
    console.error("Failed to load Stripe.js");
};

document.head.appendChild(script);

// Modal navigation handlers
$("#show-login-modal").on("click", function (e) {
    e.preventDefault();
    $("#purchaseModal").modal("hide");
    $("#loginModal").modal("show");
});

$("#show-signup-modal").on("click", function (e) {
    e.preventDefault();
    $("#loginModal").modal("hide");
    $("#purchaseModal").modal("show");
});

// Error modal blur background handling
$("#errorModal").on("hidden.bs.modal", function () {
    $("#purchaseModal").removeClass("blur-background");
});