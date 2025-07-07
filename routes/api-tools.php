<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/check-auth', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user'          => Auth::user(),
    ]);
});

Route::get('/fetch-alternate-measurement', function () {
    Artisan::call('nutrition:alternates');
    return response()->json([
        'status'  => 'success',
        'message' => 'Nutrition alternate measurements fetched successfully.',
    ]);
});
