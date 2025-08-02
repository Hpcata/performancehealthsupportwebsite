# OTP Registration API Documentation

This document describes the new OTP-based registration flow endpoints.

## Overview

The OTP registration flow consists of 3 steps:
1. Send OTP to mobile number
2. Verify OTP
3. Complete registration with user details

## Database Storage

OTPs are now stored in the database using the `otp_tokens` table instead of cache for better reliability and persistence.

### Database Schema

```sql
CREATE TABLE otp_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mobile_number VARCHAR(255) NOT NULL,
    otp VARCHAR(10) NOT NULL, -- Increased to 10 to accommodate future needs
    is_verified BOOLEAN DEFAULT FALSE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_mobile_otp (mobile_number, otp),
    INDEX idx_mobile_verified (mobile_number, is_verified),
    INDEX idx_expires (expires_at)
);
```

## Endpoints

### 1. Send OTP
**POST** `/front/otp/send`

Sends a 6-digit OTP to the provided mobile number.

**Request Body:**
```json
{
    "mobile_number": "+61434708100"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "OTP sent successfully to your mobile number.",
    "mobile_number": "+61434708100",
    "otp_id": 123
}
```

**Response (Development Mode):**
```json
{
    "success": true,
    "message": "OTP sent successfully (development mode)",
    "debug_otp": "123456",
    "otp_id": 123
}
```

**Response (Error):**
```json
{
    "success": false,
    "message": "Please enter a valid mobile number in international format (e.g., +1234567890)."
}
```

### 2. Verify OTP
**POST** `/front/otp/verify`

Verifies the OTP sent to the mobile number.

**Request Body:**
```json
{
    "mobile_number": "+61434708100",
    "otp": "123456"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "OTP verified successfully.",
    "mobile_number": "+61434708100"
}
```

**Response (Error):**
```json
{
    "success": false,
    "message": "OTP has expired or is invalid. Please request a new one."
}
```

### 3. Complete Registration
**POST** `/front/otp/register`

Completes the registration process and logs the user in.

**Request Body:**
```json
{
    "mobile_number": "+61434708100",
    "first_name": "John",
    "email": "john@example.com"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Registration completed successfully. You are now logged in.",
    "user": {
        "id": 1,
        "name": "John",
        "first_name": "John",
        "last_name": "",
        "email": "john@example.com",
        "phone": "+61434708100",
        "free_user": true
    }
}
```

**Response (Error):**
```json
{
    "success": false,
    "message": "Please verify your mobile number with OTP first."
}
```

### 4. Resend OTP
**POST** `/front/otp/resend`

Resends OTP to the mobile number.

**Request Body:**
```json
{
    "mobile_number": "+61434708100"
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "OTP resent successfully to your mobile number.",
    "mobile_number": "+61434708100"
}
```

### 5. Debug OTP (Development Only)
**GET** `/front/otp/debug/{mobile}`

Get OTP information for debugging (remove in production).

**Response:**
```json
{
    "mobile_number": "+61434708100",
    "otp_info": {
        "mobile_number": "+61434708100",
        "valid_otps_count": 1,
        "valid_otps": [
            {
                "id": 123,
                "otp": "123456",
                "expires_at": "2025-01-27T10:30:00.000000Z",
                "is_expired": false
            }
        ],
        "verified_otps_count": 0,
        "verified_otps": []
    },
    "database_info": {
        "connection": "mysql",
        "driver": "mysql"
    }
}
```

## Flow Description

1. **Step 1**: User enters mobile number and clicks "Continue"
   - System validates mobile number format
   - System checks if user already exists with this mobile number
   - System invalidates any existing unverified OTPs for this mobile
   - System creates new OTP record in database with 5-minute expiry
   - System sends OTP via Twilio SMS (or shows in development mode)

2. **Step 2**: User enters OTP and clicks "Verify"
   - System validates OTP format (6 digits)
   - System queries database for valid, unexpired, unverified OTP
   - If valid, OTP is marked as verified (is_verified = true)
   - Verification status is valid for 10 minutes from OTP expiry

3. **Step 3**: User enters first name and email, clicks "Submit"
   - System validates all required fields
   - System checks if OTP is verified and not expired
   - System checks if email is unique
   - System creates user account with `free_user = true`
   - System automatically logs user in
   - System clears OTP verification status

## Important Notes

- Mobile numbers must be in international format (e.g., +61434708100)
- OTP is valid for 5 minutes
- OTP verification is valid for 10 minutes
- Users registered via this flow are marked as `free_user = true`
- No password is required from the user (system generates a random password)
- Users are automatically logged in after successful registration
- The existing registration/login flow remains unchanged
- OTPs are stored in database for better reliability and debugging
- Expired OTPs are automatically cleaned up

## Environment Variables Required

Add these to your `.env` file:
```
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_FROM_NUMBER=your_twilio_phone_number
```

## Installation Steps

1. Install Twilio package:
   ```bash
   composer require twilio/sdk
   ```

2. Run migrations to create required tables:
   ```bash
   php artisan migrate
   ```

3. Configure Twilio credentials in `.env` file

4. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Maintenance

### Cleanup Expired OTPs

Run this command periodically to clean up expired OTP tokens:

```bash
php artisan otp:cleanup
```

You can add this to your cron job:

```bash
# Add to crontab - runs every hour
0 * * * * cd /path/to/your/project && php artisan otp:cleanup
``` 