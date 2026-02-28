<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response // <<< Perubahan ada di baris ini
    {
        // Jika user belum login, redirect ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Cek apakah role user ada di daftar roles yang diizinkan
        if (!in_array($user->role, $roles)) {
            // Jika tidak, redirect ke halaman sebelumnya dengan pesan error,
            // atau ke halaman unauthorized
            abort(403, 'Unauthorized. You do not have access to this page.');
        }

        return $next($request);
    }
}