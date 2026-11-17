<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\AcademicQualification;

use App\Application\ProfileUseCase\AcademicQualificationUseCase\AcademicQualificationAllUseCase;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\AcademicQualification\AcademicQualificationAllRequest;
use App\Http\Resources\Profile\AcademicQualification\AcademicQualificationCollection;
use App\Infrastructure\Repositories\AcademicQualificationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class AcademicQualificationAllController
{
    use ApiResponser;
    use ApiMessage;
    private $academicQualificationRepository;

    public function __construct(AcademicQualificationRepository $academicQualificationRepository)
    {
        $this->academicQualificationRepository = $academicQualificationRepository;
    }

    /**
     * @OA\Get(
     *     path="/Profiles/AcademicQualifications/All/{profileId}",
     *     summary="Listar estudios de formación de un perfil",
     *     tags={"Profiles - AcademicQualifications"},
     *     @OA\Parameter(
     *         name="profileId",
     *         in="path",
     *         description="ID del perfil al que pertenecen los estudios de formación",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=12)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda para filtrar estudios de formación",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=100, example="Ingeniería")
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
     *         description="Listado de estudios de formación obtenido exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="academicQualifications", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=101),
     *                         @OA\Property(property="profile", type="object", example={"id":12,"person_id":345}),
     *                         @OA\Property(property="city", type="object", example={"id":5,"name":"Bogotá"}),
     *                         @OA\Property(property="academic_level", type="object", example={"id":3,"name":"Universitario"}),
     *                         @OA\Property(property="academic_institution", type="string", example="Universidad Nacional"),
     *                         @OA\Property(property="academic_title", type="string", example="Ingeniería de Sistemas"),
     *                         @OA\Property(property="formation_status", type="object", example={"id":2,"name":"Finalizado"}),
     *                         @OA\Property(property="start_date", type="string", format="date-time", example="2020-01-10 00:00:00"),
     *                         @OA\Property(property="end_date", type="string", format="date-time", example="2024-12-15 00:00:00"),
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
     *             @OA\Property(property="message", type="string", example="Los estudios de formación fueron consultados exitosamente."),
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
     *         description="Error inesperado al obtener los estudios de formación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener los estudios de formación: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(AcademicQualificationAllRequest $request, int $profileId)
    {
        Gate::authorize('allAcademicQualification', $profileId);
        
        try {
            $profileId = new ProfileId($profileId ?? null);
            $academicQualificationAllUseCase = new AcademicQualificationAllUseCase($this->academicQualificationRepository);
            $academicQualifications = new AcademicQualificationCollection($academicQualificationAllUseCase->__invoke($request, $profileId));
            return $this->successResponse(
                $academicQualifications,
                'Los estudios de formación fueron consultados exitosamente.',
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
