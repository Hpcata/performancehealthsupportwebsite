<!-- Sign-In Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="login-error" class="text-danger"></div> <!-- This will display the error message -->
                <!-- Sign In Form -->
                <form id="login-form">
                    <div class="mb-3">
                        <label for="login-email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="login-email" required>
                    </div>
                    <div class="mb-3">
                        <label for="login-password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="login-password" required>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit" id="login-submit" class="btn btn-primary w-100 mt-3" style="justify-content: center;">
                        Sign In
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="mt-3 text-center">
                    <small>Don't have an account? <a href="#" id="show-signup-modal">Sign Up</a></small>
                </div>
            </div>
        </div>
    </div>
</div>