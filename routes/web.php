<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/produk', 'produk')->name('produk');

Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::view('/', 'dashboard.index')->name('index');
    Route::view('/transactions', 'dashboard.transactions')->name('transactions');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::view('/', 'dashboard.profile.index')->name('index');
        Route::view('/password', 'dashboard.profile.password')->name('password');
    });
});
