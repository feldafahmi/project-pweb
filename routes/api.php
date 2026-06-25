<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoint: /api/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\Api\CompetitionController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UploadController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'google']);

// Public endpoints — tidak perlu token
Route::get('/competitions', [CompetitionController::class, 'index']);
Route::get('/competitions/{id}', [CompetitionController::class, 'show']);
Route::get('products/{product}/reviews', [ReviewController::class, 'index']);

// Webhook Midtrans — dipanggil server-to-server (tanpa token), diamankan via signature.
Route::post('/midtrans/notification', [TransactionController::class, 'notification']);

// Media proxy: menyajikan file dari /public lewat Laravel agar dapat header
// CORS (HandleCors otomatis untuk path api/*). Diperlukan oleh Flutter Web
// karena `php artisan serve` melayani file statis tanpa lewat middleware.
Route::get('/media/{path}', function (string $path) {
    $base = realpath(public_path());
    $full = realpath(public_path($path));
    // Cegah path traversal: hanya boleh file yang berada di dalam /public.
    abort_unless(
        $full !== false && $base !== false
            && str_starts_with($full, $base) && is_file($full),
        404
    );
    return response()->file($full);
})->where('path', '.*');

// Route terproteksi (harus membawa token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profil user yang sedang login
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}/content', [ProductController::class, 'content']);
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

    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
    Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'pay']);
    Route::post('/transactions/{transaction}/sync-status', [TransactionController::class, 'syncStatus']);
    Route::get('/my-products', [TransactionController::class, 'myProductIds']);
    Route::get('/my-learning', [TransactionController::class, 'myProducts']);

    // ── Admin only (role === 'admin') ────────────────────────────────────
    // Hanya endpoint tulis; pembacaan tetap lewat route di atas.
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::apiResource('products', ProductController::class)
            ->only(['store', 'update', 'destroy']);
        Route::apiResource('mentors', MentorController::class)
            ->only(['store', 'update', 'destroy']);
        Route::apiResource('competitions', CompetitionController::class)
            ->only(['store', 'update', 'destroy']);
        Route::post('uploads', [UploadController::class, 'store']);
    });
});