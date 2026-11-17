<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AllyPolicy
{
    public function allAlly(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Ally.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No tienes permiso para listar aliados.');
    }

    public function findAlly(?User $user, int $allyId = null): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Ally.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes consultar aliados.');
    }

    public function storeAlly(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Ally.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes crear aliados.');
    }

    public function updateAlly(?User $user, int $allyId = null): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Ally.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes actualizar aliados.');
    }

    public function deleteAlly(?User $user, int $allyId = null): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Ally.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes inactivar aliados.');
    }

    public function restoreAlly(?User $user, int $allyId = null): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Ally.Restore']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes activar aliados.');
    }
}
