<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Person;

use App\Application\PersonUseCase\PersonStoreUseCase;
use App\Http\Requests\Person\PersonStoreRequest;
use App\Http\Resources\Person\PersonResource;
use App\Infrastructure\Repositories\PersonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class PersonStoreController
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
     *     path="/People/Store",
     *     summary="Registrar una nueva persona",
     *     tags={"People"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"first_name", "first_last_name", "birth_date", "document_number", "document_issue_date", "city_id", "address", "neighborhood", "phone_number", "email", "document_type_id", "person_type_id", "sex_id", "gender_id", "blood_type_id", "marital_status_id"},
     *                 @OA\Property(property="first_name", type="string", example="Mariana"),
     *                 @OA\Property(property="second_name", type="string", example="Lucía"),
     *                 @OA\Property(property="first_last_name", type="string", example="González"),
     *                 @OA\Property(property="second_last_name", type="string", example="Ramírez"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1994-09-08"),
     *                 @OA\Property(property="document_number", type="string", example="1234567892"),
     *                 @OA\Property(property="document_issue_date", type="string", format="date", example="2014-02-20"),
     *                 @OA\Property(property="city_id", type="integer", example=20772),
     *                 @OA\Property(property="address", type="string", example="Carrera 7 #45-90"),
     *                 @OA\Property(property="neighborhood", type="string", example="La Riviera"),
     *                 @OA\Property(property="phone_number", type="string", example="3109876543"),
     *                 @OA\Property(property="phone_number_verified_at", type="string", format="date-time", nullable=true, example="2025-07-02 10:15:00"),
     *                 @OA\Property(property="email", type="string", example="mariana.gonzalez@example.com"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2025-07-02 10:45:00"),
     *                 @OA\Property(property="dialing_country_id", type="integer", example=12),
     *                 @OA\Property(property="document_type_id", type="integer", example=12),
     *                 @OA\Property(property="sex_id", type="integer", example=31),
     *                 @OA\Property(property="gender_id", type="integer", example=33),
     *                 @OA\Property(property="blood_type_id", type="integer", example=44),
     *                 @OA\Property(property="marital_status_id", type="integer", example=80)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La persona fue registrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                  @OA\Property(property="person", type="object",
     *                       @OA\Property(property="id", type="integer", example=10),
     *                       @OA\Property(property="first_name", type="string", example="Mariana"),
     *                       @OA\Property(property="second_name", type="string", example="Lucía"),
     *                       @OA\Property(property="first_last_name", type="string", example="González"),
     *                       @OA\Property(property="second_last_name", type="string", example="Ramírez"),
     *                       @OA\Property(property="birth_date", type="string", format="date", example="1994-09-08"),
     *                       @OA\Property(property="document_number", type="string", example="1234567892"),
     *                       @OA\Property(property="document_issue_date", type="string", format="date", example="2014-02-20"),
     *                       @OA\Property(property="city_id", type="integer", example=20772),
     *                       @OA\Property(property="address", type="string", example="Carrera 7 #45-90"),
     *                       @OA\Property(property="neighborhood", type="string", example="La Riviera"),
     *                       @OA\Property(property="phone_number", type="string", example="3109876543"),
     *                       @OA\Property(property="phone_number_verified_at", type="string", format="date-time", example="2025-07-02 10:15:00"),
     *                       @OA\Property(property="email", type="string", example="mariana.gonzalez@example.com"),
     *                       @OA\Property(property="email_verified_at", type="string", format="date-time", example="2025-07-02 10:45:00"),
     *                       @OA\Property(property="dialing_country_id", type="integer", example=12),
     *                       @OA\Property(property="document_type_id", type="integer", example=12),
     *                       @OA\Property(property="sex_id", type="integer", example=31),
     *                       @OA\Property(property="gender_id", type="integer", example=33),
     *                       @OA\Property(property="blood_type_id", type="integer", example=44),
     *                       @OA\Property(property="marital_status_id", type="integer", example=80),
     *                       @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 21:00:00"),
     *                       @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02 21:00:00")
     *                  ),
     *             ),
     *             @OA\Property(property="message", type="string", example="La persona fue registrada exitosamente."),
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
     *                 @OA\Property(property="first_name", type="string", example="Primer nombre"),
     *                 @OA\Property(property="email", type="string", example="Correo electrónico"),
     *                 @OA\Property(property="document_number", type="string", example="Número de documento"),
     *                 @OA\Property(property="document_type_id", type="string", example="Tipo de documento"),
     *                 @OA\Property(property="gender_id", type="string", example="Genero"),
     *                 @OA\Property(property="blood_type_id", type="string", example="Tipo de sangre")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="Ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="array",
     *                     @OA\Items(type="string", example="Ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(
     *                     property="document_number",
     *                     type="array",
     *                     @OA\Items(type="string", example="El formato no es válido.")
     *                 ),
     *                 @OA\Property(
     *                     property="gender_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 ),
     *                 @OA\Property(
     *                     property="document_type_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="No se ha podido validar.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar la persona.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al guardar la persona: clave duplicada.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(PersonStoreRequest $request)
    {
        Gate::authorize('storePerson');

        try {
            $personStoreUseCase = new PersonStoreUseCase($this->personRepository);
            $person = new PersonResource($personStoreUseCase->__invoke($request));
            return $this->successResponse(
                $person,
                'La persona fue registrada exitosamente.',
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
