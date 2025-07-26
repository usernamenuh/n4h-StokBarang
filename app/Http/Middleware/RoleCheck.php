<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    $role = $request->user()->role; // misal 'admin_gudang'

    if ($role !== 'admin_gudang') {
        abort(403, 'Akses ditolak: Anda bukan admin gudang.');
    }

    return $next($request);
    }
}
