<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalSkill;

use App\Application\ProfileUseCase\ProfessionalSkillUseCase\ProfessionalSkillFindUseCase;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillId;
use App\Http\Resources\Profile\ProfessionalSkill\ProfessionalSkillResource;
use App\Infrastructure\Repositories\ProfessionalSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalSkillFindController
{
    use ApiResponser;
    use ApiMessage;
    private $professionalSkillRepository;

    public function __construct(ProfessionalSkillRepository $professionalSkillRepository)
    {
        $this->professionalSkillRepository = $professionalSkillRepository;
    }

    /**
     * @OA\Get(
     *     path="/Profiles/ProfessionalSkills/Find/{professionalSkillId}",
     *     summary="Buscar una habilidad profesional",
     *     tags={"Profiles - ProfessionalSkills"},
     *     @OA\Parameter(
     *         name="professionalSkillId",
     *         in="path",
     *         description="ID de la habilidad profesional a consultar",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=701)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La habilidad fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="professionalSkill", type="object",
     *                     @OA\Property(property="id", type="integer", example=701),
     *                     @OA\Property(property="profile_id", type="integer", example=12),
     *                     @OA\Property(property="skill_type_id", type="integer", example=1),
     *                     @OA\Property(property="skill_id", type="integer", example=10),
     *                     @OA\Property(property="percentage", type="integer", example=85),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 15:40:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La habilidad fue encontrada exitosamente."),
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
     *         response=500,
     *         description="Error inesperado al buscar la habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar la habilidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $professionalSkillId)
    {
        Gate::authorize('findProfessionalSkill', $professionalSkillId);
        
        try {
            $professionalSkillId = new ProfessionalSkillId($professionalSkillId ?? null);
            $professionalSkillFindUseCase = new ProfessionalSkillFindUseCase($this->professionalSkillRepository);
            $professionalSkill = new ProfessionalSkillResource($professionalSkillFindUseCase->__invoke($professionalSkillId));
            return $this->successResponse(
                $professionalSkill,
                'La habilidad fue encontrado exitosamente.',
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
