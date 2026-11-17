<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\AcademicQualification;

use App\Application\ProfileUseCase\AcademicQualificationUseCase\AcademicQualificationFindUseCase;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationId;
use App\Http\Resources\Profile\AcademicQualification\AcademicQualificationResource;
use App\Infrastructure\Repositories\AcademicQualificationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class AcademicQualificationFindController
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
     *     path="/Profiles/AcademicQualifications/Find/{academicQualificationId}",
     *     summary="Buscar un estudio de formación",
     *     tags={"Profiles - AcademicQualifications"},
     *     @OA\Parameter(
     *         name="academicQualificationId",
     *         in="path",
     *         description="ID del estudio de formación",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El estudio de formación fue encontrado exitosamente.",
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
     *                     @OA\Property(property="start_date", type="string", format="date-time", example="2020-01-10 00:00:00"),
     *                     @OA\Property(property="end_date", type="string", format="date-time", example="2024-12-15 00:00:00"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El estudio de formación fue encontrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
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
     *         description="Error inesperado al buscar el estudio de formación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar el estudio de formación: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $academicQualificationId)
    {
        Gate::authorize('findAcademicQualification', $academicQualificationId);
        
        try {
            $academicQualificationId = new AcademicQualificationId($academicQualificationId ?? null);
            $academicQualificationFindUseCase = new AcademicQualificationFindUseCase($this->academicQualificationRepository);
            $academicQualification = new AcademicQualificationResource($academicQualificationFindUseCase->__invoke($academicQualificationId));
            return $this->successResponse(
                $academicQualification,
                'El estudio de formación fue encontrado exitosamente.',
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
