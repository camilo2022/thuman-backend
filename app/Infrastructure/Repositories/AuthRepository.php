<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Auth\Contracts\AuthContract;
use App\Models\User as AuthModel;
use App\Domain\Auth\Auth;
use App\Domain\Auth\Values\AuthDocumentNumber;
use App\Domain\Auth\Values\AuthEmail;
use App\Domain\Auth\Values\AuthPassword;
use App\Domain\Auth\Values\AuthPersonId;
use App\Domain\Auth\Values\AuthToken;
use App\Domain\Auth\Values\AuthUserId;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AuthRepository implements AuthContract
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel;
    }

    public function login(Auth $auth): ?Auth
    {
        try {
            $model = $this->authModel
                ->with(['person'])
                ->whereHas('person', fn($query) => $query->where('document_number', $auth->documentNumber()->value())->orWhere('email', $auth->email()->value()))
                ->first();

            if (!$model or !Hash::check($auth->password()->value(), $model?->password)) {
                throw new UnauthorizedException('Credenciales invalidas.');
            }

            return $this->mapToEntity($model, $model->createToken('auth_token')->plainTextToken);
        } catch (UnauthorizedException $e) {
            throw new HttpException(401, 'Error de credenciales: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al autenticarse: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al autenticarse: ' . $e->getMessage());
        }
    }

    public function logout(AuthUserId $userId): ?Auth
    {
        try {
            $model = $this->authModel
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

    private function mapToEntity($model, $token = null): Auth
    {
        return new Auth(
            new AuthPersonId($model->person->id),
            new AuthUserId($model->id),
            new AuthDocumentNumber($model->person->document_number),
            new AuthEmail($model->person->email),
            new AuthPassword($model->password),
            new AuthToken($token)
        );
    }
}
