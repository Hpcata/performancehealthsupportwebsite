<?php

use Illuminate\Support\Facades\Storage;

/**
 * =======================
 * FRONTEND ASSETS
 * =======================
 */
function frontAssets($path)
{
    return asset("front/{$path}");
}

/**
 * =======================
 * BACKEND/ADMIN ASSETS
 * =======================
 */
function backendAssets($path)
{
    return asset("backend/{$path}");
}

function adminAssets($path)
{
    return asset("admin/{$path}");
}

/**
 * =======================
 * GENERAL ASSETS
 * =======================
 */
function webAssets($path)
{
    return asset($path);
}

/**
 * =======================
 * VIEW HELPERS
 * =======================
 */
function backendView($key)
{
    return "backend.{$key}";
}

function adminView($key)
{
    return "admin.{$key}";
}

/**
 * =======================
 * ROUTE HELPERS
 * =======================
 */
function backendRoute($key)
{
    return "backend.{$key}";
}

function backendRoutePut($key, $args = [])
{
    return routePut(backendRoute($key), $args);
}

/**
 * =======================
 * IMAGE HELPERS
 * =======================
 */

// For images stored via Laravel filesystem in storage/app/public
function storageImageUrl($relativePath)
{
    return Storage::url($relativePath);
}

/**
 * =======================
 *  ASSET HELPERS
 * =======================
 */

//  For images stored via Laravel filesystem in public/uploads
function uploadAssets($path)
{
    return asset(ltrim($path, '/'));
}