<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Skill;

use App\Application\SkillUseCase\SkillUpdateUseCase;
use App\Domain\Skill\Values\SkillId;
use App\Http\Requests\Skill\SkillUpdateRequest;
use App\Http\Resources\Skill\SkillResource;
use App\Infrastructure\Repositories\SkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class SkillUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    /**
     * @OA\Put(
     *     path="/Skills/Update/{skillId}",
     *     summary="Actualizar una habilidad",
     *     description="Actualiza una habilidad existente y su relación con el tipo de habilidad.",
     *     tags={"Skills"},
     *     @OA\Parameter(
     *         name="skillId",
     *         in="path",
     *         required=true,
     *         description="ID de la habilidad a actualizar",
     *         @OA\Schema(type="integer", example=45)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"skill_type_id","item_id","name","description"},
     *             @OA\Property(property="skill_type_id", type="integer", description="ID del tipo de habilidad (item_id=6)", example=1),
     *             @OA\Property(property="item_id", type="integer", description="Categorización fija para Skills", example=32),
     *             @OA\Property(property="name", type="string", maxLength=80, description="Nombre de la habilidad (único dentro de item_id=32)", example="JavaScript"),
     *             @OA\Property(property="description", type="string", maxLength=255, description="Descripción de la habilidad", example="Lenguaje de programación para la web.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La habilidad fue actualizado exitosamente.",
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
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La habilidad fue actualizado exitosamente."),
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
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object", example={"skill_type_id":"Tipo de habilidad","item_id":"Categorización","name":"Nombre","description":"Descripción"}),
     *             @OA\Property(property="errors", type="object",
     *                 example={
     *                     "skill_type_id":{"No existe en la base de datos."},
     *                     "item_id":{"Debe ser un valor válido."},
     *                     "name":{"Ya ha sido tomado."},
     *                     "description":{"No debe exceder los 255 caracteres."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error, intentelo más tarde."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar la habilidad: detalle de la excepción")
     *         )
     *     )
     * )
     */
    public function __invoke(SkillUpdateRequest $request, int $skillId)
    {
        Gate::authorize('updateSkill');

        try {
            $skillId = new SkillId($skillId ?? null);
            $skillUpdateUseCase = new SkillUpdateUseCase($this->skillRepository);
            $skill = new SkillResource($skillUpdateUseCase->__invoke($request, $skillId));
            return $this->successResponse(
                $skill,
                'La habilidad fue actualizada exitosamente.',
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
