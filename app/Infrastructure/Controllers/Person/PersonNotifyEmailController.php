<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Person;

use App\Application\PersonUseCase\PersonNotifyEmailUseCase;
use App\Domain\Person\Values\PersonId;
use App\Infrastructure\Repositories\PersonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class PersonNotifyEmailController
{
    use ApiResponser;
    use ApiMessage;
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @OA\Post(
     *     path="/People/Notify/Email/{personId}",
     *     summary="Enviar código de verificación al correo electrónico de una persona",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="personId",
     *         in="path",
     *         required=true,
     *         description="ID de la persona a la que se le enviará el código de verificación por correo",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Código enviado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", example={}),
     *             @OA\Property(property="message", type="string", example="Se ha enviado exitosamente el código de verificación al correo electrónico asociado a la persona."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de negocio al enviar el correo.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error de negocio al enviar correo: El correo ya fue verificado anteriormente.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La persona no fue encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="La persona no existe: No query results for model [App\\Models\\Person] 1")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al enviar el correo.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado al enviar el correo: Error en la integración con Hablame.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(int|null $personId = null)
    {
        Gate::authorize('notifyPerson', $personId);

        try {
            $personId = new PersonId($personId);
            $PersonNotifyEmailUseCase = new PersonNotifyEmailUseCase($this->personRepository);
            $PersonNotifyEmailUseCase->__invoke($personId);
            return $this->successResponse(
                '',
                'Se ha enviado exitosamente el código de verificación al correo electrónico asociado a la persona.',
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
