<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        $path = trim($request->path(), '/');

        if (Str::startsWith($path, 'direktur')) {
            return url('/direktur/login');
        }

        if (Str::startsWith($path, 'bidang')) {
            return url('/bidang/login');
        }

        return url('/');
    }
}
