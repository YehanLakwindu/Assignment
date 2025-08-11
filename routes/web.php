<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return redirect('/weather');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth', [AuthController::class, 'authenticate'])->name('auth');
Route::get('/callback', [AuthController::class, 'callback'])->name('callback');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth0')->group(function () {
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather');
});
