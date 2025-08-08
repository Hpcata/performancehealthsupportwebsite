<?php

namespace App\Http\Controllers\Front;

use App\Models\Quiz;
use App\Models\User;
use App\Models\SportGame;
use Illuminate\Support\Str;
use App\Constants\AgeGroups;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OtpRegistrationController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Step 1: Send OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(Request $request)
    {
        // Debug logging to see what's being received
        Log::info('Send OTP Request', [
            'mobile_number' => $request->input('mobile_number'),
            'all_data' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string|regex:/^\+[1-9]\d{1,14}$/'
        ], [
            'mobile_number.required' => 'Mobile number is required.',
            'mobile_number.string' => 'Mobile number must be a string.',
            'mobile_number.regex' => 'Please enter a valid mobile number in international format (e.g., +61434708100). The number must start with + followed by country code and number.'
        ]);

        if ($validator->fails()) {
            Log::warning('Mobile number validation failed', [
                'mobile_number' => $request->input('mobile_number'),
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Please correct the mobile number format.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mobileNumber = $request->input('mobile_number');

        // Check if user already exists with this mobile number
        $existingUser = User::where('phone', $mobileNumber)->first();
        
        try {
            $result = $this->otpService->sendOtp($mobileNumber);

            if ($result['success']) {
                $response = [
                    'success' => true,
                    'message' => 'OTP sent successfully to ' . $mobileNumber,
                    'debug_otp' => $result['debug_otp'] ?? null, // Only in development
                    'user_exists' => $existingUser ? true : false
                ];
                
                // If user exists, include user info (without sensitive data)
                if ($existingUser) {
                    // $response['existing_user'] = [
                    //     'id' => $existingUser->id,
                    //     'name' => $existingUser->name,
                    //     'email' => $existingUser->email,
                    //     'free_user' => $existingUser->free_user
                    // ];
                }
                
                return response()->json($response);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to send OTP. Please try again.',
                    'errors' => [
                        'mobile_number' => [$result['message'] ?? 'Failed to send OTP. Please try again.']
                    ]
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Send OTP failed', [
                'mobile' => $mobileNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.',
                'errors' => [
                    'mobile_number' => ['Service temporarily unavailable. Please try again later.']
                ]
            ], 500);
        }
    }

    /**
     * Step 2: Verify OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string|regex:/^\+[1-9]\d{1,14}$/',
            'otp' => 'required|string|size:6'
        ], [
            'mobile_number.required' => 'Mobile number is required.',
            'mobile_number.regex' => 'Please enter a valid mobile number in international format.',
            'otp.required' => 'OTP is required.',
            'otp.size' => 'OTP must be exactly 6 digits.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please correct the input errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mobileNumber = $request->input('mobile_number');
        $otp = $request->input('otp');

        $isFromQuizPopup = $request->input('isFromQuizPopup');
        $completedQuizId = $request->input('completed_quiz_id');

        try {
            $result = $this->otpService->verifyOtp($mobileNumber, $otp);

            if ($result['success']) {
                // Check if user exists with this mobile number
                $existingUser = User::where('phone', $mobileNumber)->first();

                if ($existingUser && $existingUser->email) {
                    // User exists - log them in
                    Auth::login($existingUser);

                    if($isFromQuizPopup && $completedQuizId) {
                        $quiz = Quiz::where('id', $completedQuizId)->first();
                        if($quiz) {
                            $quiz->user_id = $existingUser->id;
                            $quiz->save();

                            if($existingUser->email) {
                                $this->sendAfterQuizEmail($existingUser->email);
                            }
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful! Welcome back.',
                        'action' => 'login',
                        'user' => [
                            'id' => $existingUser->id,
                            'name' => $existingUser->name,
                            'email' => $existingUser->email,
                            'free_user' => $existingUser->free_user
                        ],
                        'redirectUrl' => route('front.profile', ['id' => $existingUser->id])
                    ]);
                } else {
                    // User doesn't exist - proceed to registration
                    return response()->json([
                        'success' => true,
                        'message' => 'OTP verified successfully! Please complete your registration.',
                        'action' => 'register'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'OTP has expired or is invalid. Please request a new one.',
                    'errors' => [
                        'otp' => [$result['message'] ?? 'OTP has expired or is invalid. Please request a new one.']
                    ]
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('OTP verification failed', [
                'mobile' => $mobileNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'OTP verification failed. Please try again.',
                'errors' => [
                    'otp' => ['An unexpected error occurred. Please try again.']
                ]
            ], 500);
        }
    }

    /**
     * Step 3: Complete registration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string|regex:/^\+[1-9]\d{1,14}$/',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'userType' => 'nullable|string|in:athlete,parent,club',
            'sport' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('userType') === 'athlete') {
                        if (empty($value)) {
                            $fail('The sport field is required when user type is athlete.');
                        } elseif (!\DB::table('sport_games')->where('id', $value)->exists()) {
                            $fail('Please select a valid sport game.');
                        }
                    }
                }
            ],
            'ageGroup' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('userType') === 'athlete') {
                        if (empty($value)) {
                            $fail('The age group field is required when user type is athlete.');
                        } elseif (!is_string($value)) {
                            $fail('Please select a valid age group.');
                        }
                    }
                }
            ],
        ], [
            'mobile_number.required' => 'Mobile number is required.',
            'mobile_number.regex' => 'Please enter a valid mobile number in international format.',
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'userType.in' => 'Please select a valid user type.',
            'sport.exists' => 'Please select a valid sport game.',
            'ageGroup.string' => 'Please select a valid age group.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please correct the input errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mobileNumber = $request->input('mobile_number');
        $firstName = $request->input('first_name');
        $email = $request->input('email');
        $userType = $request->input('userType');
        $sportGameId = $request->input('sport');
        $ageGroup = $request->input('ageGroup');
        $isFromQuizPopup = $request->input('isFromQuizPopup');
        $completedQuizId = $request->input('completed_quiz_id');

        // Check if OTP is verified
        if (!$this->otpService->isOtpVerified($mobileNumber)) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your mobile number with OTP first.',
                'errors' => [
                    'otp' => ['OTP verification required.']
                ]
            ], 422);
        }

        // Check if user already exists with this email
        $existingUser = User::where('email', $email)->first();
        $existingUserByPhone = User::where('phone', $mobileNumber)->first();

        if($existingUser?->is_superadmin == 1 || $existingUserByPhone?->is_superadmin == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid access with others account.',
                'errors' => [
                    'general' => ['Invalid access with others account.']
                ]
            ], 500);
        }

        if ($existingUser) {
            if($existingUserByPhone && $existingUserByPhone->id != $existingUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number and email belongs to different users.',
                    'errors' => [
                        'email' => ['Phone number and email belongs to different users.']
                    ]
                ], 422);
            } else {
                // Clear OTP verification
                $this->otpService->clearOtpVerification($mobileNumber);

                $existingUser->phone = $mobileNumber;
                $existingUser->save();

                // Log in the user
                Auth::login($existingUser);

                if($isFromQuizPopup && $completedQuizId) {
                    $quiz = Quiz::where('id', $completedQuizId)->first();
                    if($quiz) {
                        $quiz->user_id = $existingUser->id;
                        $quiz->save();

                        if($existingUser->email) {
                            $this->sendAfterQuizEmail($existingUser->email);
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful! Welcome back.',
                    'user' => $existingUser,
                    'redirectUrl' => route('front.profile', ['id' => $existingUser->id]),
                    'action' => 'login'
                ]);
            }
        }

        // Generate a random password for the user
        $password = Hash::make(Str::random(16));

        try {
            // Create new user
            $userData = [
                'name' => $firstName,
                'first_name' => $firstName,
                'last_name' => '',
                'email' => $email,
                'phone' => $mobileNumber,
                'password' => $password,
                'free_user' => true, // Mark as free user
                'user_type' => $userType,
                'sport_game_id' => (int)$sportGameId == 0 ? null : (int)$sportGameId,
                'age_group' => $ageGroup,
            ];

            $user = User::create($userData);

            // Clear OTP verification
            $this->otpService->clearOtpVerification($mobileNumber);

            // Log in the user
            Auth::login($user);

            if($isFromQuizPopup && $completedQuizId) {
                $quiz = Quiz::where('id', $completedQuizId)->first();
                if($quiz) {
                    $quiz->user_id = $user->id;
                    $quiz->save();

                    if($user->email) {
                        $this->sendAfterQuizEmail($user->email);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully. You are now logged in.',
                'user' => $user,
                'redirectUrl' => route('front.profile', ['id' => $user->id])
            ]);

        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'mobile' => $mobileNumber,
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'errors' => [
                    'general' => ['An unexpected error occurred. Please try again.']
                ]
            ], 500);
        }
    }

    /**
     * Resend OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string'
        ], [
            'mobile_number.required' => 'Mobile number is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number is required.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mobileNumber = $request->input('mobile_number');

        try {
            $result = $this->otpService->sendOtp($mobileNumber);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'New OTP sent successfully to ' . $mobileNumber,
                    'debug_otp' => $result['debug_otp'] ?? null // Only in development
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to resend OTP. Please try again.',
                    'errors' => [
                        'mobile_number' => [$result['message'] ?? 'Failed to resend OTP. Please try again.']
                    ]
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Resend OTP failed', [
                'mobile' => $mobileNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again later.',
                'errors' => [
                    'mobile_number' => ['Service temporarily unavailable. Please try again later.']
                ]
            ], 500);
        }
    }

    /**
     * Get sport games and age groups for registration form
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSportGamesAndAgeGroups()
    {
        try {
            $sportGames = SportGame::select('id', 'name')->orderBy('name')->get();
            $ageGroups = AgeGroups::getAll();
            
            return response()->json([
                'success' => true,
                'sport_games' => $sportGames,
                'age_groups' => $ageGroups
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get sport games and age groups', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load sport games and age groups.'
            ], 500);
        }
    }

    /**
     * Debug OTP cache
     *
     * @param string $mobile
     * @return \Illuminate\Http\JsonResponse
     */
    public function debugCache(string $mobile)
    {
        $mobileNumber = '+' . $mobile; // Add + prefix if not present
        
        $otpInfo = $this->otpService->getOtpInfo($mobileNumber);
        
        return response()->json([
            'mobile_number' => $mobileNumber,
            'otp_info' => $otpInfo,
            'database_info' => [
                'connection' => config('database.default'),
                'driver' => config('database.connections.' . config('database.default') . '.driver')
            ]
        ]);
    }

    public function sendAfterQuizEmail($email) {
        try {
            mail($email, "After Quiz", "Thank you for completing the quiz.");
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send after quiz email', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}