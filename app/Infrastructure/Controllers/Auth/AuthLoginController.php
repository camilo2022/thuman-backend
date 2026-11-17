<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Auth;

use App\Application\AuthUseCase\AuthLoginUseCase;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Infrastructure\Repositories\AuthRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AuthLoginController
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
     *     path="/Login",
     *     summary="Iniciar sesión",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"password"},
     *                 @OA\Property(property="email", type="string", nullable=true),
     *                 @OA\Property(property="document_number", type="string", nullable=true,),
     *                 @OA\Property(property="password", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario autenticado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="auth", type="object",
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="document_number", type="string", example="1234567892"),
     *                     @OA\Property(property="email", type="string", example="example@example.com"),
     *                     @OA\Property(property="password", type="string", example="$2y$10$***************************************"),
     *                     @OA\Property(property="token", type="string", example="1|P5tjDeJmnZsYGO1GDi3RcwrBEkXxY...")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Usuario autenticado exitosamente."),
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
     *                 @OA\Property(property="email", type="string", example="Correo electrónico"),
     *                 @OA\Property(property="document_number", type="string", example="Número de documento"),
     *                 @OA\Property(property="password", type="string", example="Contraseña")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser una dirección de correo electrónico válida.")
     *                 ),
     *                 @OA\Property(
     *                     property="document_number",
     *                     type="array",
     *                     @OA\Items(type="string", example="El campo número de documento es requerido cuando correo electrónico no está presente.")
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *                     @OA\Items(type="string", example="El campo contraseña es requerido.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado en la autenticación.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado en la autenticación: La contraseña no coincide.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(AuthLoginRequest $request)
    {
        try {
            $authLoginUseCase = new AuthLoginUseCase($this->authRepository);
            $auth = new AuthResource($authLoginUseCase->__invoke($request));
            return $this->successResponse(
                $auth,
                'Usuario autenticado exitosamente.',
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
