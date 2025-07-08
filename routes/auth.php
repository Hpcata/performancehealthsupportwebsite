<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::as('admin.auth.')
    ->controller(LoginController::class)
    ->group(function () {

        // Login / Logout
        Route::get('/login', 'index')->name('login.index');
        Route::post('/login', 'login')->name('login.submit');
        Route::match(['get', 'post'], '/logout', 'logout')->name('logout');

        // Register
        Route::get('/register', 'register')->name('register.index');
        Route::post('/register', 'registerPost')->name('register.submit');

        // Forgot & Reset Password
        Route::get('/forgot-password', 'forgotPassword')->name('forgot.index');
        Route::post('/forgot-password', 'forgotPasswordPost')->name('forgot.submit');
        Route::get('/reset-password/{token}', 'resetPassword')->name('reset.index');
        Route::post('/reset-password', 'resetPasswordPost')->name('reset.submit');

        // Change Password
        Route::get('/change-password', 'changePassword')->name('change.index');
        Route::post('/change-password', 'changePasswordPost')->name('change.submit');
    });
