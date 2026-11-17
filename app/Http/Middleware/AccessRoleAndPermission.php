<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessRoleAndPermission
{
    use ApiResponser;

    public function handle(Request $request, Closure $next, $args)
    {
        $parts = explode('|', $args);

        $visibility = isset($parts[0]) ? explode(',', $parts[0]) : [];
        $roles = isset($parts[1]) ? explode(',', $parts[1]) : [];
        $permissions = isset($parts[2]) ? explode(',', $parts[2]) : [];

        $visibility = array_map(fn($v) => strtolower(trim($v)), $visibility);
        $roles = array_map('trim', $roles);
        $permissions = array_map('trim', $permissions);

        $isPublic = in_array('public', $visibility);
        $isPrivate = in_array('private', $visibility);

        $headerVisibility = strtolower(trim($request->header('X-Visibility', '')));
        $user = Auth::user();

        if ($isPublic && $headerVisibility === 'public') {
            return $next($request);
        }

        if ($isPublic && !$isPrivate) {
            return $next($request);
        }

        if ($isPrivate) {
            if (!$user) {
                return $this->errorResponse([
                    'message' => 'No autenticado.',
                    'error' => 'unauthenticated'
                ], 401);
            }

            if (!empty(array_filter($roles))) {
                if (!$user->hasAnyRole($roles)) {
                    return $this->errorResponse([
                        'message' => 'No está autorizado para realizar esta acción. Requiere uno de estos roles: '.implode(', ', $roles).'.'
                    ], 403);
                }
            }

            if (!empty(array_filter($permissions))) {
                if (!$user->hasAnyPermission($permissions)) {
                    return $this->errorResponse([
                        'message' => 'No está autorizado para realizar esta acción. Requiere uno de estos permisos: '.implode(', ', $permissions).'.'
                    ], 403);
                }
            }

            return $next($request);
        }

        return $next($request);
    }
}
