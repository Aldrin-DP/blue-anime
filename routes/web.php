<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');
});

Route::inertia('/register', 'Auth/Register');
Route::inertia('/login', 'Auth/Login');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/color', function () {
    return inertia('Colors');
});

Route::get('/register/success', function () {
    return inertia('Auth/RegisterSuccess');
})->name('register.success');
