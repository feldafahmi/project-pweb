<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\MentorDashboardController;

// Public Views
Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::get('/produk', [ProductController::class, 'index'])->name('produk');

Route::get('/tentang-kami/profil-mentor', function () {
    return view('profil-mentor');
})->name('about.mentor');

Route::get('/info-lomba', [CompetitionController::class, 'publicIndex'])->name('lomba');

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
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::view('/transactions', 'dashboard.transactions')->name('transactions');

        // Milestone Tracker API
        Route::get('/milestones', [MilestoneController::class, 'index'])->name('milestones.index');
        Route::post('/milestones', [MilestoneController::class, 'store'])->name('milestones.store');
        Route::put('/milestones/{id}/toggle', [MilestoneController::class, 'toggle'])->name('milestones.toggle');
        Route::delete('/milestones/{id}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');
        
        Route::get('/keranjang', function () {
            return view('dashboard.keranjang');
        })->name('cart');

        Route::get('/checkout', function () {
            return view('dashboard.checkout'); 
        })->name('checkout');

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::get('/password', [ProfileController::class, 'passwordIndex'])->name('password');
            Route::post('/password', [ProfileController::class, 'passwordUpdate'])->name('password.update');
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

        // Mentees and Feedback
        Route::get('/mentees', [MentorDashboardController::class, 'mentees'])->name('mentees');
        Route::post('/milestones/{id}/feedback', [MentorDashboardController::class, 'giveFeedback'])->name('milestones.feedback');
    });
});
