<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Person;

use App\Application\PersonUseCase\PersonFindUseCase;
use App\Domain\Person\Values\PersonId;
use App\Http\Requests\Person\PersonFindRequest;
use App\Http\Resources\Person\PersonResource;
use App\Infrastructure\Repositories\PersonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class PersonFindController
{
    use ApiResponser;
    use ApiMessage;
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @OA\Get(
     *     path="/People/Find/{personId}",
     *     summary="Buscar persona por ID o por atributos opcionales",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="personId",
     *         in="path",
     *         description="ID de la persona a buscar",
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
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="person", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="first_name", type="string", example="Carlos"),
     *                     @OA\Property(property="second_name", type="string", example="Andrés"),
     *                     @OA\Property(property="first_last_name", type="string", example="Martínez"),
     *                     @OA\Property(property="second_last_name", type="string", example="Pérez"),
     *                     @OA\Property(property="birth_date", type="string", format="date-time", example="1990-05-20 00:00:00"),
     *                     @OA\Property(property="document_number", type="string", example="9876543212"),
     *                     @OA\Property(property="document_issue_date", type="string", format="date-time", example="2008-07-10 00:00:00"),
     *                     @OA\Property(property="address", type="string", example="Calle 10 #20-30"),
     *                     @OA\Property(property="neighborhood", type="string", example="Centro"),
     *                     @OA\Property(property="phone_number", type="string", example="3001234567"),
     *                     @OA\Property(property="phone_number_verified_at", type="string", format="date-time", example="2024-06-15 14:30:00"),
     *                     @OA\Property(property="email", type="string", example="carlos.martinez@example.com"),
     *                     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-06-15 14:35:00"),
     *                     @OA\Property(property="dialing_country_id", type="integer", example=4),
     *                     @OA\Property(property="document_type_id", type="integer", example=4),
     *                     @OA\Property(property="person_type_id", type="integer", example=5),
     *                     @OA\Property(property="sex_id", type="integer", example=8),
     *                     @OA\Property(property="gender_id", type="integer", example=9),
     *                     @OA\Property(property="blood_type_id", type="integer", example=10),
     *                     @OA\Property(property="marital_status_id", type="integer", example=14),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-15 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-15 12:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La persona fue encontrada exitosamente."),
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
     *                 @OA\Property(property="error", type="string", example="La persona no existe: No query results for model [App\\Models\\Person] 33")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación en los parámetros de búsqueda.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="document_number", type="string", example="Número de documento"),
     *                 @OA\Property(property="phone_number", type="string", example="Número de teléfono"),
     *                 @OA\Property(property="email", type="string", example="Correo electrónico")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="document_number", type="array",
     *                     @OA\Items(type="string", example="El campo número de documento es obligatorio cuando número de teléfono y correo electrónico no están presentes.")
     *                 ),
     *                 @OA\Property(property="phone_number", type="array",
     *                     @OA\Items(type="string", example="Debe ser una cadena de caracteres.")
     *                 ),
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="Debe ser una dirección de correo electrónico válida.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la persona.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado al buscar la persona: Error de conexión a la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(PersonFindRequest $request, int|null $personId = null)
    {
        Gate::authorize('findPerson', [$personId, (object) [
            'email' => $request->input('email'),
            'document_number' => $request->input('document_number'),
            'document_type_id' => $request->input('document_type_id'),
            'phone_number' => $request->input('phone_number'),
            'employee_id' => $request->input('employee_id')
        ]]);

        try {
            $personId = new PersonId($personId);
            $personFindUseCase = new PersonFindUseCase($this->personRepository);
            $person = new PersonResource($personFindUseCase->__invoke($request, $personId));
            return $this->successResponse(
                $person,
                'La persona fue encontrada exitosamente.',
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
