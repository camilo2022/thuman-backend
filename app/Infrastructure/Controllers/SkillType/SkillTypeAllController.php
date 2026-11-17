<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\SkillType;

use App\Application\SkillTypeUseCase\SkillTypeAllUseCase;
use App\Http\Requests\SkillType\SkillTypeAllRequest;
use App\Http\Resources\SkillType\SkillTypeCollection;
use App\Infrastructure\Repositories\SkillTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SkillTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $skillTypeRepository;

    public function __construct(SkillTypeRepository $skillTypeRepository)
    {
        $this->skillTypeRepository = $skillTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/SkillTypes/All",
     *     summary="Listar tipos de habilidades",
     *     description="Retorna un listado paginado de tipos de habilidades (con sus habilidades asociadas y metadatos).",
     *     tags={"SkillTypes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string", maxLength=100, example="Blanda")
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
     *         description="Los tipos de habilidades consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="skillTypes",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="item", type="object", example={"id":6,"name":"Tipo de Habilidades"}),
     *                         @OA\Property(property="name", type="string", example="Blanda"),
     *                         @OA\Property(property="description", type="string", example="Habilidades sociales y actitudinales."),
     *                         @OA\Property(property="settings", type="object", example={"regex":"^[1-5]$","message":"Seleccione una calificación de 1 a 5 estrellas."}),
     *                         @OA\Property(
     *                             property="skills",
     *                             type="array",
     *                             description="Lista de habilidades asociadas al tipo",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=101),
     *                                 @OA\Property(property="name", type="string", example="Comunicación"),
     *                                 @OA\Property(property="description", type="string", example="Capacidad de expresar ideas claramente."),
     *                                 @OA\Property(property="settings", type="object", example={}),
     *                                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                             )
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(
     *                         property="pagination",
     *                         type="object",
     *                         @OA\Property(property="total", type="integer", example=42),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=5)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los tipos de habilidades consultados exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object", example={"search":"Término de búsqueda","perPage":"Número de registros por página"}),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "search":{"Debe ser una cadena de caracteres."},
     *                     "perPage":{"Debe ser un valor numérico."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener los tipos de habilidades.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los tipos de habilidades: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(SkillTypeAllRequest $request)
    {
        try {
            $skillTypeAllUseCase = new SkillTypeAllUseCase($this->skillTypeRepository);
            $skillTypes = new SkillTypeCollection($skillTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $skillTypes,
                'Los tipos de habilidades consultados exitosamente.',
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
