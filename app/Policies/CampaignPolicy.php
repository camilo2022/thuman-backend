<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class CampaignPolicy
{
    public function allCampaign(?User $user): Response
    {
        if (!$user) {
            return Response::denyWithStatus(401, 'Debes iniciar sesión.');
        }

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Campaign.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(
                    403,
                    'No cuenta con los permisos necesarios para ejecutar esta acción.'
                );
        }

        return Response::denyWithStatus(403, 'No tienes permiso para listar campañas.');
    }

    public function findCampaign(?User $user, int $campaignId = null): Response
    {
        if (!$user) {
            return Response::denyWithStatus(401, 'Debes iniciar sesión.');
        }

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Campaign.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(
                    403,
                    'No cuenta con los permisos necesarios para ejecutar esta acción.'
                );
        }

        return Response::denyWithStatus(403, 'No puedes consultar campañas.');
    }

    public function storeCampaign(?User $user): Response
    {
        if (!$user) {
            return Response::denyWithStatus(401, 'Debes iniciar sesión.');
        }

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Campaign.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(
                    403,
                    'No cuenta con los permisos necesarios para ejecutar esta acción.'
                );
        }

        return Response::denyWithStatus(403, 'No puedes crear campañas.');
    }

    public function updateCampaign(?User $user, int $campaignId = null): Response
    {
        if (!$user) {
            return Response::denyWithStatus(401, 'Debes iniciar sesión.');
        }

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Campaign.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(
                    403,
                    'No cuenta con los permisos necesarios para ejecutar esta acción.'
                );
        }

        return Response::denyWithStatus(403, 'No puedes actualizar campañas.');
    }

    public function deleteCampaign(?User $user, int $campaignId = null): Response
    {
        if (!$user) {
            return Response::denyWithStatus(401, 'Debes iniciar sesión.');
        }

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Campaign.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(
                    403,
                    'No cuenta con los permisos necesarios para ejecutar esta acción.'
                );
        }

        return Response::denyWithStatus(403, 'No puedes inactivar campañas.');
    }

    public function restoreCampaign(?User $user, int $campaignId = null): Response
    {
        if (!$user) {
            return Response::denyWithStatus(401, 'Debes iniciar sesión.');
        }

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Campaign.Restore']) || true
                ? Response::allow()
                : Response::denyWithStatus(
                    403,
                    'No cuenta con los permisos necesarios para ejecutar esta acción.'
                );
        }

        return Response::denyWithStatus(403, 'No puedes activar campañas.');
    }
}
