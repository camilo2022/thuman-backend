<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Skill;

use App\Application\SkillUseCase\SkillFindUseCase;
use App\Domain\Skill\Values\SkillId;
use App\Http\Resources\Skill\SkillResource;
use App\Infrastructure\Repositories\SkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class SkillFindController
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
     *     path="/Skills/Find/{skillId}",
     *     summary="Obtener una habilidad por ID",
     *     description="Retorna la información de una habilidad (incluye soft-deleted).",
     *     tags={"Skills"},
     *     @OA\Parameter(
     *         name="skillId",
     *         in="path",
     *         required=true,
     *         description="ID de la habilidad a consultar",
     *         @OA\Schema(type="integer", example=25)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La habilidad fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="skill", type="object",
     *                     @OA\Property(property="id", type="integer", example=25),
     *                     @OA\Property(property="skill_type_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="JavaScript"),
     *                     @OA\Property(property="description", type="string", example="Lenguaje de programación para la web."),
     *                     @OA\Property(property="settings", type="object", example={}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-02-01 12:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-10 09:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La habilidad fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Habilidad no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error, intentelo más tarde."),
     *             @OA\Property(property="error", type="string", example="La habilidad no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error, intentelo más tarde."),
     *             @OA\Property(property="error", type="string", example="Error al buscar la habilidad: detalle de la excepción")
     *         )
     *     )
     * )
     */
    public function __invoke(int $skillId)
    {
        Gate::authorize('findSkill');

        try {
            $skillId = new SkillId($skillId ?? null);
            $skillFindUseCase = new SkillFindUseCase($this->skillRepository);
            $skill = new SkillResource($skillFindUseCase->__invoke($skillId));
            return $this->successResponse(
                $skill,
                'La habilidad fue encontrada exitosamente.',
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
