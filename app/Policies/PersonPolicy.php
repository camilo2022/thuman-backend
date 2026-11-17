<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PersonPolicy
{
    public function allPerson(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['People.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No tienes permiso para listar personas.');
    }

    public function findPerson(?User $user, ?int $personId, object $person): Response
    {
        if (!$user) {
            return (!empty($person->document_number) && !empty($person->document_type_id))
                ? Response::allow()
                : Response::denyWithStatus(403, 'Debe enviar el tipo y numero de documento.');
        }

        if($user->person_id == $personId) return Response::allow();

        if (!$personId) {
            if ($person->email == $user->email) return Response::allow();
            if ($person->document_number == $user->person->document_number && $person->document_type_id == $user->person->document_type_id) return Response::allow();
            if ($person->phone_number == $user->person->phone_number) return Response::allow();
        }

        if ($user->isEmployee()) {
            return $user->canAny(['People.Find', 'Enterprise.Entity.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'Solo puedes consultar tu propia información.');
    }

    public function storePerson(?User $user): Response
    {
        if (!$user) return Response::allow();

        if ($user->isEmployee()) {
            return $user->canAny(['People.Store', 'Enterprise.Entity.Store', 'Enterprise.Entity.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person_id
            ? Response::denyWithStatus(403, 'No puedes crear más de una persona.')
            : Response::allow();
    }

    public function updatePerson(?User $user, int $personId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['People.Update', 'Enterprise.Entity.Store', 'Enterprise.Entity.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person_id == $personId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes actualizar tu propia información.');
    }

    public function togglePerson(?User $user, int $personId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        return $user->person_id == $personId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes realizar esta acción desde tu propia cuenta.');
    }

    public function notifyPerson(?User $user, int $personId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        return $user->person_id == $personId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes enviar notificaciones de tu propia cuenta.');
    }

    public function verifyPerson(?User $user, int $personId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        return $user->person_id == $personId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes verificar tu propia información.');
    }

    public function deletePerson(?User $user, int $personId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['People.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person_id == $personId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes eliminar tu propia información.');
    }

    public function restorePerson(?User $user, int $personId): Response
    {
        if ($user->isEmployee()) {
            return $user->canAny(['People.Restore']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $person = Person::withTrashed()->findOrFail($personId);

        if (!$person->trashed()) return Response::denyWithStatus(400, 'La persona no está eliminada.');

        return Response::denyWithStatus(403, 'No tienes permiso para restaurar esta persona.');
    }
}
