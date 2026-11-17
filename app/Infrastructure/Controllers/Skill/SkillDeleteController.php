<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Skill;

use App\Application\SkillUseCase\SkillDeleteUseCase;
use App\Domain\Skill\Values\SkillId;
use App\Http\Resources\Skill\SkillResource;
use App\Infrastructure\Repositories\SkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SkillDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Skills/Delete/{skillId}",
     *     summary="Eliminar una habilidad",
     *     description="Elimina lógicamente una habilidad por su ID (item_id=32).",
     *     tags={"Skills"},
     *     @OA\Parameter(
     *         name="skillId",
     *         in="path",
     *         required=true,
     *         description="ID de la habilidad a eliminar",
     *         @OA\Schema(type="integer", example=45)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La habilidad fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="skill", type="object",
     *                     @OA\Property(property="id", type="integer", example=45),
     *                     @OA\Property(property="skill_type_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="JavaScript"),
     *                     @OA\Property(property="description", type="string", example="Lenguaje de programación para la web."),
     *                     @OA\Property(property="settings", type="object", nullable=true, example={"level_range":"1-100"}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-10 09:15:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-10 10:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-03-10 10:30:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La habilidad fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
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
     *         description="Error inesperado al eliminar la habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error, intentelo más tarde."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar la habilidad: detalle de la excepción")
     *         )
     *     )
     * )
     */
    public function __invoke(int $skillId)
    {
        Gate::authorize('deleteSkill');

        try {
            $skillId = new SkillId($skillId);
            $skillDeleteUseCase = new SkillDeleteUseCase($this->skillRepository);
            $skill = new SkillResource($skillDeleteUseCase->__invoke($skillId));
            return $this->successResponse(
                $skill,
                'La habilidad fue inactivada exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => 500
                ],
                500
            );
        }
    }
}
