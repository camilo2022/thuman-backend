<?php

namespace App\Policies;

use App\Models\EmergencyContact;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmergencyContactPolicy
{
    public function findEmergencyContact(?User $user, ?int $emergencyContactId, object $emergencyContact): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.EmergencyContact.All'])  || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        if (!$emergencyContactId) {
            if ($emergencyContact->profile_id == $user->person->profile?->id) return Response::allow();
        }

        $emergencyContact = EmergencyContact::find($emergencyContactId);

        return $user->person->profile?->id == $emergencyContact->profile_id || !$emergencyContact
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes consultar tu propio contacto de emergencia registrado.');
    }

    public function storeEmergencyContact(?User $user, int $profileId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.EmergencyContact.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->person->profile->id == $profileId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes guardar tu propio contacto de emergencia registrado.');
    }

    public function updateEmergencyContact(?User $user, int $emergencyContactId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Profile.EmergencyContact.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $emergencyContact = EmergencyContact::find($emergencyContactId);

        return $user->person->profile?->id == $emergencyContact->profile_id || !$emergencyContact
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes actualizar tu propio contacto de emergencia registrado.');
    }
}
