<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/objectives', [HomeController::class, 'objectives'])->name('objectives');
Route::get('/appeal', [HomeController::class, 'appeal'])->name('appeal');
Route::get('/science', [HomeController::class, 'science'])->name('science');
Route::get('/marathi-medium', [HomeController::class, 'marathiMedium'])->name('marathi-medium');
Route::get('/neet-sample-papers', [HomeController::class, 'neetSamplePapers'])->name('neet-sample-papers');
Route::get('/suggest-word', [HomeController::class, 'suggestWord'])->name('suggest-word');
Route::post('/suggest-word', [HomeController::class, 'submitWord'])->name('suggest-word.submit');

// Category Routes
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category');

// Search Route
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Authentication Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Google Authentication Routes
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
Route::post('/auth/google/one-tap', [App\Http\Controllers\Auth\GoogleController::class, 'handleOneTapCallback'])->name('google.one-tap');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});
