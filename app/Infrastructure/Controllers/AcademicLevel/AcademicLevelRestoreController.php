<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AcademicLevel;

use App\Application\AcademicLevelUseCase\AcademicLevelRestoreUseCase;
use App\Domain\AcademicLevel\Values\AcademicLevelId;
use App\Http\Resources\AcademicLevel\AcademicLevelResource;
use App\Infrastructure\Repositories\AcademicLevelRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AcademicLevelRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $academicLevelRepository;

    public function __construct(AcademicLevelRepository $academicLevelRepository)
    {
        $this->academicLevelRepository = $academicLevelRepository;
    }

    /**
     * @OA\Patch(
     *     path="/AcademicLevels/Restore/{academicLevelId}",
     *     summary="Restaurar un nivel académico",
     *     description="Restaura un nivel académico previamente eliminado lógicamente por su ID.",
     *     tags={"AcademicLevels"},
     *     @OA\Parameter(
     *         name="academicLevelId",
     *         in="path",
     *         required=true,
     *         description="ID del nivel académico a restaurar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El nivel académico fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="academicLevels", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=8),
     *                     @OA\Property(property="name", type="string", example="Licenciatura"),
     *                     @OA\Property(property="description", type="string", example="Programa de educación universitaria."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El nivel academico fue restaurada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nivel académico no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El nivel academico no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al restaurar el nivel académico.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al restaurar el nivel academico: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $academicLevelId)
    {
        try {
            $academicLevelId = new AcademicLevelId($academicLevelId);
            $academicLevelRestoreUseCase = new AcademicLevelRestoreUseCase($this->academicLevelRepository);
            $academicLevel = new AcademicLevelResource($academicLevelRestoreUseCase->__invoke($academicLevelId));
            return $this->successResponse(
                $academicLevel,
                'El nivel academico fue activada exitosamente.',
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
