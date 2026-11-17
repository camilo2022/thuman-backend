<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\AcademicQualification;

use App\Application\ProfileUseCase\AcademicQualificationUseCase\AcademicQualificationUpdateUseCase;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationId;
use App\Http\Requests\Profile\AcademicQualification\AcademicQualificationUpdateRequest;
use App\Http\Resources\Profile\AcademicQualification\AcademicQualificationResource;
use App\Infrastructure\Repositories\AcademicQualificationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class AcademicQualificationUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $academicQualificationRepository;

    public function __construct(AcademicQualificationRepository $academicQualificationRepository)
    {
        $this->academicQualificationRepository = $academicQualificationRepository;
    }

    /**
     * @OA\Put(
     *     path="/Profiles/AcademicQualifications/Update/{academicQualificationId}",
     *     summary="Actualizar un estudio de formación",
     *     tags={"Profiles - AcademicQualifications"},
     *     @OA\Parameter(
     *         name="academicQualificationId",
     *         in="path",
     *         description="ID del estudio de formación a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=101)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el estudio de formación",
     *         @OA\JsonContent(
     *             required={"profile_id","city_id","academic_level_id","academic_institution","academic_title","formation_status_id","start_date"},
     *             @OA\Property(property="profile_id", type="integer", format="int64", example=12, description="Debe existir en profiles.id"),
     *             @OA\Property(property="city_id", type="integer", format="int64", example=5, description="Debe existir en cities.id"),
     *             @OA\Property(property="academic_level_id", type="integer", example=3, description="Debe existir en subitems con item_id=8"),
     *             @OA\Property(property="academic_institution", type="string", maxLength=255, example="Universidad Nacional"),
     *             @OA\Property(property="academic_title", type="string", maxLength=255, example="Ingeniería de Sistemas"),
     *             @OA\Property(property="formation_status_id", type="integer", example=2, description="Debe existir en subitems con item_id=7"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2020-01-10"),
     *             @OA\Property(property="end_date", type="string", format="date", nullable=true, example="2024-12-15")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El estudio de formación fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="academicQualification", type="object",
     *                     @OA\Property(property="id", type="integer", example=101),
     *                     @OA\Property(property="profile_id", type="integer", example=12),
     *                     @OA\Property(property="city_id", type="integer", example=5),
     *                     @OA\Property(property="academic_level_id", type="integer", example=3),
     *                     @OA\Property(property="academic_institution", type="string", example="Universidad Nacional"),
     *                     @OA\Property(property="academic_title", type="string", example="Ingeniería de Sistemas"),
     *                     @OA\Property(property="formation_status_id", type="integer", example=2),
     *                     @OA\Property(property="start_date", type="string", format="date", example="2020-01-10"),
     *                     @OA\Property(property="end_date", type="string", format="date", example="2024-12-15"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El estudio de formación fue actualizado exitosamente."),
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
     *                 @OA\Property(property="profile_id", type="string", example="Perfil"),
     *                 @OA\Property(property="city_id", type="string", example="Ciudad"),
     *                 @OA\Property(property="academic_level_id", type="string", example="Nivel academico"),
     *                 @OA\Property(property="academic_institution", type="string", example="Institución academica"),
     *                 @OA\Property(property="academic_title", type="string", example="Titulo academico"),
     *                 @OA\Property(property="formation_status_id", type="string", example="Estado de la formacion"),
     *                 @OA\Property(property="start_date", type="string", example="Fecha de inicio"),
     *                 @OA\Property(property="end_date", type="string", example="Fecha de finalización")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="academic_institution",
     *                     type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 255 caracteres.")
     *                 ),
     *                 @OA\Property(
     *                     property="start_date",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe tener un formato de fecha Y-m-d.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el estudio de formación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El estudio de formación no existe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el estudio de formación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar el estudio de formación: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(AcademicQualificationUpdateRequest $request, int $academicQualificationId)
    {
        Gate::authorize('updateAcademicQualification', $academicQualificationId);
        
        try {
            $academicQualificationId = new AcademicQualificationId($academicQualificationId ?? null);
            $academicQualificationUpdateUseCase = new AcademicQualificationUpdateUseCase($this->academicQualificationRepository);
            $academicQualification = new AcademicQualificationResource($academicQualificationUpdateUseCase->__invoke($request, $academicQualificationId));
            return $this->successResponse(
                $academicQualification,
                'El estudio de formación fue actualizado exitosamente.',
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
