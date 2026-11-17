<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Person;

use App\Application\PersonUseCase\PersonAllUseCase;
use App\Http\Requests\Person\PersonAllRequest;
use App\Http\Resources\Person\PersonCollection;
use App\Infrastructure\Repositories\PersonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class PersonAllController
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
     *     path="/People/All",
     *     summary="Listar todas las personas",
     *     tags={"People"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda para filtrar personas",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Número de registros por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de personas obtenido exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="people", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="first_name", type="string", example="Carlos"),
     *                         @OA\Property(property="second_name", type="string", example="Andrés"),
     *                         @OA\Property(property="first_last_name", type="string", example="Martínez"),
     *                         @OA\Property(property="second_last_name", type="string", example="Pérez"),
     *                         @OA\Property(property="birth_date", type="string", format="date-time", example="1990-01-01 00:00:00"),
     *                         @OA\Property(property="document_number", type="string", example="1234567890"),
     *                         @OA\Property(property="document_issue_date", type="string", format="date-time", example="2010-01-01 00:00:00"),
     *                         @OA\Property(property="address", type="string", example="Calle 10 #20-30"),
     *                         @OA\Property(property="neighborhood", type="string", example="Centro"),
     *                         @OA\Property(property="phone_number", type="string", example="3001234567"),
     *                         @OA\Property(property="phone_number_verified_at", type="string", format="date-time", example="2024-07-01 00:00:00"),
     *                         @OA\Property(property="email", type="string", example="carlos@example.com"),
     *                         @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-07-01 00:00:00"),
     *                         @OA\Property(property="dialing_country", type="object"),
     *                         @OA\Property(property="document_type", type="object"),
     *                         @OA\Property(property="person_type", type="object"),
     *                         @OA\Property(property="sex", type="object"),
     *                         @OA\Property(property="gender", type="object"),
     *                         @OA\Property(property="blood_type", type="object"),
     *                         @OA\Property(property="marital_status", type="object"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-01 00:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-01 00:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=50),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=5)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las personas fueron consultadas exitosamente."),
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
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="search", type="array",
     *                     @OA\Items(type="string", example="Debe ser una cadena de caracteres.")
     *                 ),
     *                 @OA\Property(property="perPage", type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las personas.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado al obtener las personas: Error en la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(PersonAllRequest $request)
    {
        Gate::authorize('allPerson');

        try {
            $personAllUseCase = new PersonAllUseCase($this->personRepository);
            $people = new PersonCollection($personAllUseCase->__invoke($request));
            return $this->successResponse(
                $people,
                'Las personas fueron consultadas exitosamente.',
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
