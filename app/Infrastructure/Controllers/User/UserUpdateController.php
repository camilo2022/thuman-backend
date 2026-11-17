<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\User;

use App\Application\UserUseCase\UserUpdateUseCase;
use App\Domain\User\Values\UserId;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Infrastructure\Repositories\UserRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class UserUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

   /**
     * @OA\Put(
     *     path="/Users/Update/{userId}",
     *     summary="Actualizar un usuario existente",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"person_id", "current_password", "password", "password_confirmation"},
     *                 @OA\Property(property="person_id", type="integer", example=1),
     *                 @OA\Property(property="current_password", type="string", example="P@ssword123"),
     *                 @OA\Property(property="password", type="string", example="NewP@ssword1"),
     *                 @OA\Property(property="password_confirmation", type="string", example="NewP@ssword1")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El usuario fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="person_id", type="integer", example=1),
     *                     @OA\Property(property="password", type="string", example="$2y$10$**********************************************"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 21:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02 21:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El usuario fue actualizado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="El usuario no existe: No query results for model [App\\Models\\User] 1")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="person_id", type="string", example="Persona"),
     *                 @OA\Property(property="current_password", type="string", example="Contraseña actual"),
     *                 @OA\Property(property="password", type="string", example="Nueva contraseña"),
     *                 @OA\Property(property="password_confirmation", type="string", example="Confirmación de la nueva contraseña")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(
     *                     property="person_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="La persona no existe en la base de datos.")
     *                 ),
     *                 @OA\Property(
     *                     property="current_password",
     *                     type="array",
     *                     @OA\Items(type="string", example="La contraseña actual no es correcta.")
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.")
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="array",
     *                     @OA\Items(type="string", example="No coincide con la confirmación.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el usuario.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al actualizar el usuario: clave duplicada.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(UserUpdateRequest $request, int $userId)
    {
        Gate::authorize('updateUser', $userId);

        try {
            $userId = new UserId($userId ?? null);
            $userUpdateUseCase = new UserUpdateUseCase($this->userRepository);
            $user = new UserResource($userUpdateUseCase->__invoke($request, $userId));
            return $this->successResponse(
                $user,
                'La contraseña fue actualizada exitosamente.',
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
