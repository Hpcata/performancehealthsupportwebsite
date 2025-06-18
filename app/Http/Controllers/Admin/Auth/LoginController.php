<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\BookingConfiguration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Validation\ValidatesRequests;

class LoginController extends Controller
{

    use ValidatesRequests;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::ADMIN_DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        // Only redirect if user is already authenticated as admin
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->is_superadmin == 1) {
            return redirect()->route('admin.purchase-plans.index');
        }
        return view('backend.pages.auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = User::where('email', $request->get('email'))->first();
        
        if (!$admin) {
            return redirect()->route('index')->with('error', 'Invalid credentials.');
        }

        if (!$admin->is_superadmin) {
            return redirect()->route('index')->with('error', 'You do not have authorization to access this system.');
        }

        $rememberMe = $request->has('remember_me');

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $rememberMe)) {
            $request->session()->regenerate();
            return redirect()->route('admin.purchase-plans.index')->with('success', 'You are logged in successfully.');
        }

        return back()->with('error', 'Invalid credentials.');
    }

    public function register()
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->is_superadmin == 1) {
            return redirect()->route('admin.purchase-plans.index');
        }
        return view('backend.pages.auth.register');
    }

    /**
     * Register a new admin user.
     *
     * @param Request $request The request instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerPost(Request $request)
    {
        // Validate the registration request data.
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'], // First name of the admin user.
            'last_name' => ['required', 'string', 'max:255'], // Last name of the admin user.
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Email of the admin user.
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Password of the admin user.
            'password_confirmation' => ['required'], // Confirmation of the password.
        ]);

        // Create a new admin user.
        $user = User::create([
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'), // Full name of the admin user.
            'first_name' => $request->input('first_name'), // First name of the admin user.
            'last_name' => $request->input('last_name'), // Last name of the admin user.
            'email' => $request->input('email'), // Email of the admin user.
            'password' => Hash::make($request->input('password')), // Hashed password of the admin user.
            'is_superadmin' => 1, // Set as super admin
        ]);

        // Log in the new admin user
        Auth::guard('admin')->login($user);

        return redirect()->route('admin.purchase-plans.index')->with('success', 'Registration successful. You are now logged in.');
    }

    /**
     * Log out the admin user.
     *
     * @param Request $request The request instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Only logout from admin guard
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            
            // Invalidate only the admin session
            $request->session()->forget('admin');
            
            // Regenerate CSRF token
            $request->session()->regenerateToken();

            return redirect()->route('index')->with('success', 'You have been logged out successfully.');
        }

        return redirect()->route('index')->with('error', 'Unauthorized access.');
    }

    /**
     * Show the forgot password form for the admin user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forgotPassword()
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->is_superadmin == 1) {
            return redirect()->route('admin.purchase-plans.index');
        }
        return view('backend.pages.auth.forgot-password');
    }

    /**
     * Handle the post request for forgot password.
     *
     * @param Request $request The request instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgotPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users', // Validate the email input.
        ]);

        $token = Str::random(64); // Generate a random token for password reset.

        PasswordReset::insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(), // Insert a new password reset record.
        ]);

        Mail::send("backend.pages.auth.reset-password", ['token' => $token], function ($message) use ($request) {
            $message->to($request->email); // Send the reset password email to the user.
            $message->subject("Reset Password"); // Set the email subject.
        });

        return back()->with('success', "Email send for reset password."); // Redirect back with success message.
    }

    /**
     * Show the reset password form for the admin user.
     *
     * @param string $token The password reset token.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetPassword($token)
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->is_superadmin == 1) {
            return redirect()->route('admin.purchase-plans.index');
        }
        return view('backend.pages.auth.new-password', compact('token'));
    }

    /**
     * Handle the post request for resetting the admin user's password.
     *
     * @param Request $request The request instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPasswordPost(Request $request)
    {
        // Validate the request data.
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        // Find the password reset record.
        $updatePassword = PasswordReset::where([
            'email' => $request->email,
            'token' => $request->token,
        ])->first();

        // If the password reset record does not exist, redirect back with an error message.
        if (!$updatePassword) {
            return redirect()->to(route("reset-password"))->with('error', 'Invalid!!');
        }

        // Update the admin user's password.
        User::where("email", $request->email)->update(["password" => Hash::make($request->password)]);

        // Delete the password reset record.
        PasswordReset::where(["email" => $request->email])->delete();

        // Redirect to the admin login page with a success message.
        return redirect()->to(route('login'))->with("success", "Password reset successfully.");
    }

    /**
     * Show the change password form for the authenticated admin user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('index');
        }
        return view('backend.pages.auth.change-password');
    }

    /**
     * Change the password of the authenticated admin user.
     *
     * @param Request $request The request instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePasswordPost(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $currentAdminUser = Auth::guard('admin')->user();

        if (Hash::check($request->current_password, $currentAdminUser->password)) {
            User::where('id', $currentAdminUser->id)->update(['password' => Hash::make($request->new_password)]);
            return redirect()->route('dashboard')->with('success', 'Password changed successfully.');
        } else {
            return back()->with('error', 'Current password not matched.');
        }
    }

    public function profile(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('index');
        }
        
        $adminUser = [];
        if ($request->id) {
            $adminUser = User::find($request->id);
        }
        $bookingConfiguration = null;
        return view('backend.pages.admin-profile', compact('adminUser', 'bookingConfiguration'));
    }

    public function profilePost(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('index');
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'email_signature' => ['required'],
            'description_character_count' => ['required'],
            'about_us_title' => ['required'],
            'about_us_description' => ['required'],
            'front_title' => ['required'],
            'front_description' => ['required'],
            'copyright_text' => ['required'],
            'qualification_text' => ['required'],
        ]);

        $adminUser = User::findOrFail($request->id);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/profile_images/' . $fileName;
            $file->move(public_path('uploads/profile_images'), $fileName);
        
            if ($adminUser->profile_image && file_exists(public_path($adminUser->profile_image))) {
                unlink(public_path($adminUser->profile_image));
            }
        
            $adminUser->profile_image = $filePath;
        }

        if ($request->hasFile('front_logo')) {
            $file = $request->file('front_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/front_logo/' . $fileName;
            $file->move(public_path('uploads/front_logo'), $fileName);
        
            if ($adminUser->front_logo && file_exists(public_path($adminUser->front_logo))) {
                unlink(public_path($adminUser->front_logo));
            }
        
            $adminUser->front_logo = $filePath;
        }

        if ($request->hasFile('about_us_image')) {
            $file = $request->file('about_us_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/about_us_image/' . $fileName;
            $file->move(public_path('uploads/about_us_image'), $fileName);
        
            if ($adminUser->about_us_image && file_exists(public_path($adminUser->about_us_image))) {
                unlink(public_path($adminUser->about_us_image));
            }
        
            $adminUser->about_us_image = $filePath;
        }
        
        $adminUser->update([
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'designation' => $request->input('designation'),
            'business_name' => $request->input('business_name'),
            'email_signature' => $request->input('email_signature'),
            'description_character_count'  => $request->input('description_character_count'),
            'about_us_title' => $request->input('about_us_title'),
            'about_us_description' => $request->input('about_us_description'),
            'front_title' => $request->input('front_title'),
            'front_description' => $request->input('front_description'),
            'copyright_text'  => $request->input('copyright_text'),
            'qualification_text' => $request->input('qualification_text'),
            'profile_image' => $adminUser->profile_image,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    
    public function removeProfileImage($id)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('index');
        }

        $adminUser = User::findOrFail($id);
        if ($adminUser->profile_image && file_exists(public_path($adminUser->profile_image))) {
            unlink(public_path($adminUser->profile_image));
        }
        $adminUser->update([
            'profile_image' => null
        ]);
        return redirect()->back()->with('success', 'Profile image removed successfully.');
    }

    public function removeFrontLogo($id)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('index');
        }

        $adminUser = User::findOrFail($id);
        if ($adminUser->front_logo && file_exists(public_path($adminUser->front_logo))) {
            unlink(public_path($adminUser->front_logo));
        }
        $adminUser->update([
            'front_logo' => null
        ]);
        return redirect()->back()->with('success', 'Front Logo removed successfully.');
    }

    public function removeAboutUsImage($id)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('index');
        }

        $adminUser = User::findOrFail($id);
        if ($adminUser->about_us_image && file_exists(public_path($adminUser->about_us_image))) {
            unlink(public_path($adminUser->about_us_image));
        }
        $adminUser->update([
            'about_us_image' => null
        ]);
        return redirect()->back()->with('success', 'About Us Image removed successfully.');
    }

    public function dashboard() {
        return redirect()->route('admin.purchase-plans.index');
    }
}
