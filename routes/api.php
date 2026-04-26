<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\VideoController;

// Endpoint: /api/

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/debug-db', [DebugController::class, 'checkDb']);

// Route::get('/mentor', [DebugController::class, 'checkMentor']);

// Route::apiResource('videos', VideoController::class);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public endpoints — tidak perlu token
Route::get('/competitions', [CompetitionController::class, 'index']);
Route::get('/competitions/{id}', [CompetitionController::class, 'show']);
Route::get('products/{product}/reviews', [ReviewController::class, 'index']);

// Route terproteksi (harus membawa token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::get('/mentors', [MentorController::class, 'index']);
    Route::get('/mentors/{mentor}', [MentorController::class, 'show']);

    // Reviews — write endpoints (read is public, lihat di atas)
    Route::post('products/{product}/reviews', [ReviewController::class, 'store']);
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy']);

    // cart/clear HARUS sebelum apiResource agar "clear" tidak di-resolve sebagai {cartItem}
    Route::delete('cart/clear', [CartItemController::class, 'clear']);
    Route::apiResource('cart', CartItemController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['cart' => 'cartItem']);
});