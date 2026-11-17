<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function allUser(?User $user): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Users.All']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'No tienes permiso para listar usuarios.');
    }

    public function findUser(?User $user, ?int $userId, object $model): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if($user->id == $userId) return Response::allow();

        if ($user->isEmployee()) {
            return $user->canAny(['Users.Find']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'Solo puedes consultar tu propio usuario.');
    }

    public function updateUser(?User $user, int $userId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if($user->id == $userId) return Response::allow();

        if ($user->isEmployee()) {
            return $user->canAny(['Users.Update']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return Response::denyWithStatus(403, 'Solo puedes actualizar tu propio usuario.');
    }

    public function toggleUser(?User $user, int $userId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Users.Toogle']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $model = User::findOrFail($userId);

        return $model->isEmployee()
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes modificar privilegios de usuarios empleados.');
    }

    public function deleteUser(?User $user, int $userId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Users.Delete']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        return $user->id == $userId
            ? Response::allow()
            : Response::denyWithStatus(403, 'Solo puedes eliminar tu propio usuario.');
    }

    public function restoreUser(?User $user, int $userId): Response
    {
        if (!$user) return Response::denyWithStatus(401, 'Debes iniciar sesión.');

        if ($user->isEmployee()) {
            return $user->canAny(['Users.Restore']) || true
                ? Response::allow()
                : Response::denyWithStatus(403, 'No cuenta con los permisos necesarios para ejecutar esta acción.');
        }

        $model = User::withTrashed()->findOrFail($userId);

        if (!$model->trashed()) return Response::denyWithStatus(400, 'El usuario no está eliminada.');

        return Response::denyWithStatus(403, 'No tienes permiso para restaurar usuarios.');
    }
}
