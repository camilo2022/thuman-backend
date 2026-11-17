<?php

namespace App\Policies;

use App\Models\ProfessionalSkill;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfessionalSkillPolicy
{
    public function allProfessionalSkill(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalSkill.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile?->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes listar tus propias habilidades profesionales.');
    }

    public function findProfessionalSkill(?User $user, int $professionalSkillId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalSkill.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $professionalSkill = ProfessionalSkill::find($professionalSkillId);

        return $user->person->profile?->id == $professionalSkill->profile_id || !$professionalSkill
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes consultar tu propia habilidad profresional registrado.');
    }

    public function storeProfessionalSkill(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalSkill.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes guardar tu propia habilidad profresional registrado.');
    }

    public function updateProfessionalSkill(?User $user, int $professionalSkillId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalSkill.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $professionalSkill = ProfessionalSkill::find($professionalSkillId);

        return $user->person->profile?->id == $professionalSkill->profile_id || !$professionalSkill
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes actualizar tu propia habilidad profresional registrado.');
    }

    public function deleteProfessionalSkill(?User $user, int $professionalSkillId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.ProfessionalSkill.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $professionalSkill = ProfessionalSkill::find($professionalSkillId);

        return $user->person->profile?->id == $professionalSkill->profile_id || !$professionalSkill
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes eliminar tu propia habilidad profresional registrado.');
    }
}
