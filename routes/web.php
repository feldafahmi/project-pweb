<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/produk', 'produk')->name('produk');

Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register');
