<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Ally;

use App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\AllyDeleteUseCase;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Http\Resources\Enterprise\Entity\Ally\AllyResource;
use App\Infrastructure\Repositories\AllyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class AllyDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $allieRepository;

    public function __construct(AllyRepository $allieRepository)
    {
        $this->allieRepository = $allieRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Enterprises/Entities/Allys/Delete/{AllyId}",
     *     summary="Eliminar sede de una entidad",
     *     description="Elimina una sede de la entidad según su ID.",
     *     tags={"Enterprises - Entities - Allys"},
     *     @OA\Parameter(
     *         name="AllyId",
     *         in="path",
     *         required=true,
     *         description="ID de la sede a eliminar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La sede fue eliminada exitosamente.",
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
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-08-18 12:45:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La sede fue eliminada exitosamente."),
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
     *         description="Error inesperado al eliminar la sede de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al eliminar la sede de la entidad: Error en la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(int $allieId)
    {
        // Gate::authorize('deleteAlly');
        
        try {
            $allieId = new AllyId($allieId);
            $allieDeleteUseCase = new AllyDeleteUseCase($this->allieRepository);
            $allie = new AllyResource($allieDeleteUseCase->__invoke($allieId));
            return $this->successResponse(
                $allie,
                'El aliado fue inactivado exitosamente.',
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
