<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\ProductReview;
use App\Policies\CartItemPolicy;
use App\Policies\ProductReviewPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(CartItem::class, CartItemPolicy::class);
        Gate::policy(ProductReview::class, ProductReviewPolicy::class);
    }
}
