<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfilePolicy
{
    public function findProfile(?User $user, ?int $profileId, object $profile): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        if (!$profileId) {
            if ($profile->person_id && $profile?->person_id == $user->person_id) return Response::allow();
        }

        return $user->person->profile?->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes consultar tu propia información.');
    }

    public function storeProfile(?User $user, int $personId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person_id == $personId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes guardar tu propio perfil.');
    }

    public function updateProfile(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.'.$user);

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile?->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes actualizar tu propio perfil.');
    }
}
