<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\User;

use App\Application\UserUseCase\UserFindUseCase;
use App\Domain\User\Values\UserId;
use App\Http\Requests\User\UserFindRequest;
use App\Http\Resources\User\UserResource;
use App\Infrastructure\Repositories\UserRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class UserFindController
{
    use ApiResponser;
    use ApiMessage;
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Get(
     *     path="/Users/Find/{userId}",
     *     summary="Buscar usuario por ID o por información asociada a la persona",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID del usuario a buscar",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="person_id",
     *         in="query",
     *         description="ID de la persona asociada al usuario",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="document_number",
     *         in="query",
     *         description="Número de documento de la persona",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="phone_number",
     *         in="query",
     *         description="Número de teléfono de la persona",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Correo electrónico de la persona",
     *         required=false,
     *         @OA\Schema(type="string", format="email")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="person_id", type="integer", example=1),
     *                     @OA\Property(property="password", type="string", example="$2y$10$***************************************"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-15 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-15 12:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El usuario fue encontrado exitosamente."),
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
     *                 @OA\Property(property="error", type="string", example="El usuario no existe: No query results for model [App\\Models\\User] 33")
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
     *                 @OA\Property(property="document_number", type="string", example="Número de documento"),
     *                 @OA\Property(property="phone_number", type="string", example="Número de teléfono"),
     *                 @OA\Property(property="email", type="string", example="Correo electrónico")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(
     *                     property="person_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 ),
     *                 @OA\Property(
     *                     property="document_number",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser una cadena de caracteres.")
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser una dirección de correo electrónico válida.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el usuario.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado al buscar el usuario: Error de conexión a la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(UserFindRequest $request, int|null $userId = null)
    {
        Gate::authorize('findUser', [$userId, (object) [
            'person_id' => $request->input('person_id'),
            'document_number' => $request->input('document_number'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'employee_id' => $request->input('employee_id')
        ]]);

        try {
            $userId = new UserId($userId);
            $userFindUseCase = new UserFindUseCase($this->userRepository);
            $user = new UserResource($userFindUseCase->__invoke($request, $userId));
            return $this->successResponse(
                $user,
                'El usuario fue encontrada exitosamente.',
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
