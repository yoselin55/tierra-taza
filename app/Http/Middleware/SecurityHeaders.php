<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Evita clickjacking (iframes maliciosos)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Evita que el browser adivine el Content-Type (MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Activa el filtro XSS del navegador (legacy IE/Chrome)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // No enviar el referrer completo fuera del dominio
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Deshabilitar funciones de navegador innecesarias
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // Content Security Policy: sólo carga recursos de fuentes permitidas
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net",
            "img-src 'self' data: blob: https:",
            "connect-src 'self' https://cdn.jsdelivr.net https://fonts.googleapis.com https://fonts.gstatic.com",
            "frame-src 'self' https://www.google.com https://maps.googleapis.com",
            "frame-ancestors 'self'",
            "form-action 'self'",
            "base-uri 'self'",
            "object-src 'none'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // Fuerza HTTPS en producción (no aplica en local HTTP)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Deshabilitar bfcache para evitar pantalla negra al presionar atras
        $response->headers->set('Cache-Control', 'no-store');

        // Elimina la cabecera que revela el servidor/tecnología
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
