<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Ally\Campaign;

use App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\CampaignUseCase\CampaignStoreUseCase;
use App\Http\Requests\Enterprise\Entity\Ally\Campaign\CampaignStoreRequest;
use App\Http\Resources\Enterprise\Entity\Ally\Campaign\CampaignResource;
use App\Infrastructure\Repositories\CampaignRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class CampaignStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @OA\Post(
     *     path="/Enterprises/Entities/Campaigns/Store",
     *     summary="Registrar sede de una entidad",
     *     description="Crea una nueva sede asociada a una entidad.",
     *     tags={"Enterprises - Entities - Campaigns"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"enterprise_id","name","city_id","address"},
     *             @OA\Property(property="enterprise_id", type="integer", description="ID de la empresa", example=3),
     *             @OA\Property(property="name", type="string", description="Nombre de la sede", example="Sede Principal"),
     *             @OA\Property(property="city_id", type="integer", description="ID de la ciudad", example=11001),
     *             @OA\Property(property="address", type="string", description="Dirección física de la sede", example="Calle 123 #45-67")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La sede fue registrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="Campaign", type="object",
     *                     @OA\Property(property="id", type="integer", example=9),
     *                     @OA\Property(property="entity_id", type="integer", example=15),
     *                     @OA\Property(property="name", type="string", example="Sede Principal"),
     *                     @OA\Property(property="city_id", type="integer", example=11001),
     *                     @OA\Property(property="address", type="string", example="Calle 123 #45-67"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La sede fue registrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
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
     *                 @OA\Property(property="enterprise_id", type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 ),
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="Ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(property="city_id", type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 ),
     *                 @OA\Property(property="address", type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 110 caracteres.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar la sede de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al guardar la sede de la entidad: Error en la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(CampaignStoreRequest $request)
    {
        Gate::authorize('storeCampaign');
        
        try {
            $allieStoreUseCase = new CampaignStoreUseCase($this->campaignRepository);
            $allie = new CampaignResource($allieStoreUseCase->__invoke($request));
            return $this->successResponse(
                $allie,
                'La campaña fue registrada exitosamente.',
                201
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
