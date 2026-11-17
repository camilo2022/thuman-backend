<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalExperience;

use App\Application\ProfileUseCase\ProfessionalExperienceUseCase\ProfessionalExperienceAllUseCase;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\ProfessionalExperience\ProfessionalExperienceAllRequest;
use App\Http\Resources\Profile\ProfessionalExperience\ProfessionalExperienceCollection;
use App\Infrastructure\Repositories\ProfessionalExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalExperienceAllController
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
     *     path="/Profiles/ProfessionalExperiences/All/{profileId}",
     *     summary="Listar experiencias profesionales de un perfil",
     *     tags={"Profiles - ProfessionalExperiences"},
     *     @OA\Parameter(
     *         name="profileId",
     *         in="path",
     *         description="ID del perfil al que pertenecen las experiencias profesionales",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=12)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda para filtrar experiencias profesionales",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=100, example="Desarrollador")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Número de registros por página",
     *         required=false,
     *         @OA\Schema(type="integer", format="int32", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de experiencias profesionales obtenido exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="professionalExperiences", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=201),
     *                         @OA\Property(property="profile", type="object", example={"id":12,"person_id":345}),
     *                         @OA\Property(property="company_name", type="string", example="Tech Solutions S.A."),
     *                         @OA\Property(property="supervisor_name", type="string", example="Carlos Pérez"),
     *                         @OA\Property(property="supervisor_dialing_country", type="object", example={"code":"+57","name":"Colombia"}),
     *                         @OA\Property(property="supervisor_phone_number", type="string", example="3101234567"),
     *                         @OA\Property(property="city", type="object", example={"id":5,"name":"Bogotá"}),
     *                         @OA\Property(property="company_address", type="string", example="Calle 123 #45-67"),
     *                         @OA\Property(property="start_date", type="string", format="date-time", example="2021-01-10 00:00:00"),
     *                         @OA\Property(property="end_date", type="string", format="date-time", example="2023-06-15 00:00:00"),
     *                         @OA\Property(property="position", type="string", example="Desarrollador Backend"),
     *                         @OA\Property(property="work_arrangement", type="object", example={"id":1,"name":"Remoto"}),
     *                         @OA\Property(property="position_description", type="string", example="Desarrollo y mantenimiento de microservicios en Laravel y PostgreSQL."),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=50),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=5)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las experiencias profesionales fueron consultadas exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
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
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="search",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser una cadena de caracteres.")
     *                 ),
     *                 @OA\Property(
     *                     property="perPage",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las experiencias profesionales.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener las experiencias profesionales: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ProfessionalExperienceAllRequest $request, int $profileId)
    {
        Gate::authorize('allProfessionalExperience', $profileId);

        try {
            $profileId = new ProfileId($profileId ?? null);
            $professionalExperienceAllUseCase = new ProfessionalExperienceAllUseCase($this->professionalExperienceRepository);
            $professionalExperiences = new ProfessionalExperienceCollection($professionalExperienceAllUseCase->__invoke($request, $profileId));
            return $this->successResponse(
                $professionalExperiences,
                'Las experiencias profesionales fueron consultados exitosamente.',
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
