<?php

namespace App\Services;

use App\Models\OtpToken;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class OtpService
{
    protected $twilioClient;
    protected $fromNumber;

    public function __construct()
    {
        $this->twilioClient = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $this->fromNumber = config('services.twilio.from');
    }

    /**
     * Generate a 6-digit OTP
     *
     * @return string
     */
    public function generateOtp(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP to the given mobile number
     *
     * @param string $mobileNumber
     * @return array
     */
    public function sendOtp(string $mobileNumber): array
    {
        try {
            // Generate OTP
            $otp = $this->generateOtp();
            
            // Invalidate any existing OTPs for this mobile number
            OtpToken::where('mobile_number', $mobileNumber)
                   ->where('is_verified', false)
                   ->update(['is_verified' => true]);
            
            // Store OTP in database for 5 minutes
            $otpToken = OtpToken::create([
                'mobile_number' => $mobileNumber,
                'otp' => $otp,
                'is_verified' => false,
                'expires_at' => now()->addMinutes(5)
            ]);
            
            // Log OTP storage
            Log::info('OTP stored in database', [
                'mobile' => $mobileNumber,
                'otp_id' => $otpToken->id,
                'expires_at' => $otpToken->expires_at
            ]);
            
            // For development/testing, skip Twilio if not configured
            if (!config('services.twilio.sid') || !config('services.twilio.token')) {
                Log::info('Twilio not configured, skipping SMS send', [
                    'mobile' => $mobileNumber,
                    'otp' => $otp,
                    'otp_id' => $otpToken->id
                ]);
                
                return [
                    'success' => true,
                    'message' => 'OTP sent successfully (development mode)',
                    'debug_otp' => $otp, // Only for development
                    'otp_id' => $otpToken->id
                ];
            }
            
            // Send SMS via Twilio
            $message = $this->twilioClient->messages->create(
                $mobileNumber,
                [
                    'from' => $this->fromNumber,
                    'body' => "Your OTP for registration is: {$otp}. Valid for 5 minutes."
                ]
            );

            Log::info('OTP sent successfully', [
                'mobile' => $mobileNumber,
                'message_sid' => $message->sid,
                'otp_id' => $otpToken->id
            ]);

            return [
                'success' => true,
                'message' => 'OTP sent successfully',
                'message_sid' => $message->sid,
                'otp_id' => $otpToken->id
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send OTP', [
                'mobile' => $mobileNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify OTP for the given mobile number
     *
     * @param string $mobileNumber
     * @param string $otp
     * @return array
     */
    public function verifyOtp(string $mobileNumber, string $otp): array
    {
        // Find the valid OTP token
        $otpToken = OtpToken::where('mobile_number', $mobileNumber)
                           ->where('otp', $otp)
                           ->where('is_verified', false)
                           ->where('expires_at', '>', now())
                           ->first();
        
        // Log verification attempt
        Log::info('OTP verification attempt', [
            'mobile' => $mobileNumber,
            'provided_otp' => $otp,
            'otp_found' => $otpToken ? true : false,
            'otp_id' => $otpToken ? $otpToken->id : null
        ]);

        if (!$otpToken) {
            Log::warning('OTP not found or expired', [
                'mobile' => $mobileNumber,
                'provided_otp' => $otp
            ]);
            
            return [
                'success' => false,
                'message' => 'OTP has expired or is invalid. Please request a new one.',
                'debug' => 'OTP not found or expired'
            ];
        }

        // Mark OTP as verified
        $otpToken->markAsVerified();
        
        Log::info('OTP verified successfully', [
            'mobile' => $mobileNumber,
            'otp_id' => $otpToken->id
        ]);

        return [
            'success' => true,
            'message' => 'OTP verified successfully'
        ];
    }

    /**
     * Check if OTP is verified for the given mobile number
     *
     * @param string $mobileNumber
     * @return bool
     */
    public function isOtpVerified(string $mobileNumber): bool
    {
        return OtpToken::where('mobile_number', $mobileNumber)
                      ->where('is_verified', true)
                      ->where('expires_at', '>', now()->subMinutes(10)) // Check if verified within last 10 minutes
                      ->exists();
    }

    /**
     * Clear OTP verification for the given mobile number
     *
     * @param string $mobileNumber
     * @return void
     */
    public function clearOtpVerification(string $mobileNumber): void
    {
        OtpToken::where('mobile_number', $mobileNumber)
               ->where('is_verified', true)
               ->update(['is_verified' => false]);
    }

    /**
     * Validate mobile number format
     *
     * @param string $mobileNumber
     * @return bool
     */
    public function validateMobileNumber(string $mobileNumber): bool
    {
        // Basic validation for international format
        // You can customize this based on your requirements
        return preg_match('/^\+[1-9]\d{1,14}$/', $mobileNumber);
    }

    /**
     * Get OTP information for debugging
     *
     * @param string $mobileNumber
     * @return array
     */
    public function getOtpInfo(string $mobileNumber): array
    {
        $validOtps = OtpToken::where('mobile_number', $mobileNumber)
                            ->where('is_verified', false)
                            ->where('expires_at', '>', now())
                            ->get();
        
        $verifiedOtps = OtpToken::where('mobile_number', $mobileNumber)
                               ->where('is_verified', true)
                               ->where('expires_at', '>', now()->subMinutes(10))
                               ->get();
        
        return [
            'mobile_number' => $mobileNumber,
            'valid_otps_count' => $validOtps->count(),
            'valid_otps' => $validOtps->map(function($otp) {
                return [
                    'id' => $otp->id,
                    'otp' => $otp->otp,
                    'expires_at' => $otp->expires_at,
                    'is_expired' => $otp->isExpired()
                ];
            }),
            'verified_otps_count' => $verifiedOtps->count(),
            'verified_otps' => $verifiedOtps->map(function($otp) {
                return [
                    'id' => $otp->id,
                    'otp' => $otp->otp,
                    'expires_at' => $otp->expires_at,
                    'is_expired' => $otp->isExpired()
                ];
            })
        ];
    }

    /**
     * Clean up expired OTPs
     *
     * @return int
     */
    public function cleanupExpiredOtps(): int
    {
        return OtpToken::where('expires_at', '<', now())->delete();
    }
} 