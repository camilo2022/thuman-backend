<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalSkill;

use App\Application\ProfileUseCase\ProfessionalSkillUseCase\ProfessionalSkillDeleteUseCase;
use App\Domain\Profile\ProfessionalSkill\Values\ProfessionalSkillId;
use App\Infrastructure\Repositories\ProfessionalSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalSkillDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $professionalSkillRepository;

    public function __construct(ProfessionalSkillRepository $professionalSkillRepository)
    {
        $this->professionalSkillRepository = $professionalSkillRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Profiles/ProfessionalSkills/Delete/{professionalSkillId}",
     *     summary="Eliminar una habilidad profesional",
     *     tags={"Profiles - ProfessionalSkills"},
     *     @OA\Parameter(
     *         name="professionalSkillId",
     *         in="path",
     *         description="ID de la habilidad profesional a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=701)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La habilidad fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La habilidad fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La habilidad no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La habilidad no existe: No query results for model [ProfessionalSkill] 701")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar la habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar la habilidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $professionalSkillId)
    {
        Gate::authorize('deleteProfessionalSkill', $professionalSkillId);
        
        try {
            $professionalSkillId = new ProfessionalSkillId($professionalSkillId);
            $professionalSkillDeleteUseCase = new ProfessionalSkillDeleteUseCase($this->professionalSkillRepository);
            $professionalSkillDeleteUseCase->__invoke($professionalSkillId);
            return $this->successResponse(
                '',
                'La habilidad fue inactivada exitosamente.',
                204
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
