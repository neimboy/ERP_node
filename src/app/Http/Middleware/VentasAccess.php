<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VentasAccess
{
    /**
     * Correos permitidos para acceder al módulo Ventas.
     * Mantener en minúsculas.
     */
    protected $allowed = [
        'admin@erp.com',
        'ventas@erp.com',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Si no está autenticado, delegar a auth (redirección a login)
        if (! $user) {
            return redirect()->route('login');
        }

        $email = strtolower($user->email ?? '');

        // Usuarios permitidos tienen acceso completo
        if (in_array($email, $this->allowed, true)) {
            return $next($request);
        }

        // Usuarios autenticados pero no permitidos: pueden VER (index/show)
        // pero no crear/editar/actualizar/eliminar en recursos sensibles.
        $protected = ['cotizaciones', 'ordenes', 'facturas', 'pagos', 'oportunidades'];

        // Si la ruta nombrada indica una acción de edición/creación/almacenamiento/borrado, bloquearla
        $routeName = $request->route()?->getName() ?? '';
        foreach ($protected as $res) {
            $patterns = [
                "{$res}.create", "{$res}.store", "{$res}.edit", "{$res}.update", "{$res}.destroy",
                "ventas.{$res}.create", "ventas.{$res}.store", "ventas.{$res}.edit", "ventas.{$res}.update", "ventas.{$res}.destroy",
            ];
            foreach ($patterns as $p) {
                if ($routeName && $request->routeIs($p)) {
                    return redirect()->route('dashboard')->with('error', 'No autorizado para editar en el módulo Ventas');
                }
            }
        }

        // Bloquear métodos que modifican (POST/PUT/PATCH/DELETE) si apuntan a recursos protegidos
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            foreach ($protected as $res) {
                if ($request->is("ventas/{$res}*") || $request->is("{$res}*")) {
                    return redirect()->route('dashboard')->with('error', 'No autorizado para editar en el módulo Ventas');
                }
            }
        }

        // Bloquear formularios de creación/edición (GET) por URI
        foreach ($protected as $res) {
            if ($request->is("ventas/{$res}/create") || $request->is("{$res}/create") || $request->is("ventas/{$res}/*/edit") || $request->is("{$res}/*/edit")) {
                return redirect()->route('dashboard')->with('error', 'No autorizado para editar en el módulo Ventas');
            }
        }

        // Si ninguna regla anterior aplica, permitir sólo ver
        return $next($request);
    }
}
