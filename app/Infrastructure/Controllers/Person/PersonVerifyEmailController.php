<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Person;

use App\Application\PersonUseCase\PersonVerifyEmailUseCase;
use App\Domain\Person\Values\PersonId;
use App\Http\Requests\Person\PersonVerifyEmailRequest;
use App\Http\Resources\Person\PersonResource;
use App\Infrastructure\Repositories\PersonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class PersonVerifyEmailController
{
    use ApiResponser;
    use ApiMessage;
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @OA\Put(
     *     path="/People/Verify/Email/{personId}",
     *     summary="Verificar código enviado al correo electrónico de una persona",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="personId",
     *         in="path",
     *         required=true,
     *         description="ID de la persona",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"code"},
     *                 @OA\Property(property="code", type="string", example="A1B2C3")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verificación exitosa.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="person", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="first_name", type="string", example="Carlos"),
     *                     @OA\Property(property="second_name", type="string", example="Eduardo"),
     *                     @OA\Property(property="first_last_name", type="string", example="López"),
     *                     @OA\Property(property="second_last_name", type="string", example="Ramírez"),
     *                     @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
     *                     @OA\Property(property="document_number", type="string", example="123456789"),
     *                     @OA\Property(property="document_issue_date", type="string", format="date", example="2008-05-20"),
     *                     @OA\Property(property="city_id", type="integer", example=11001),
     *                     @OA\Property(property="address", type="string", example="Calle 123 #45-67"),
     *                     @OA\Property(property="neighborhood", type="string", example="Chapinero"),
     *                     @OA\Property(property="phone_number", type="string", example="3012345678"),
     *                     @OA\Property(property="phone_number_verified_at", type="string", format="date-time", example=null),
     *                     @OA\Property(property="email", type="string", example="carlos@example.com"),
     *                     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2025-07-14 15:30:00"),
     *                     @OA\Property(property="document_type_id", type="integer", example=1),
     *                     @OA\Property(property="sex_id", type="integer", example=1),
     *                     @OA\Property(property="gender_id", type="integer", example=1),
     *                     @OA\Property(property="blood_type_id", type="integer", example=3),
     *                     @OA\Property(property="marital_status_id", type="integer", example=2),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-10 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-14 15:30:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Se ha verificado exitosamente el correo electrónico asociado a la persona."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
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
     *         response=410,
     *         description="Código expirado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="El código ha expirado. Solicite uno nuevo.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Código inválido o error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="errors", type="object", example={"code": {"Debe tener exactamente 6 caracteres."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al verificar el código.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado al verificar el código: ...")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(PersonVerifyEmailRequest $request, int $personId)
    {
        Gate::authorize('verifyPerson', $personId);

        try {
            $personId = new PersonId($personId ?? null);
            $personVerifyEmailUseCase = new PersonVerifyEmailUseCase($this->personRepository);
            $person = new PersonResource($personVerifyEmailUseCase->__invoke($request, $personId));
            return $this->successResponse(
                $person,
                'Se ha verificado exitosamente el correo electrónico asociado a la persona.',
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
