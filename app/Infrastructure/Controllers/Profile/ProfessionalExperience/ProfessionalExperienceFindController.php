<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalExperience;

use App\Application\ProfileUseCase\ProfessionalExperienceUseCase\ProfessionalExperienceFindUseCase;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceId;
use App\Http\Resources\Profile\ProfessionalExperience\ProfessionalExperienceResource;
use App\Infrastructure\Repositories\ProfessionalExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalExperienceFindController
{
    use ApiResponser;
    use ApiMessage;
    private $professionalExperienceRepository;

    public function __construct(ProfessionalExperienceRepository $professionalExperienceRepository)
    {
        $this->professionalExperienceRepository = $professionalExperienceRepository;
    }

    /**
     * @OA\Get(
     *     path="/Profiles/ProfessionalExperiences/Find/{professionalExperienceId}",
     *     summary="Buscar una experiencia profesional",
     *     tags={"Profiles - ProfessionalExperiences"},
     *     @OA\Parameter(
     *         name="professionalExperienceId",
     *         in="path",
     *         description="ID de la experiencia profesional",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=201)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La experiencia profesional fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="professionalExperience", type="object",
     *                     @OA\Property(property="id", type="integer", example=201),
     *                     @OA\Property(property="profile_id", type="integer", example=12),
     *                     @OA\Property(property="company_name", type="string", example="Tech Solutions S.A."),
     *                     @OA\Property(property="supervisor_name", type="string", example="Carlos Pérez"),
     *                     @OA\Property(property="supervisor_dialing_country_id", type="integer", example=57),
     *                     @OA\Property(property="supervisor_phone_number", type="string", example="3101234567"),
     *                     @OA\Property(property="city_id", type="integer", example=5),
     *                     @OA\Property(property="company_address", type="string", example="Calle 123 #45-67"),
     *                     @OA\Property(property="start_date", type="string", format="date-time", example="2021-01-10 00:00:00"),
     *                     @OA\Property(property="end_date", type="string", format="date-time", example="2023-06-15 00:00:00"),
     *                     @OA\Property(property="position", type="string", example="Desarrollador Backend"),
     *                     @OA\Property(property="job_arrangement_id", type="integer", example=1),
     *                     @OA\Property(property="position_description", type="string", example="Desarrollo y mantenimiento de microservicios en Laravel y PostgreSQL."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:30:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La experiencia profesional fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la experiencia profesional.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La experiencia profesional no existe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la experiencia profesional.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar la experiencia profesional: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $professionalExperienceId)
    {
        Gate::authorize('findProfessionalExperience', $professionalExperienceId);
        
        try {
            $professionalExperienceId = new ProfessionalExperienceId($professionalExperienceId ?? null);
            $professionalExperienceFindUseCase = new ProfessionalExperienceFindUseCase($this->professionalExperienceRepository);
            $professionalExperience = new ProfessionalExperienceResource($professionalExperienceFindUseCase->__invoke($professionalExperienceId));
            return $this->successResponse(
                $professionalExperience,
                'La experiencia profesional fue encontrado exitosamente.',
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
