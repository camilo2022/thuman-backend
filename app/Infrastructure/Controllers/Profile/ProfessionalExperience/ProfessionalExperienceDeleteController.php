<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalExperience;

use App\Application\ProfileUseCase\ProfessionalExperienceUseCase\ProfessionalExperienceDeleteUseCase;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceId;
use App\Infrastructure\Repositories\ProfessionalExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalExperienceDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $professionalExperienceRepository;

    public function __construct(ProfessionalExperienceRepository $professionalExperienceRepository)
    {
        $this->professionalExperienceRepository = $professionalExperienceRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Profiles/ProfessionalExperiences/Delete/{professionalExperienceId}",
     *     summary="Eliminar una experiencia profesional",
     *     tags={"Profiles - ProfessionalExperiences"},
     *     @OA\Parameter(
     *         name="professionalExperienceId",
     *         in="path",
     *         required=true,
     *         description="ID de la experiencia profesional a eliminar",
     *         @OA\Schema(type="integer", example=201)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La experiencia profesional fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="La experiencia profesional fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe la experiencia profesional.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La experiencia profesional no existe: No query results for model [ProfessionalExperience].")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar la experiencia profesional.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar la experiencia profesional: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $professionalExperienceId)
    {
        Gate::authorize('deleteProfessionalExperience', $professionalExperienceId);
        
        try {
            $professionalExperienceId = new ProfessionalExperienceId($professionalExperienceId);
            $professionalExperienceDeleteUseCase = new ProfessionalExperienceDeleteUseCase($this->professionalExperienceRepository);
            $professionalExperienceDeleteUseCase->__invoke($professionalExperienceId);
            return $this->successResponse(
                '',
                'La experiencia profesional fue inactivada exitosamente.',
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
