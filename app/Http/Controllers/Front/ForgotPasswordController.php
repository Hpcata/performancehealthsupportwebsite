<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Exception;
use Hash;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{

    public function sendResetLinkEmail(Request $request)
    {
        // Validate the request, the email is required, must be a valid email, and must exist in the users table.
        $validator = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Generate a random token.
        $token = Str::random(64);

        // Check if the user already has a reset token
        PasswordReset::where('email', $request->email)->delete(); // Delete any existing record for the user
        
        // Store the token and email in the password_resets table
        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send an email to the user with the reset password link.
        try {
            Mail::send("front.emails.forgot_password", ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password Notification');
            });

            return response()->json(['success' => true, 'message' => 'We have sent you an email with a link to reset your password.']);

        } catch (Exception $e) {
            Log::error('Error sending password reset email: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send reset password email. Please try again later.'], 500);

        }

    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param \Illuminate\Http\Request $request The request instance.
     * @param string|null $token The password reset token.
     * @return \Illuminate\View\View The password reset view.
     */
    public function showResetForm(Request $request, $token = null)
    {
        // Prepare the view data.
        $viewData = [
            'token' => $token,
            'email' => $request->email,
        ];

        // Return the view with the prepared data.
        return view('front.auth.reset_password')->with($viewData);
    }

    /**
     * Reset the user's password.
     *
     * @param Request $request The request instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        // Validate the request data.
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);
        // dd($request->all());
        // Get the password reset token for the user.
        $passwordReset = PasswordReset::where('email', $request->email)->first();

        // Check if the password reset token is valid.
        if (!$passwordReset || $passwordReset->token !== $request->token) {
            return response()->json(['success' => false, 'message' => 'Invalid password reset token.'], 400);
        }

        // Delete the password reset token.
        $passwordReset->delete();

        // Update the user's password.
        User::where("email", $request->email)->update(["password" => Hash::make($request->password)]);

        // Redirect to the home page with a success message.
        return response()->json(['success' => true, 'message' => 'Your password has been reset successfully.']);
    }

}