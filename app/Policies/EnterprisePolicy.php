<?php

namespace App\Policies;

use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnterprisePolicy
{
    public function allEnterprise(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No tienes permiso para listar empresas.');
    }

    public function findEnterprise(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes consultar empresas.');
    }

    public function storeEnterprise(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes crear empresas.');
    }

    public function updateEnterprise(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes actualizar empresas.');
    }

    public function deleteEnterprise(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes inactivar empresas.');
    }

    public function restoreEnterprise(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Restore']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes activar empresas.');
    }
}
