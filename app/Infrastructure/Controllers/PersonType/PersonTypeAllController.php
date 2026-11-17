<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PersonType;

use App\Application\PersonTypeUseCase\PersonTypeAllUseCase;
use App\Http\Requests\PersonType\PersonTypeAllRequest;
use App\Http\Resources\PersonType\PersonTypeCollection;
use App\Infrastructure\Repositories\PersonTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PersonTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $personTypeRepository;

    public function __construct(PersonTypeRepository $personTypeRepository)
    {
        $this->personTypeRepository = $personTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/PersonTypes/All",
     *     summary="Listar tipos de personas",
     *     description="Obtiene un listado paginado de tipos de personas (incluye eliminados lógicamente).",
     *     tags={"PersonTypes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="Empleado")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Los tipos de personas consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="personTypes", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=5),
     *                             @OA\Property(property="name", type="string", example="Tipos de Personas")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Empleado"),
     *                         @OA\Property(property="description", type="string", example="Persona contratada por la empresa."),
     *                         @OA\Property(property="settings", type="object", example={"order":1}),
     *                         @OA\Property(
     *                             property="document_types",
     *                             type="array",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="id", type="integer", example=10),
     *                                 @OA\Property(property="item_id", type="integer", example=4),
     *                                 @OA\Property(property="name", type="string", example="Cédula de Ciudadanía"),
     *                                 @OA\Property(property="description", type="string", example="Documento nacional de identidad."),
     *                                 @OA\Property(property="settings", type="object",
     *                                     @OA\Property(property="abreviature", type="string", example="CC"),
     *                                     @OA\Property(property="regex", type="string", example="^[0-9]{6,10}$"),
     *                                     @OA\Property(property="message", type="string", example="El documento debe contener entre 6 y 10 dígitos.")
     *                                 ),
     *                                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                             )
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=12),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=2)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los tipos de personas consultados exitosamente."),
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
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener los tipos de personas.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los tipos de personas: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(PersonTypeAllRequest $request)
    {
        try {
            $personTypeAllUseCase = new PersonTypeAllUseCase($this->personTypeRepository);
            $personTypes = new PersonTypeCollection($personTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $personTypes,
                'Los tipos de personas consultados exitosamente.',
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
