<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\UserController;

// Public Views
Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::get('/produk', [ProductController::class, 'index'])->name('produk');

Route::get('/tentang-kami/profil-mentor', function () {
    return view('profil-mentor');
})->name('about.mentor');

Route::get('/info-lomba', function () {
    return view('info-lomba');
})->name('lomba');

// Public Guest Auth Views & Submissions
Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::view('/register', 'register')->name('register');
    
    Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');
    Route::post('/register', [AuthController::class, 'webRegister'])->name('register.post');
});

// Protected Session Auth Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

    // Dashboard (User/Student Area)
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::view('/', 'dashboard.index')->name('index');
        Route::view('/transactions', 'dashboard.transactions')->name('transactions');
        
        Route::get('/keranjang', function () {
            return view('dashboard.keranjang');
        })->name('cart');

        Route::get('/checkout', function () {
            return view('dashboard.checkout'); 
        })->name('checkout');

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::view('/', 'dashboard.profile.index')->name('index');
            Route::view('/password', 'dashboard.profile.password')->name('password');
        });
    });

    // Admin Group (Only Admin role)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::redirect('/', '/admin/products');
        
        // Products CRUD
        Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Competitions CRUD
        Route::get('/competitions', [CompetitionController::class, 'adminIndex'])->name('competitions.index');
        Route::post('/competitions', [CompetitionController::class, 'store'])->name('competitions.store');
        Route::put('/competitions/{id}', [CompetitionController::class, 'update'])->name('competitions.update');
        Route::delete('/competitions/{id}', [CompetitionController::class, 'destroy'])->name('competitions.destroy');

        // Users Management
        Route::get('/users', [UserController::class, 'adminIndex'])->name('users.index');
        Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    });

    // Mentor Group (Admin and Mentor roles)
    Route::middleware('mentor')->prefix('mentor')->name('mentor.')->group(function () {
        Route::redirect('/', '/mentor/products');

        // Products CRUD (shared)
        Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Competitions CRUD (shared)
        Route::get('/competitions', [CompetitionController::class, 'adminIndex'])->name('competitions.index');
        Route::post('/competitions', [CompetitionController::class, 'store'])->name('competitions.store');
        Route::put('/competitions/{id}', [CompetitionController::class, 'update'])->name('competitions.update');
        Route::delete('/competitions/{id}', [CompetitionController::class, 'destroy'])->name('competitions.destroy');
    });
});
