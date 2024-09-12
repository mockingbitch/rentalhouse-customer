<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/time-out', function () {
    return view('timeout');
})->name('timeout');
Route::get('change-language/{language}', [HomeController::class, 'changeLanguage'])->name('user.change-language');
Route::get('test', function () {
    return view('test');
});
Route::get('/not-found', function () {
    return view('404');
})->name('404');
