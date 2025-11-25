<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! auth()->check()) {
            $loginPath = '/'.$role.'/login';

            return redirect()->to(url($loginPath));
        }

        if (auth()->user()->role !== $role) {
            $targetPanel = '/'.auth()->user()->role;

            return redirect()->to(url($targetPanel))->with('error', 'Akses ditolak. Anda dialihkan ke panel yang sesuai.');
        }

        if (! auth()->user()->is_active) {
            auth()->logout();
            $loginPath = '/'.$role.'/login';

            return redirect()->to(url($loginPath))->with('error', 'Akun Anda tidak aktif.');
        }

        return $next($request);
    }
}
