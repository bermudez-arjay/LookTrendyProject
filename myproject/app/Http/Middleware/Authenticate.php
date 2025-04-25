<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // Si la solicitud no espera una respuesta en formato JSON
        if (! $request->expectsJson()) {
            // Redirigir al usuario a la ruta de login
            return route('login');
        }
    
        // Si la solicitud espera una respuesta en JSON, no redirige
        return null;
    }
    
}
