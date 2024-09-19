<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/not-found', function () {
    return view('404');
})->name('404');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/time-out', function () {
    return view('timeout');
})->name('timeout');
Route::get('change-language/{language}', [HomeController::class, 'changeLanguage'])->name('user.change-language');
Route::get('test', function () {
    return view('test');
});

//Authentication
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
