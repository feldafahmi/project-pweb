<?php

use Illuminate\Support\Facades\Route;

// Route::get('/laravel', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about'); 
})->name('about');

// Route::get('/blog', function () {
//     return view('blog');
// });

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/product', function () {
    return view('product');
});

// 1. Pastikan ada fungsi ->name('login')
Route::get('/login', function () {
    return view('login'); // sesuaikan dengan folder file kamu
})->name('login');

// 2. Lakukan hal yang sama untuk register agar tidak error nanti
Route::get('/register', function () {
    return view('register');
})->name('register');