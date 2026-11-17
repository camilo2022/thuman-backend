<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalExperience;

use App\Application\ProfileUseCase\ProfessionalExperienceUseCase\ProfessionalExperienceStoreUseCase;
use App\Http\Requests\Profile\ProfessionalExperience\ProfessionalExperienceStoreRequest;
use App\Http\Resources\Profile\ProfessionalExperience\ProfessionalExperienceResource;
use App\Infrastructure\Repositories\ProfessionalExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalExperienceStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $professionalExperienceRepository;

    public function __construct(ProfessionalExperienceRepository $professionalExperienceRepository)
    {
        $this->professionalExperienceRepository = $professionalExperienceRepository;
    }

    /**
     * @OA\Post(
     *     path="/Profiles/ProfessionalExperiences/Store",
     *     summary="Registrar una experiencia profesional",
     *     tags={"Profiles - ProfessionalExperiences"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para registrar una experiencia profesional",
     *         @OA\JsonContent(
     *             required={"profile_id","company_name","supervisor_name","supervisor_dialing_country_id","supervisor_phone_number","city_id","company_address","start_date","position","work_arrangement_id","position_description"},
     *             @OA\Property(property="profile_id", type="integer", format="int64", example=12, description="Debe existir en profiles.id"),
     *             @OA\Property(property="company_name", type="string", maxLength=255, example="Tech Solutions S.A."),
     *             @OA\Property(property="supervisor_name", type="string", maxLength=255, example="Carlos Pérez"),
     *             @OA\Property(property="supervisor_dialing_country_id", type="integer", example=57, description="Debe existir en countries.id"),
     *             @OA\Property(property="supervisor_phone_number", type="string", maxLength=30, example="3101234567"),
     *             @OA\Property(property="city_id", type="integer", format="int64", example=5, description="Debe existir en cities.id"),
     *             @OA\Property(property="company_address", type="string", maxLength=75, example="Calle 123 #45-67"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2021-01-10"),
     *             @OA\Property(property="end_date", type="string", format="date", nullable=true, example="2023-06-15"),
     *             @OA\Property(property="position", type="string", maxLength=50, example="Desarrollador Backend"),
     *             @OA\Property(property="work_arrangement_id", type="integer", example=1, description="Debe existir en subitems con item_id=3 (Modalidad de trabajo)"),
     *             @OA\Property(property="position_description", type="string", maxLength=500, example="Desarrollo y mantenimiento de microservicios en Laravel y PostgreSQL.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La experiencia profesional fue registrada exitosamente.",
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
     *                     @OA\Property(property="start_date", type="string", format="date", example="2021-01-10"),
     *                     @OA\Property(property="end_date", type="string", format="date", example="2023-06-15"),
     *                     @OA\Property(property="position", type="string", example="Desarrollador Backend"),
     *                     @OA\Property(property="job_arrangement_id", type="integer", example=1),
     *                     @OA\Property(property="position_description", type="string", example="Desarrollo y mantenimiento de microservicios en Laravel y PostgreSQL."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 12:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 12:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La experiencia profesional fue registrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
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
     *                 @OA\Property(property="company_name", type="string", example="Empresa"),
     *                 @OA\Property(property="supervisor_name", type="string", example="Jefe inmediato"),
     *                 @OA\Property(property="supervisor_dialing_country_id", type="string", example="Codigo de pais"),
     *                 @OA\Property(property="supervisor_phone_number", type="string", example="Número de teléfono"),
     *                 @OA\Property(property="city_id", type="string", example="Ciudad"),
     *                 @OA\Property(property="company_address", type="string", example="Dirección"),
     *                 @OA\Property(property="start_date", type="string", example="Fecha de inicio"),
     *                 @OA\Property(property="end_date", type="string", example="Fecha de finalización"),
     *                 @OA\Property(property="position", type="string", example="Cargo"),
     *                 @OA\Property(property="work_arrangement_id", type="string", example="Modalidad de trabajo"),
     *                 @OA\Property(property="position_description", type="string", example="Descripción de labores")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="company_name",
     *                     type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 255 caracteres.")
     *                 ),
     *                 @OA\Property(
     *                     property="start_date",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe tener un formato de fecha Y-m-d.")
     *                 ),
     *                 @OA\Property(
     *                     property="position_description",
     *                     type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 500 caracteres.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar la experiencia profesional.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al guardar la experiencia profesional: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ProfessionalExperienceStoreRequest $request)
    {
        Gate::authorize('storeProfessionalExperience', $request->input('profile_id'));
        
        try {
            $professionalExperienceStoreUseCase = new ProfessionalExperienceStoreUseCase($this->professionalExperienceRepository);
            $professionalExperience = new ProfessionalExperienceResource($professionalExperienceStoreUseCase->__invoke($request));
            return $this->successResponse(
                $professionalExperience,
                'La experiencia profesional fue registrado exitosamente.',
                201
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
