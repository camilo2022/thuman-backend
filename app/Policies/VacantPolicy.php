<?php

namespace App\Policies;

use App\Models\Vacant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class VacantPolicy
{
    public function allVacant(?User $user): Response
    {
        if (!$user) return Response::allow();

        if ($user->isEmployee()) {
            return $user->canAny(['Vacant.All'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::allow();
    }

    public function findVacant(?User $user, int $vacantId): Response
    {
        if (!$user) return Response::allow();

        if ($user->isEmployee()) {
            $vacant = Vacant::find($vacantId);

            if($vacant->status == Vacant::PUBLISHED) return Response::allow();

            return $user->canAny(['Vacant.Find'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::allow();
    }

    public function storeVacant(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Vacant.Store'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes crear vacantes.');
    }

    public function updateVacant(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Vacant.Update'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes actualizar vacantes.');
    }

    public function reviewVacant(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Vacant.Update'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes revisar vacantes.');
    }

    public function approvedVacant(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Vacant.Update'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes aprobar vacantes.');
    }

    public function rejectedVacant(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Vacant.Update'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes rechazar vacantes.');
    }

    public function publishedVacant(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Vacant.Update'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes publicar vacantes.');
    }

    public function finishedVacant(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Vacant.Update'])
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes finalizar vacantes.');
    }
}
