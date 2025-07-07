<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are now organized into separate files for clarity.
|
*/

// Auth Routes
require __DIR__ . '/auth.php';

// Frontend Routes
require __DIR__ . '/front.php';

// Admin Routes
require __DIR__ . '/admin.php';

// API Routes
require __DIR__ . '/api-tools.php';

// AI Routes
require __DIR__ . '/ai.php';