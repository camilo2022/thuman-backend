<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\AcademicQualification;

use App\Application\ProfileUseCase\AcademicQualificationUseCase\AcademicQualificationDeleteUseCase;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationId;
use App\Infrastructure\Repositories\AcademicQualificationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class AcademicQualificationDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $academicQualificationRepository;

    public function __construct(AcademicQualificationRepository $academicQualificationRepository)
    {
        $this->academicQualificationRepository = $academicQualificationRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Profiles/AcademicQualifications/Delete/{academicQualificationId}",
     *     summary="Eliminar un estudio de formación",
     *     tags={"Profiles - AcademicQualifications"},
     *     @OA\Parameter(
     *         name="academicQualificationId",
     *         in="path",
     *         description="ID del estudio de formación a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=101)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El estudio de formación fue eliminado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="string", example=""),
     *             @OA\Property(property="message", type="string", example="El estudio de formación fue eliminado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
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
     *         description="Error inesperado al eliminar el estudio de formación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar el estudio de formación: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $academicQualificationId)
    {
        Gate::authorize('deleteAcademicQualification', $academicQualificationId);
        
        try {
            $academicQualificationId = new AcademicQualificationId($academicQualificationId);
            $academicQualificationDeleteUseCase = new AcademicQualificationDeleteUseCase($this->academicQualificationRepository);
            $academicQualificationDeleteUseCase->__invoke($academicQualificationId);
            return $this->successResponse(
                '',
                'El estudio de formación fue eliminado exitosamente.',
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
