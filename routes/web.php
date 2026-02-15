<?php

use Illuminate\Support\Facades\Route;

Route::get('/laravel', function () {
    return view('welcome');
});


Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});


