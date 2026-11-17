<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Person;

use App\Application\PersonUseCase\PersonNotifyPhoneNumberUseCase;
use App\Domain\Person\Values\PersonId;
use App\Infrastructure\Repositories\PersonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class PersonNotifyPhoneNumberController
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
     *     path="/People/Notify/PhoneNumber/{personId}",
     *     summary="Enviar código de verificación al número de teléfono de una persona",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="personId",
     *         in="path",
     *         required=true,
     *         description="ID de la persona a la que se le enviará el código de verificación por teléfono",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Código enviado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", example={}),
     *             @OA\Property(property="message", type="string", example="Se ha enviado exitosamente el código de verificación al número de teléfono asociado a la persona."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de negocio al enviar el SMS.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error de negocio al enviar SMS: El número de teléfono no está registrado correctamente.")
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
     *         response=422,
     *         description="No se pudo enviar el SMS.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="No se pudo enviar el SMS: Número inválido o sin cobertura (Código: 1x003)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al enviar el SMS.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado al enviar el SMS: Error de conexión con Hablame")
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
            $PersonNotifyPhoneNumberUseCase = new PersonNotifyPhoneNumberUseCase($this->personRepository);
            $PersonNotifyPhoneNumberUseCase->__invoke($personId);
            return $this->successResponse(
                '',
                'Se ha enviado exitosamente el código de verificación al número de teléfono asociado a la persona.',
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
