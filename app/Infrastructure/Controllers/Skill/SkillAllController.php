<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Skill;

use App\Application\SkillUseCase\SkillAllUseCase;
use App\Http\Requests\Skill\SkillAllRequest;
use App\Http\Resources\Skill\SkillCollection;
use App\Infrastructure\Repositories\SkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class SkillAllController
{
    use ApiResponser;
    use ApiMessage;
    private $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    /**
     * @OA\Get(
     *     path="/Skills/All",
     *     summary="Listar habilidades",
     *     description="Obtiene el listado paginado de habilidades con filtros opcionales.",
     *     tags={"Skills"},
     *     @OA\Parameter(
     *         name="search", in="query", required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string", maxLength=100, example="Java")
     *     ),
     *     @OA\Parameter(
     *         name="perPage", in="query", required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Parameter(
     *         name="skill_type_id", in="query", required=false,
     *         description="ID del tipo de habilidad (de subitems, item_id=6)",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Las habilidades consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="skills", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=10),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=32),
     *                             @OA\Property(property="name", type="string", example="Skills"),
     *                             @OA\Property(property="description", type="string", example="Catálogo de habilidades")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Java SE"),
     *                         @OA\Property(property="description", type="string", example="Desarrollo con Java Standard Edition"),
     *                         @OA\Property(property="settings", type="object", example={}),
     *                         @OA\Property(property="skill_type", type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="Dura"),
     *                             @OA\Property(property="description", type="string", example="Habilidades técnicas"),
     *                             @OA\Property(property="settings", type="object", example={"regex":"^(?:100|[1-9]\\d?)$"}),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:00:00"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-12 10:00:00"),
     *                             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-01 08:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-05 12:30:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=42),
     *                         @OA\Property(property="count", type="integer", example=15),
     *                         @OA\Property(property="per_page", type="integer", example=15),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=3)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las habilidades consultados exitosamente."),
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
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página"),
     *                 @OA\Property(property="skill_type_id", type="string", example="Tipo de habilidad")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="perPage", type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 ),
     *                 @OA\Property(property="skill_type_id", type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las habilidades.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error, intentelo más tarde."),
     *             @OA\Property(property="error", type="string", example="Error al obtener las habilidades: detalle de la excepción")
     *         )
     *     )
     * )
     */
    public function __invoke(SkillAllRequest $request)
    {
        Gate::authorize('allSkill');

        try {
            $skillAllUseCase = new SkillAllUseCase($this->skillRepository);
            $skills = new SkillCollection($skillAllUseCase->__invoke($request));
            return $this->successResponse(
                $skills,
                'Las habilidades consultados exitosamente.',
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
