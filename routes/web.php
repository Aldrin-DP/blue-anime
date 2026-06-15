<?php

use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Pest\Plugins\Profile;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/anime/{id}', [AnimeController::class, 'show'])->name('anime.show');

Route::get('/explore', [ExploreController::class, 'index'])->name('explore.index');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::get('/register/success', [RegisterController::class, 'success'])->name('register.success');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:2,1');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile/account', [ProfileController::class, 'updateAccount']);
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword']);

    Route::post('/logout', [LogoutController::class, 'destroy']);
});
