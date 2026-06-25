<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Tolak request bila user yang terautentikasi bukan admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user()?->role === 'admin',
            403,
            'Hanya admin yang boleh mengakses resource ini.'
        );

        return $next($request);
    }
}
