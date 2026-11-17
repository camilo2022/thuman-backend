<?php

namespace App\Policies;

use App\Models\AcademicQualification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AcademicQualificationPolicy
{
    public function allAcademicQualification(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.AcademicQualification.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile?->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes listar tus propias formaciones academicas.');
    }

    public function findAcademicQualification(?User $user, int $academicQualificationId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.AcademicQualification.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $academicQualification = AcademicQualification::find($academicQualificationId);

        return $user->person->profile?->id == $academicQualification->profile_id || !$academicQualification
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes consultar tu propia formacion academica.');
    }

    public function storeAcademicQualification(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.AcademicQualification.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes guardar tu propia formacion academica.');
    }

    public function updateAcademicQualification(?User $user, int $academicQualificationId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.AcademicQualification.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $academicQualification = AcademicQualification::find($academicQualificationId);

        return $user->person->profile?->id == $academicQualification->profile_id || !$academicQualification
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes actualizar tu propia formacion academica.');
    }

    public function deleteAcademicQualification(?User $user, int $academicQualificationId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.AcademicQualification.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $academicQualification = AcademicQualification::find($academicQualificationId);

        return $user->person->profile?->id == $academicQualification->profile_id || !$academicQualification
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes eliminar tu propia formacion academica.');
    }
}
