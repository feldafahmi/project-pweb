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

Route::get('/login', function () {
    return view('login'); 
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/produk', function () {
    return view('produk'); 
})->name('produk');