<?php

namespace App\Policies;

use App\Models\LanguageProficiencie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LanguageProficienciePolicy
{
    public function allLanguageProficiencie(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.LanguageProficiencie.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile?->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes listar tus propios idiomas registrados.');
    }

    public function findLanguageProficiencie(?User $user, int $languageProficiencieId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.LanguageProficiencie.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $languageProficiencie = LanguageProficiencie::find($languageProficiencieId);

        return $user->person->profile?->id == $languageProficiencie->profile_id || !$languageProficiencie
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes consultar tu propio idioma registrado.');
    }

    public function storeLanguageProficiencie(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.LanguageProficiencie.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes guardar tu propio idioma registrado.');
    }

    public function updateLanguageProficiencie(?User $user, int $languageProficiencieId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.LanguageProficiencie.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $languageProficiencie = LanguageProficiencie::find($languageProficiencieId);

        return $user->person->profile?->id == $languageProficiencie->profile_id || !$languageProficiencie
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes actualizar tu propio idioma registrado.');
    }

    public function deleteLanguageProficiencie(?User $user, int $languageProficiencieId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.LanguageProficiencie.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $languageProficiencie = LanguageProficiencie::find($languageProficiencieId);

        return $user->person->profile?->id == $languageProficiencie->profile_id || !$languageProficiencie
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes eliminar tu propio idioma registrado.');
    }
}
