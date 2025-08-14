<?php

// use Route;
use Carbon\Carbon;
use App\Models\SportGame;
use App\Constants\AgeGroups;
use Illuminate\Support\Facades\Auth;

// APP FUNCTIONS
function appName() {
	return env('APP_NAME');
}

// ROUTE FUNCTIONS
function routePut($name, $args = []) {
	return $name && \Route::has($name) ? route($name, $args) : '#';
}
function routeCurrentName() {
	return \Route::getCurrentRoute()->getName();
}
function routeIsActive($name, $activeClass = "active") {
	return routeCurrentName() == $name ? $activeClass : '';
}

// BACKEND FUNCTIONS
function backendAssets($path) {
	return asset('backend/' . $path);
}
function backendView($key) {
	return 'backend.' . $key;
}
function backendRoute($key) {
	return 'backend.' . $key;
}
function backendRoutePut($key, $args = []) {
	return routePut(backendRoute($key), $args);
}

function frontAssets($path) {
	$asset = config('constant.ENVIRONMENT') == 'production' ? 'front/' . $path : 'front/' . $path;
	return asset($asset);
}

function webAssets($path) {
	$asset = config('constant.ENVIRONMENT') == 'production' ? '' . $path : 'public/' . $path;
	return asset($asset);
}

function adminAssets($path) {
	$asset = config('constant.ENVIRONMENT') == 'production' ? 'public/admin/' . $path : 'admin/' . $path;
	return asset($asset);
}

function adminView($key) {
	return 'admin.' . $key;
}

function getUserBySlug($slug) {
	return \App\Models\User::where('slug', $slug)->first();
}

function frontView($key) {
	return 'front.' . $key;
}

if (!function_exists('formatDate')) {
    /**
     * Format a date to d-m-Y format.
     *
     * @param string|null $date
     * @return string
     */
    function formatDate($date)
    {
        if (!$date) {
            return '';
        }

        try {
            return Carbon::parse($date)->format('d-m-Y');
        } catch (\Exception $e) {
            return ''; // Return empty if date parsing fails
        }
    }
}

function formatDecimal($value) {
    // Remove known unwanted text
    $cleanedValue = str_replace(['Approx.', '<', 'g'], '', $value);

    // Convert to float
    $numericValue = is_numeric($cleanedValue) ? (float) $cleanedValue : 0;

    // If value is "<1", assume 0.9 (or change as needed)
    if (strpos($value, '<') !== false) {
        return $numericValue > 0 ? $numericValue : 0.9;
    }

    return $numericValue;
}

// Check if the value is a decimal
function isDecimal($value) {
    return is_float($value) || (is_numeric($value) && strpos($value, '.') !== false);
}

function cleanDecimal($value)
{
    // Remove all non-digit/non-dot characters (keep only digits and dot)
    $cleaned = preg_replace('/[^0-9.]/', '', $value);

    // Fix multiple dots (e.g., '0.330.' -> '0.330')
    // Remove extra trailing dots
    $cleaned = rtrim($cleaned, '.');

    // If there are still multiple dots, keep only the first one
    $parts = explode('.', $cleaned, 3); // allow max 2 parts
    if (count($parts) > 2) {
        $cleaned = $parts[0] . '.' . $parts[1];
    }

    return is_numeric($cleaned) ? (float) $cleaned : 0;
}

function getAdminProfileImage()
{
    $admin = Auth::guard('admin')->user();

    if ($admin && !empty($admin->profile_image)) {
        return asset($admin->profile_image);
    }

    // Default fallback image
    return 'https://booking.biohealthpassport.com.au/public/admin/dist/assets/images/profile_av.svg';
}

function scaleNutritionValue($value, $num_servings)
{
    // Check if value contains '<'
    $isLessThan = false;
    if (strpos($value, '<') !== false) {
        $isLessThan = true;
        $value      = str_replace('<', '', $value);
    }

    // Extract numeric part and unit using regex
    preg_match('/([\d\.]+)\s*([a-zA-Z]*)/', trim($value), $matches);

    $number = isset($matches[1]) ? (float) $matches[1] : 0;
    $unit   = isset($matches[2]) ? $matches[2] : '';

    $scaled = $number * $num_servings;

    // Format result with the unit and handle '<' again
    if ($isLessThan) {
        return '< ' . $scaled . $unit;
    } else {
        return $scaled . $unit;
    }
}

// Get age groups from constants
function getAgeGroups()
{
    return AgeGroups::getAll();
}

// Get all sports as an alphabetically sorted array
function getSports()
{
    return SportGame::orderBy('name', 'asc')->get();
}
