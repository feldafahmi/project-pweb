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
});

// Route::get('/blog', function () {
//     return view('blog');
// });

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/product', function () {
    return view('product');
});

Route::get('/login-page', function () {
    return view('login-page');
});

Route::get('/sign-in-page', function () {
    return view('sign-in-page');
});