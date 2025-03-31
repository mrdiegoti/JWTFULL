<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado y es administrador
        if (!auth()->check() || auth()->user()->role_id !== '1') {
            return redirect('/'); // O cambiar a otra ruta de error/autorización
        }

        return $next($request);
    }
}
