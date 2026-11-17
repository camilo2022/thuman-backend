<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Ally\Campaign;

use App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\CampaignUseCase\CampaignUpdateUseCase;
use App\Domain\Enterprise\Entity\Ally\Campaign\Values\CampaignId;
use App\Http\Requests\Enterprise\Entity\Ally\Campaign\CampaignUpdateRequest;
use App\Http\Resources\Enterprise\Entity\Ally\Campaign\CampaignResource;
use App\Infrastructure\Repositories\CampaignRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class CampaignUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $allieRepository;

    public function __construct(CampaignRepository $allieRepository)
    {
        $this->allieRepository = $allieRepository;
    }

    /**
     * @OA\Put(
     *     path="/Enterprises/Entities/Allies/Update/{AllieId}",
     *     summary="Actualizar sede de una entidad",
     *     description="Actualiza la información de una sede de la entidad.",
     *     tags={"Enterprises - Entities - Allies"},
     *     @OA\Parameter(
     *         name="AllieId",
     *         in="path",
     *         required=true,
     *         description="ID de la sede a actualizar",
     *         @OA\Schema(type="integer", example=9)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"enterprise_id","name","city_id","address"},
     *             @OA\Property(property="enterprise_id", type="integer", description="ID de la empresa", example=3),
     *             @OA\Property(property="name", type="string", description="Nombre de la sede", example="Sede Actualizada"),
     *             @OA\Property(property="city_id", type="integer", description="ID de la ciudad", example=11001),
     *             @OA\Property(property="address", type="string", description="Dirección física de la sede", example="Carrera 45 #100-20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La sede fue actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="Allie", type="object",
     *                     @OA\Property(property="id", type="integer", example=9),
     *                     @OA\Property(property="entity_id", type="integer", example=15),
     *                     @OA\Property(property="name", type="string", example="Sede Actualizada"),
     *                     @OA\Property(property="city_id", type="integer", example=11001),
     *                     @OA\Property(property="address", type="string", example="Carrera 45 #100-20"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La sede fue actualizada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
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
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="enterprise_id", type="string", example="Empresa"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="city_id", type="string", example="Ciudad"),
     *                 @OA\Property(property="address", type="string", example="Dirección")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="Ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(property="address", type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 110 caracteres.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la sede de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al actualizar la sede de la entidad: Error en la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(CampaignUpdateRequest $request, int $campaignId)
    {
        Gate::authorize('updateCampaign');
        
        try {
            $campaignId = new CampaignId($campaignId ?? null);
            $allieUpdateUseCase = new CampaignUpdateUseCase($this->allieRepository);
            $allie = new CampaignResource($allieUpdateUseCase->__invoke($request, $campaignId));
            return $this->successResponse(
                $allie,
                'La campaña fue actualizada exitosamente.',
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
