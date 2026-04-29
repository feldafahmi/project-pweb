<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/produk', 'produk')->name('produk');

Route::get('/tentang-kami/profil-mentor', function () {
    return view('profil-mentor');
})->name('about.mentor');

Route::get('/info-lomba', function () {
    return view('info-lomba');
})->name('lomba');

Route::get('/dashboard/keranjang', function () {
    return view('dashboard.keranjang');
})->name('dashboard.cart');

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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/products');
    Route::view('/products', 'admin.products.index')->name('products.index');
    Route::view('/competitions', 'admin.competitions.index')->name('competitions.index');
    Route::view('/users', 'admin.users.index')->name('users.index');
});
