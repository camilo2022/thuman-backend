<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\HealthEntity;

use App\Application\HealthEntityUseCase\HealthEntityDeleteUseCase;
use App\Domain\HealthEntity\Values\HealthEntityId;
use App\Http\Resources\HealthEntity\HealthEntityResource;
use App\Infrastructure\Repositories\HealthEntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class HealthEntityDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $healthEntityRepository;

    public function __construct(HealthEntityRepository $healthEntityRepository)
    {
        $this->healthEntityRepository = $healthEntityRepository;
    }

    /**
     * @OA\Delete(
     *     path="/HealthEntities/Delete/{healthEntityId}",
     *     summary="Eliminar entidad de salud",
     *     description="Elimina (borrado lógico) una entidad de salud por su ID.",
     *     tags={"HealthEntities"},
     *     @OA\Parameter(
     *         name="healthEntityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad de salud",
     *         @OA\Schema(type="integer", example=17)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La entidad de salud fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="healthEntity", type="object",
     *                     @OA\Property(property="id", type="integer", example=17),
     *                     @OA\Property(property="item_id", type="integer", example=29),
     *                     @OA\Property(property="name", type="string", example="EPS SURA"),
     *                     @OA\Property(property="description", type="string", example="Entidad promotora de salud del régimen contributivo."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example="2025-03-01 08:15:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La entidad de salud fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La entidad de salud no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="La entidad de salud no existe: No se encontró el recurso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar la entidad de salud.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar la entidad de salud: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $healthEntityId)
    {
        try {
            $healthEntityId = new HealthEntityId($healthEntityId);
            $healthEntityDeleteUseCase = new HealthEntityDeleteUseCase($this->healthEntityRepository);
            $healthEntity = new HealthEntityResource($healthEntityDeleteUseCase->__invoke($healthEntityId));
            return $this->successResponse(
                $healthEntity,
                'La entidad de salud fue inactivada exitosamente.',
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
