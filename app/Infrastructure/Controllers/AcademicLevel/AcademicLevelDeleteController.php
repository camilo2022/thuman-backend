<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AcademicLevel;

use App\Application\AcademicLevelUseCase\AcademicLevelDeleteUseCase;
use App\Domain\AcademicLevel\Values\AcademicLevelId;
use App\Http\Resources\AcademicLevel\AcademicLevelResource;
use App\Infrastructure\Repositories\AcademicLevelRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AcademicLevelDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $academicLevelRepository;

    public function __construct(AcademicLevelRepository $academicLevelRepository)
    {
        $this->academicLevelRepository = $academicLevelRepository;
    }

    /**
     * @OA\Delete(
     *     path="/AcademicLevels/Delete/{academicLevelId}",
     *     summary="Eliminar un nivel académico",
     *     description="Elimina lógicamente un nivel académico por su ID.",
     *     tags={"AcademicLevels"},
     *     @OA\Parameter(
     *         name="academicLevelId",
     *         in="path",
     *         required=true,
     *         description="ID del nivel académico a eliminar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El nivel académico fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="academicLevels", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=8),
     *                     @OA\Property(property="name", type="string", example="Licenciatura"),
     *                     @OA\Property(property="description", type="string", example="Programa de educación universitaria."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-03-03 11:20:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El nivel academico fue eliminada exitosamente."),
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
     *         description="Error inesperado al eliminar el nivel académico.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar el nivel academico: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $academicLevelId)
    {
        try {
            $academicLevelId = new AcademicLevelId($academicLevelId);
            $academicLevelDeleteUseCase = new AcademicLevelDeleteUseCase($this->academicLevelRepository);
            $academicLevel = new AcademicLevelResource($academicLevelDeleteUseCase->__invoke($academicLevelId));
            return $this->successResponse(
                $academicLevel,
                'El nivel academico fue inactivado exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
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
