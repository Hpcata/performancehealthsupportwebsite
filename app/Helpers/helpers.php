<?php

// use Route;

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
	return asset('private/public/backend/' . $path);
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
	$asset = config('constant.ENVIRONMENT') == 'production' ? 'private/public/front/' . $path : 'private/public/front/' . $path;
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