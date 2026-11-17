<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GalleryPolicy
{
    public function allGallery(?User $user): Response
    {
        if (!$user) return Response::allow();

        if (!$user->isEmployee()) return Response::allow();

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Gallery.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No tienes permiso para listar galeria.');
    }

    public function findGallery(?User $user, int $galleryId = null): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Gallery.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes consultar galeria.');
    }

    public function storeGallery(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Gallery.Store']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes crear galeria.');
    }

    public function deleteGallery(?User $user, int $galleryId = null): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Gallery.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes inactivar galeria.');
    }

    public function destroyGallery(?User $user, int $galleryId = null): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Gallery.Destroy']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes eliminar galeria.');
    }

    public function restoreGallery(?User $user, int $galleryId = null): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Enterprise.Entity.Gallery.Restore']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No puedes activar galeria.');
    }
}
