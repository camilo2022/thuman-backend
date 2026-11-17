<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Ally\Campaign;

use App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\CampaignUseCase\CampaignDeleteUseCase;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignId;
use App\Http\Resources\Enterprise\Entity\Ally\Campaign\CampaignResource;
use App\Infrastructure\Repositories\CampaignRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class CampaignDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Enterprises/Entities/Allies/Delete/{AllieId}",
     *     summary="Eliminar sede de una entidad",
     *     description="Elimina una sede de la entidad según su ID.",
     *     tags={"Enterprises - Entities - Allies"},
     *     @OA\Parameter(
     *         name="AllieId",
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
     *                 @OA\Property(property="Allie", type="object",
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
    public function __invoke(int $campaignId)
    {
        // Gate::authorize('deleteAllie');
        
        try {
            $campaignId = new CampaignId($campaignId);
            $allieDeleteUseCase = new CampaignDeleteUseCase($this->campaignRepository);
            $allie = new CampaignResource($allieDeleteUseCase->__invoke($campaignId));
            return $this->successResponse(
                $allie,
                'La campaña fue inactivada exitosamente.',
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
