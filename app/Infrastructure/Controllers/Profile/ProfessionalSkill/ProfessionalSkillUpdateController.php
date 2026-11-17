<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalSkill;

use App\Application\ProfileUseCase\ProfessionalSkillUseCase\ProfessionalSkillUpdateUseCase;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillId;
use App\Http\Requests\Profile\ProfessionalSkill\ProfessionalSkillUpdateRequest;
use App\Http\Resources\Profile\ProfessionalSkill\ProfessionalSkillResource;
use App\Infrastructure\Repositories\ProfessionalSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalSkillUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $professionalSkillRepository;

    public function __construct(ProfessionalSkillRepository $professionalSkillRepository)
    {
        $this->professionalSkillRepository = $professionalSkillRepository;
    }

    /**
     * @OA\Put(
     *     path="/Profiles/ProfessionalSkills/Update/{professionalSkillId}",
     *     summary="Actualizar una habilidad profesional",
     *     tags={"Profiles - ProfessionalSkills"},
     *     @OA\Parameter(
     *         name="professionalSkillId",
     *         in="path",
     *         description="ID de la habilidad profesional a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=701)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar la habilidad profesional",
     *         @OA\JsonContent(
     *             required={"profile_id","skill_type_id","skill_id","percentage"},
     *             @OA\Property(property="profile_id", type="integer", example=12, description="Debe existir en profiles.id"),
     *             @OA\Property(property="skill_type_id", type="integer", example=1, description="Debe existir en subitems con item_id=6"),
     *             @OA\Property(property="skill_id", type="integer", example=32, description="Debe existir en subitems con item_id=32"),
     *             @OA\Property(
     *                 property="percentage",
     *                 type="integer",
     *                 example=85,
     *                 description="Validación dinámica por regex según el tipo de habilidad (blanda: 1–5, dura: 1–100)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La habilidad fue actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="professionalSkill", type="object",
     *                     @OA\Property(property="id", type="integer", example=701),
     *                     @OA\Property(property="profile_id", type="integer", example=12),
     *                     @OA\Property(property="skill_type_id", type="integer", example=1),
     *                     @OA\Property(property="skill_id", type="integer", example=32),
     *                     @OA\Property(property="percentage", type="integer", example=85),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 16:20:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La habilidad fue actualizada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La habilidad no existe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(
     *                 property="attributes",
     *                 type="object",
     *                 @OA\Property(property="profile_id", type="string", example="Perfil"),
     *                 @OA\Property(property="skill_type_id", type="string", example="Tipo de habilidad"),
     *                 @OA\Property(property="skill_id", type="string", example="Habilidad"),
     *                 @OA\Property(property="percentage", type="string", example="Porcentaje")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="percentage",
     *                     type="array",
     *                     @OA\Items(type="string", example="Seleccione una calificación de 1 a 5 estrellas.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar la habilidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ProfessionalSkillUpdateRequest $request, int $professionalSkillId)
    {
        Gate::authorize('updateProfessionalExperience', $request->input('profile_id'));
        
        try {
            $professionalSkillId = new ProfessionalSkillId($professionalSkillId ?? null);
            $professionalSkillUpdateUseCase = new ProfessionalSkillUpdateUseCase($this->professionalSkillRepository);
            $professionalSkill = new ProfessionalSkillResource($professionalSkillUpdateUseCase->__invoke($request, $professionalSkillId));
            return $this->successResponse(
                $professionalSkill,
                'La habilidad fue actualizado exitosamente.',
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
