<?php

use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WatchController;
use App\Http\Controllers\WatchHistoryController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/anime/search', [SearchController::class, 'search']);
Route::get('/anime/{id}', [AnimeController::class, 'show'])->name('anime.show');

Route::get('/anime/{id}/episodes/{episode}', [WatchController::class, 'show']);



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

    Route::get('/watchlists', [WatchlistController::class, 'index']);
    Route::post('/watchlists', [WatchlistController::class, 'store']);
    Route::patch('/watchlists/{anilistId}', [WatchlistController::class, 'patch']);
    Route::patch('/watchlists/{anilistId}/favorite', [WatchlistController::class, 'toggleFavorite']);
    Route::delete('/watchlists/{id}', [WatchlistController::class, 'destroy']);
    

    Route::post('/watch-histories/{animeId}/{episode}', [WatchHistoryController::class, 'save']);

    Route::patch('/watch-histories/{animeId}/{episode}', [WatchHistoryController::class, 'update']);
    Route::patch('/watch-histories/{id}', [WatchHistoryController::class, 'hide']);

    
});
