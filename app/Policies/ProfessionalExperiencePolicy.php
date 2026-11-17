<?php

namespace App\Policies;

use App\Models\ProfessionalExperience;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfessionalExperiencePolicy
{
    public function allProfessionalExperience(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalExperience.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile?->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes listar tus propias experiencias profesionales.');
    }

    public function findProfessionalExperience(?User $user, int $professionalExperienceId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalExperience.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $professionalExperience = ProfessionalExperience::find($professionalExperienceId);

        return $user->person->profile?->id == $professionalExperience->profile_id || !$professionalExperience
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes consultar tu propia experiencia profesional registrado.');
    }

    public function storeProfessionalExperience(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalExperience.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes guardar tu propia experiencia profesional registrado.');
    }

    public function updateProfessionalExperience(?User $user, int $professionalExperienceId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalExperience.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $professionalExperience = ProfessionalExperience::find($professionalExperienceId);

        return $user->person->profile?->id == $professionalExperience->profile_id || !$professionalExperience
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes actualizar tu propia experiencia profesional registrado.');
    }

    public function deleteProfessionalExperience(?User $user, int $professionalExperienceId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalExperience.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $professionalExperience = ProfessionalExperience::find($professionalExperienceId);

        return $user->person->profile?->id == $professionalExperience->profile_id || !$professionalExperience
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes eliminar tu propia experiencia profesional registrado.');
    }
}
