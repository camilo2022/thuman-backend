<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Auth\AuthInternal\Contracts\AuthInternalContract;
use App\Models\User as AuthInternalModel;
use App\Domain\Auth\AuthInternal\AuthInternal;
use App\Domain\Auth\AuthInternal\Values\AuthInternalDocumentNumber;
use App\Domain\Auth\AuthInternal\Values\AuthInternalEmail;
use App\Domain\Auth\AuthInternal\Values\AuthInternalPassword;
use App\Domain\Auth\AuthInternal\Values\AuthInternalPermissions;
use App\Domain\Auth\AuthInternal\Values\AuthInternalEmployeeId;
use App\Domain\Auth\AuthInternal\Values\AuthInternalRoles;
use App\Domain\Auth\AuthInternal\Values\AuthInternalToken;
use App\Domain\Auth\AuthInternal\Values\AuthInternalUserId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AuthInternalRepository implements AuthInternalContract
{
    private $authInternalModel;

    public function __construct()
    {
        $this->authInternalModel = new AuthInternalModel;
    }

    public function login(AuthInternal $authInternal): ?AuthInternal
    {
        try {
            $model = $this->authInternalModel
                ->with([
                    'person.employee.position.roles.permissions',
                    'person.employee.position.permissions',
                    'roles.permissions',
                    'permissions',
                ])
                ->whereHas('person', function ($query) use ($authInternal) {
                    $query->where(function ($q) use ($authInternal) {
                        $q->where('document_number', $authInternal->documentNumber()->value())
                            ->orWhere('email', $authInternal->email()->value());
                    })->whereHas('employee');
                })
                ->first();

            if (!$model || !Hash::check($authInternal->password()->value(), $model->password)) {
                throw new UnauthorizedException('Credenciales inválidas.');
            }

            $position = $model->person?->employee?->position;

            $permissions = $model->getAllPermissions()
                ->merge($position?->getAllPermissions() ?? collect())
                ->unique('id')
                ->values();

            $roles = $model->roles
                ->merge($position?->roles ?? collect())
                ->unique('id')
                ->values();

            $model->setRelation('permissions', $permissions);
            $model->setRelation('roles', $roles);

            $token = $model->createToken('auth_token')->plainTextToken;

            return $this->mapToEntity($model, $token);
        } catch (UnauthorizedException $e) {
            throw new HttpException(401, 'Error de credenciales: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al autenticarse: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al autenticarse: ' . $e->getMessage());
        }
    }

    public function logout(AuthInternalUserId $userId): ?AuthInternal
    {
        try {
            $model = $this->authInternalModel
                ->with(['person'])
                ->findOrFail($userId->value());

            $model->tokens()->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El cierre de sesión falló: .' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error en el cierre de sesión: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error al intentar cerrar sesión: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model, $token = null): AuthInternal
    {
        return new AuthInternal(
            new AuthInternalEmployeeId($model->person->employee->id),
            new AuthInternalUserId($model->id),
            new AuthInternalDocumentNumber($model->person->document_number),
            new AuthInternalEmail($model->person->email),
            new AuthInternalPassword($model->password),
            new AuthInternalToken($token),
            new AuthInternalRoles($token ? $model->roles : collect()),
            new AuthInternalPermissions($token ? $model->permissions : collect())
        );
    }
}
