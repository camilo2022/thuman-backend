<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\User;

use App\Application\UserUseCase\UserStoreUseCase;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Resources\User\UserResource;
use App\Infrastructure\Repositories\UserRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class UserStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *     path="/Users/Store",
     *     summary="Registrar un nuevo usuario",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"person_id", "password", "password_confirmation"},
     *                 @OA\Property(property="person_id", type="integer", example=1),
     *                 @OA\Property(property="password", type="string", example="P@ssword123"),
     *                 @OA\Property(property="password_confirmation", type="string", example="P@ssword123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El usuario fue registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="person_id", type="integer", example=1),
     *                     @OA\Property(property="password", type="string", example="$2y$10$***************************************"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 21:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02 21:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El usuario fue registrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="person_id", type="string", example="Persona"),
     *                 @OA\Property(property="password", type="string", example="Contraseña"),
     *                 @OA\Property(property="password_confirmation", type="string", example="Confirmación de contraseña")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(
     *                     property="person_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="El campo persona ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.")
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="array",
     *                     @OA\Items(type="string", example="El campo confirmación de contraseña no coincide con la confirmación.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar el usuario.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al guardar el usuario: clave duplicada.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(UserStoreRequest $request)
    {
        try {
            $userStoreUseCase = new UserStoreUseCase($this->userRepository);
            $user = new UserResource($userStoreUseCase->__invoke($request));
            return $this->successResponse(
                $user,
                'El usuario fue registrado exitosamente.',
                201
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
