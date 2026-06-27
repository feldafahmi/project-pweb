<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\ProductReview;
use App\Policies\CartItemPolicy;
use App\Policies\ProductReviewPolicy;
use Illuminate\Pagination\Paginator;
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

        // Pagination ber-brand. View bawaan Laravel ada di vendor/ yang tidak
        // dipindai Tailwind v4, jadi pakai view sendiri di resources/views.
        Paginator::defaultView('vendor.pagination.markup');
    }
}
