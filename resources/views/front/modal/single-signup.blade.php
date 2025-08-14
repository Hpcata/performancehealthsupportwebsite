<!-- Single Sign up Modal -->
<div class="modal fade" id="signupModalathlete" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="signup-container">
                <div class="signup-modal">
                    <button type="button" class="close-button" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>

                    <!-- Step 1: Phone Number Input -->
                    <div class="form-section" id="step1">

                        <div class='signup-login-h2-title d-none'>
                            <!-- singup/login  -->
                            <img src="{{ frontAssets('images/athleat_logo_full_colour.svg') }}" alt="ATHLEAT Fuel Logo"
                                width="168" height="19" style="margin-bottom: 47px;" />
                            <h2 class="welcome-title">Welcome to ATHLEAT</h2>
                        </div>
                        <!-- or -->
                        <div class='quiz-h2-title d-none'>
                            <!-- quiz -->
                            <h2 class="welcome-title">Welcome to <img
                                    src="{{ frontAssets('images/athleat_logo_full__colour.svg') }}"
                                    alt="ATHLEAT Fuel Logo" width="168" height="19" /></h2>
                        </div>


                        <!-- 1 -->
                        <div class='signup-login-h2-title d-none'>
                            <p class="welcome-text" style="margin-bottom: 30px;">
                                Fast, safe access. Just pop in your number and we'll text you a code. No passwords, no
                                fuss.
                            </p>
                        </div>

                        <!-- or -->

                        <!-- 2 -->
                        <div class='quiz-h2-title d-none'>
                            <p class="welcome-text" style="margin-bottom: 30px;">
                                Get your personalised quiz results and discover where you stand.
                            </p>
                            <p class="welcome-text" style="margin-bottom: 30px;">
                                Fast, safe access. Just pop in your number and we’ll text you a code. No passwords, no
                                fuss.
                            </p>
                        </div>

                        <div class="input-group">
                            <div class="phone-input-container">
                                <div class="dropdown-wrapper" onclick="toggleLoginDropdown()">
                                    <span id="selected-flag" class="fi fi-au"></span>
                                    <span id="selected-code">+61</span>
                                    <svg class="arrow" width="12" height="8" viewBox="0 0 12 8" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <input type="tel" id="mobile_number" class="phone-input"
                                    placeholder="Enter Phone Number" inputmode="numeric" pattern="[0-9]*"
                                    maxlength="15">

                                <div id="login-dropdown" class="hidden dropdown">
                                    <input id="login-search-input" type="text" placeholder="Search country..." />
                                    <ul id="login-country-list"></ul>
                                </div>
                            </div>
                        </div>

                        <div class='signup-login-h2-title d-none'>
                            <!--  -->
                            <label class="terms-label">By continuing, you agree to our <span class="terms-link"
                                    onclick="openTermsModal()">Terms.</span></label>
                            <br>
                            <label class="terms-label d-none" id="new-user-singup">New User? <span
                                    class="terms-link">Sign Up here For Free.</span></label>
                            <label class="terms-label d-none" id="existing-user-login">Existing User? <span
                                    class="terms-link">Log in here.</span></label>
                        </div>

                        <div class='quiz-h2-title d-none'>
                            <!--  -->
                            <p class="welcome-text" style="margin-top: 10px;">
                                Sign up for free to unlock your performance dashboard and find out how you did. We’ll
                                send your full results to your inbox-plus tools and tips to help you level up your
                                nutrition.
                            </p>
                        </div>


                        <button class="btn-signup" style="margin-top:8px;" onclick="sendOtp()">Continue</button>

                        <!--  -->
                        <div class='quiz-h2-title d-none'>
                            <label class="terms-label">By continuing, you agree to our <span class="terms-link"
                                    onclick="openTermsModal()">Terms.</span></label>
                        </div>

                        <div class='signup-login-h2-title d-none'>
                            <div class="or-divider">
                                <div class="divider-line"></div>
                                <span class="or-text">OR</span>
                                <div class="divider-line"></div>
                            </div>

                            <div class="social-buttons">
                                <button class="social-button" onmouseenter="showComingSoonTooltip(this, 'Google')"
                                    onmouseleave="hideComingSoonTooltip()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.7643 2.24729C9.71461 2.21578 7.70395 2.80909 5.99975 3.94829C1.9895 6.62804 0.423504 11.7918 2.26925 16.2483C4.11425 20.7048 8.8715 23.2458 13.6025 22.3053C18.3335 21.3641 21.7558 17.194 21.7558 12.3708H21.7513V11.2458H12.7513V14.2458H18.6163C18.2678 15.5505 17.5604 16.7315 16.5745 17.6544C15.5885 18.5772 14.3634 19.2051 13.0385 19.4666C11.3965 19.796 9.69112 19.5445 8.21417 18.755C6.73723 17.9656 5.58059 16.6873 4.94225 15.1391C4.29907 13.593 4.21318 11.8716 4.69928 10.2692C5.18539 8.66681 6.21326 7.28313 7.607 6.35503C8.99776 5.42235 10.6695 5.00212 12.336 5.16631C14.0024 5.33049 15.56 6.06893 16.742 7.25508L18.7895 5.20906C16.9237 3.34331 14.4027 2.28046 11.7643 2.24729Z"
                                            fill="#EA4335" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.72797 14.5508L2.32422 16.3643C4.20522 20.7458 8.91372 23.2365 13.602 22.3043C15.7059 21.8834 17.621 20.8032 19.0695 19.2202L16.8045 17.4082C15.9341 18.3072 14.8451 18.9646 13.6441 19.3159C12.4432 19.6672 11.1716 19.7004 9.95395 19.4123C8.73631 19.1242 7.61443 18.5246 6.69829 17.6724C5.78215 16.8201 5.10319 15.7445 4.72797 14.5508Z"
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
                                <button class="social-button" onmouseenter="showComingSoonTooltip(this, 'Facebook')"
                                    onmouseleave="hideComingSoonTooltip()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <g clip-path="url(#clip0_2822_5214)">
                                            <path
                                                d="M24 12C24 5.37264 18.6274 0 12 0C5.37264 0 0 5.37264 0 12C0 17.6275 3.87456 22.3498 9.10128 23.6467V15.6672H6.62688V12H9.10128V10.4198C9.10128 6.33552 10.9498 4.4424 14.9597 4.4424C15.72 4.4424 17.0318 4.59168 17.5685 4.74048V8.06448C17.2853 8.03472 16.7933 8.01984 16.1822 8.01984C14.2147 8.01984 13.4544 8.76528 13.4544 10.703V12H17.3741L16.7006 15.6672H13.4544V23.9122C19.3963 23.1946 24.0005 18.1354 24.0005 12H24Z"
                                                fill="#0866FF" />
                                            <path
                                                d="M16.6988 15.6701L17.3722 12.0029H13.4525V10.706C13.4525 8.76819 14.2128 8.02275 16.1804 8.02275C16.7914 8.02275 17.2834 8.03763 17.5666 8.06739V4.74339C17.03 4.59411 15.7181 4.44531 14.9578 4.44531C10.9479 4.44531 9.0994 6.33843 9.0994 10.4228V12.0029H6.625V15.6701H9.0994V23.6496C10.0277 23.88 10.9988 24.0029 11.9981 24.0029C12.4901 24.0029 12.9754 23.9727 13.452 23.9151V15.6701H16.6983H16.6988Z"
                                                fill="white" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2822_5214">
                                                <rect width="24" height="24" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    Continue with Facebook
                                    <div>&nbsp;</div>
                                </button>
                                <button class="social-button" onmouseenter="showComingSoonTooltip(this, 'Apple')"
                                    onmouseleave="hideComingSoonTooltip()" style="justify-content: center;">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                        fill="none" style="margin-right: 6px;">
                                        <g clip-path="url(#clip0_2822_5220)">
                                            <path
                                                d="M18.6593 15.5861C18.3569 16.2848 17.9988 16.928 17.584 17.5194C17.0186 18.3255 16.5557 18.8835 16.1989 19.1934C15.6458 19.702 15.0533 19.9625 14.4187 19.9773C13.9632 19.9773 13.4138 19.8477 12.7743 19.5847C12.1327 19.323 11.5431 19.1934 11.004 19.1934C10.4386 19.1934 9.83219 19.323 9.18357 19.5847C8.53396 19.8477 8.01064 19.9847 7.61053 19.9983C7.00203 20.0242 6.39551 19.7563 5.7901 19.1934C5.40369 18.8563 4.92037 18.2786 4.34138 17.4601C3.72016 16.586 3.20944 15.5725 2.80933 14.417C2.38082 13.1689 2.16602 11.9603 2.16602 10.7902C2.16602 9.44984 2.45564 8.29383 3.03574 7.32509C3.49165 6.54697 4.09818 5.93316 4.85729 5.48255C5.6164 5.03195 6.43662 4.80233 7.31992 4.78764C7.80324 4.78764 8.43705 4.93714 9.22468 5.23096C10.0101 5.52576 10.5144 5.67526 10.7355 5.67526C10.9008 5.67526 11.461 5.50045 12.4107 5.15195C13.3089 4.82875 14.0669 4.69492 14.6878 4.74764C16.3705 4.88344 17.6347 5.54675 18.4754 6.74177C16.9705 7.6536 16.2261 8.93072 16.2409 10.5691C16.2545 11.8452 16.7174 12.9071 17.6272 13.7503C18.0396 14.1417 18.5001 14.4441 19.0124 14.6589C18.9013 14.9812 18.784 15.2898 18.6593 15.5861V15.5861ZM14.8002 0.400114C14.8002 1.40034 14.4348 2.33425 13.7064 3.19867C12.8274 4.22629 11.7642 4.8201 10.6113 4.7264C10.5966 4.60641 10.5881 4.48011 10.5881 4.3474C10.5881 3.38718 11.0061 2.35956 11.7484 1.51934C12.119 1.09392 12.5904 0.74019 13.162 0.458013C13.7323 0.180046 14.2718 0.0263242 14.7792 0C14.794 0.133715 14.8002 0.267438 14.8002 0.400101V0.400114Z"
                                                fill="black" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2822_5220">
                                                <rect width="20" height="20" fill="white" transform="translate(0.5)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    Sign in with Apple
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Step 2: OTP Verification -->
                    <div class="form-section" id="step2" style="display: none;">
                        <img src="{{ frontAssets('images/athleat_logo_full_colour.svg') }}" alt="ATHLEAT Fuel Logo"
                            width="168" height="19" style="margin-bottom: 47px;" />
                        <h2 class="welcome-title">OTP Verification</h2>
                        <p class="welcome-text-otp">
                            We’ve sent you a code. Please check the <br /> Phone at <span id="phone-number"></span>
                        </p>

                        <div class="input-group">
                            <div class="otp-input-group">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric"
                                    autocomplete="one-time-code" />
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]"
                                    inputmode="numeric" />
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]"
                                    inputmode="numeric" />
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 8 8"
                                    fill="none">
                                    <circle cx="4" cy="4" r="4" fill="#080808" />
                                </svg>
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]"
                                    inputmode="numeric" />

                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]"
                                    inputmode="numeric" />
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]"
                                    inputmode="numeric" />
                            </div>
                        </div>
                        <button class="btn-signup" style="margin-top:20px;margin-bottom: 10px;"
                            onclick="verifyOtp()">Verify</button>
                        <p class="otp-resend-text">
                            <label>
                                Resend code in <span id="resend-timer">00:30</span>
                                <a href="#" id="resend-otp-link" class="login-link" onclick="resendOtp()"
                                    style="display:none;">Resend OTP</a>
                            </label>
                        </p>
                        <label class="security-note">Security note: PIN valid for 5 mins. 3 attempts
                            max.</label>
                    </div>

                    <!-- Step 3: User Type Selection -->
                    <div class="form-section" id="step3" style="display: none;">
                        <img src="{{ frontAssets('images/athleat_logo_full_colour.svg') }}" alt="ATHLEAT Fuel Logo"
                            width="168" height="19" style="margin-bottom: 47px;" />
                        <h2 class="welcome-title">Sign up</h2>
                        <p class="welcome-text">
                            Join for free and get easy, effective nutrition tips that truly work.</span>
                        </p>

                        <div class="input-group" style="margin-top: 36px; margin-bottom: 20px;">
                            <input type="text" id="firstname" placeholder="First Name" class="email-input" />
                        </div>
                        <div class="input-group">
                            <input type="email" id="email" placeholder="Enter email address" class="email-input" />
                        </div>

                        <div class="" style="width:100%">
                            <h3 style="font-size: 14px; font-weight: 400; color: #333;     margin-bottom: 14px;
                                    margin-top: 44px;">User Type</h3>
                            <div class="user-type-selection" id="user-type-section-id">
                                <label class="user-type-box">
                                    <input type="radio" name="userType" value="athlete" class="sr-only" />
                                    <div class="custom-radio"></div>

                                    <img src="{{ frontAssets('images/hurdle 1.svg') }}" alt="apple logo" class=""
                                        style="margin-right: 6px;" width="40" height="40" />
                                    <div class="title-wrap">
                                        <label class="user-type-text-title">Athlete</label>
                                        <label class="user-type-text-subtitle">Train, compete, and grow</label>
                                    </div>
                                </label>

                                <label class="user-type-box">
                                    <input type="radio" name="userType" value="parent" class="sr-only" />
                                    <div class="custom-radio"></div>
                                    <img src="{{ frontAssets('images/cultural-diversity 1.svg') }}" alt="apple logo"
                                        class="" style="margin-right: 6px;" width="52" height="52" />
                                    <div class="title-wrap">
                                        <label class="user-type-text-title">Parent</label>
                                        <label class="user-type-text-subtitle">Support your young athlete</label>
                                    </div>
                                </label>

                                <label class="user-type-box">
                                    <input type="radio" name="userType" value="club" class="sr-only" />
                                    <div class="custom-radio"></div>
                                    <img src="{{ frontAssets('images/community 1.svg') }}" alt="apple logo" class=""
                                        style="margin-right: 6px;" width="40" height="40" />
                                    <div class="title-wrap">
                                        <label class="user-type-text-title">Club</label>
                                        <label class="user-type-text-subtitle">Manage teams and talent</label>
                                    </div>

                                </label>
                            </div>
                            <div class="form-group d-none" id="select-sports-id">
                                <div class="input-group">
                                    <select name="sportstype" id="sportstype" class="sports-select">
                                        <option>Select Sports</option>
                                        @foreach(getSports() as $sport)
                                            <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="sports-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Age Range Section -->
                        <div class="user-type-section d-none" style="margin-top: 30px;width: 100%;" id="age-groups-id">
                            <h3 style="font-size: 14px; font-weight: 400; color: #333; margin-bottom: 20px;">Age Range
                            </h3>
                            <div class="age-selection-box">
                                @foreach(getAgeGroups() as $key => $ageGroup)
                                    <label class="user-type-box">
                                        <input type="radio" name="ageGroup" value="{{ $key }}" class="sr-only" />
                                        <div class="custom-radio"></div>
                                        <!-- SVG for Age Group -->
                                        <span class="user-type-text">{{ $ageGroup }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button class="btn-signup" onclick="completeRegistration()">Get Started</button>
                    </div>

                    <!-- Step 4: Final Step (can be customized as needed) -->
                    <div class="form-section" id="step4" style="display: none;">
                        <img src="{{ frontAssets('images/athleat_logo_full_colour.svg') }}" alt="ATHLEAT Fuel Logo"
                            width="168" height="19" style="margin-bottom: 47px;" />
                        <h2 class="welcome-title">Welcome!</h2>
                        <p class="welcome-text">
                            Your account has been created successfully. You're all set to start your journey!
                        </p>
                        <button class="btn-signup" onclick="closeModal()">Get Started</button>
                    </div>

                    <div class="image-section signup-login-h2-title d-none signup-login-h2-img">
                        <img src="{{ asset('front/images/signup/login-bg.svg') }}" alt="Bowl of healthy food"
                            class="food-image" />
                    </div>
                    <div class="p-4 image-section quiz-h2-title d-none quiz-h2-img">
                        <img src="{{ asset('front/images/quiz/last-step-login-bg.svg') }}" alt="Bowl of healthy food"
                            class="food-image" />
                    </div>
                </div>
            </div>

            <input type="hidden" name="isFromQuizPopup" id="isFromQuizPopup" value="0">
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Mobile Terms</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="terms-content">
                    <p>When you give us your mobile number, you're helping us make sign-in quick, secure, and
                        password-free.</p>
                    <p>We'll never sell your number to anyone, and we won't share it with third-party marketers.</p>
                    <p><strong>We may use your number to:</strong></p>
                    <ul>
                        <li>Send one-time PINs for secure login</li>
                        <li>Text you important reminders, updates, or new features</li>
                        <li>Occasionally share helpful tips or offers (you can opt out anytime)</li>
                        <li>Invite you to optional chat groups (like Virtual Kerry on WhatsApp) to get support, submit
                            meal pics,
                            or learn more</li>
                    </ul>
                    You're always in control — opt out any time via your privacy settings. We're here to support your
                    performance, not spam your phone.💪
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>