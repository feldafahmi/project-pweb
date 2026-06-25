<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pakai $request->user() agar berfungsi di guard web (session) maupun
        // sanctum (API token) — auth()->user() hanya membaca guard default.
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['message' => 'Unauthorized. Admin role required.'], 403);
        }

        return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses admin.');
    }
}
