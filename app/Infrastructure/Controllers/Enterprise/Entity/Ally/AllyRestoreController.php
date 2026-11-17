<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Ally;

use App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\AllyRestoreUseCase;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Http\Resources\Enterprise\Entity\Ally\AllyResource;
use App\Infrastructure\Repositories\AllyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class AllyRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $allieRepository;

    public function __construct(AllyRepository $allieRepository)
    {
        $this->allieRepository = $allieRepository;
    }

    /**
     * @OA\Patch(
     *     path="/Enterprises/Entities/Allys/Restore/{AllyId}",
     *     summary="Restaurar sede de una entidad",
     *     description="Restaura una sede de la entidad previamente eliminada (soft delete).",
     *     tags={"Enterprises - Entities - Allys"},
     *     @OA\Parameter(
     *         name="AllyId",
     *         in="path",
     *         required=true,
     *         description="ID de la sede a restaurar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La sede fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="Ally", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="entity_id", type="integer", example=7),
     *                     @OA\Property(property="name", type="string", example="Sede Norte"),
     *                     @OA\Property(property="city_id", type="integer", example=11001),
     *                     @OA\Property(property="address", type="string", example="Calle 50 #30-20"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-01 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-01 15:20:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La sede fue restaurada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La sede de la entidad no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="La sede de la entidad no existe."),
     *                 @OA\Property(property="error", type="string", example="No se encontró el ID especificado.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al restaurar la sede de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al restaurar la sede de la entidad: Error en la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(int $allieId)
    {
        // Gate::authorize('restoreAlly');
        
        try {
            $allieId = new AllyId($allieId);
            $allieRestoreUseCase = new AllyRestoreUseCase($this->allieRepository);
            $allie = new AllyResource($allieRestoreUseCase->__invoke($allieId));
            return $this->successResponse(
                $allie,
                'El aliado fue activado exitosamente.',
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
