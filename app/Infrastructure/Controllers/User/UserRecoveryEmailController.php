<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\User;

use App\Application\UserUseCase\UserRecoveryEmailUseCase;
use App\Http\Requests\User\UserRecoveryEmailRequest;
use App\Http\Resources\User\UserResource;
use App\Infrastructure\Repositories\UserRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class UserRecoveryEmailController
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
     *     path="/Users/Recovery/Email",
     *     summary="Enviar recuperación de contraseña al correo electrónico",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos necesarios para recuperar la contraseña",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"document_number", "email"},
     *                 @OA\Property(
     *                     property="document_number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado y contraseña enviada por correo electrónico",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="person_id", type="integer", example=12),
     *                     @OA\Property(property="password", type="string", example="$2y$10$***************************************"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-15 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-15 10:05:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El usuario fue encontrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida o el correo electrónico no coincide",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El correo electrónico no coincide con el número de documento."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="document_number", type="array",
     *                     @OA\Items(type="string", example="Es requerido.")
     *                 ),
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="El correo electrónico no coincide con el número de documento.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El número de documento no existe",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El usuario no existe: No query results for model [App\\Models\\User] 1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error inesperado al enviar la notificación al usuario: ...")
     *         )
     *     )
     * )
     */
    public function __invoke(UserRecoveryEmailRequest $request)
    {
        try {
            $userRecoveryEmailUseCase = new UserRecoveryEmailUseCase($this->userRepository);
            $user = new UserResource($userRecoveryEmailUseCase->__invoke($request));
            return $this->successResponse(
                $user,
                'El correo electrónico ingresado coincide con el registrado. Se ha enviado una nueva contraseña a tu correo.',
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
