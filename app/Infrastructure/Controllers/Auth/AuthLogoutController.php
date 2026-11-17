<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Auth;

use App\Application\AuthUseCase\AuthLogoutUseCase;
use App\Http\Requests\Auth\AuthLogoutRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Infrastructure\Repositories\AuthRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AuthLogoutController
{
    use ApiResponser;
    use ApiMessage;
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *     path="/Logout",
     *     summary="Cerrar sesión",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"user_id"},
     *                 @OA\Property(property="user_id", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cierre de sesión exitoso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="auth", type="object",
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="document_number", type="string", example="1234567892"),
     *                     @OA\Property(property="email", type="string", example="example@example.com"),
     *                     @OA\Property(property="password", type="string", example="$2y$10$***************************************"),
     *                     @OA\Property(property="token", type="string", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Cierre de sesión exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="user_id", type="string", example="Usuario")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="El campo usuario es requerido.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado durante el cierre de sesión.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado durante el cierre de sesión: El usuario no se encontró.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(AuthLogoutRequest $request)
    {
        try {
            $authLogoutUseCase = new AuthLogoutUseCase($this->authRepository);
            $auth = new AuthResource($authLogoutUseCase->__invoke($request));
            return $this->successResponse(
                $auth,
                'Cierre de sesion exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
