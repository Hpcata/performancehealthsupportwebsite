<!-- Sign-Up Modal (Purchase Modal) -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseModalLabel">Purchase Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                {{-- Sign In Link --}}
                <div class="mb-3" id="already-signed-in">
                    <small>Already have an account? <a href="#" id="show-login-modal">Sign In</a></small>
                </div>

                {{-- User info form --}}
                <form id="payment-form">
                    <div id="registration-details">
                        <h6 class="mb-3" style="font-weight: 800;">Create Account</h6>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="user_name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="user_email">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="user_phone">
                        </div>

                        {{-- New Password Field --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="user_password">
                            <small class="form-text text-muted">Password must be at least 8 characters long.</small>
                        </div>

                        {{-- Divider --}}
                        <hr class="my-4">
                    </div>

                    <div id="signed-in-details" class="d-none">
                        <p class="mb-2" style="font-size: 15px;">Signed In as</p>
                        <p id="signed-in-email" style="font-weight: 500;"></p>
                        <hr>
                    </div>

                    <h6 class="mb-2" style="font-weight: 800;">Payment Details</h6>

                    <div class="mb-3 mt-3">
                        <small>
                            <a href="#" id="toggle-coupon-link" class="coupon-link">Add a Coupon Code</a>
                        </small>
                    </div>

                    {{-- Promo Code Section --}}
                    <div class="mb-3 d-none" id="coupon-details">
                        <label for="promo-code" class="form-label">Coupon Code</label>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control h-auto" id="promo-code"
                                placeholder="Enter coupon code">
                            <input type="hidden" class="form-control" id="discount">
                            <button type="button" class="btn btn-primary" id="apply-promo-code">Apply</button>
                        </div>
                        <small id="promo-message" class="form-text"></small>

                        {{-- Current Discount Display --}}
                        <div id="current-discount-display" class="mt-2 d-none">
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Current discount: <span id="discount-amount"></span>
                            </small>
                        </div>
                    </div>

                    {{-- Payment Details Section --}}
                    <div id="payment-details">
                        {{-- Stripe Payment Card Section --}}
                        <div class="mb-3">
                            <label for="card-element" class="form-label">Credit or Debit Card</label>
                            <div id="card-element" class="border rounded p-3" style="background-color: #f9f9f9;">
                                {{-- A Stripe Element will be inserted here. --}}
                            </div>
                            <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                        </div>
                    </div>

                    <button type="submit" id="submit" class="btn btn-primary w-100 mt-3" style="justify-content: center;">
                        Purchase
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Error Modal --}}
@include('front.pages.partials.error')

{{-- Thank You Modal --}}
@include('front.pages.partials.thank-you')

<style>
/* Ensure modal is visible when shown manually */
#purchaseModal.show {
    display: block !important;
    background-color: rgba(0, 0, 0, 0.5);
}

#purchaseModal.show .modal-dialog {
    transform: none !important;
}

.modal-backdrop.show {
    opacity: 0.5;
}

/* Blur background for error modal */
.blur-background {
    filter: blur(5px);
}
</style>