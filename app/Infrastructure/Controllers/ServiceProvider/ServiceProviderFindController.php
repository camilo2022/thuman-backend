<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ServiceProvider;

use App\Application\ServiceProviderUseCase\ServiceProviderFindUseCase;
use App\Domain\ServiceProvider\Values\ServiceProviderId;
use App\Http\Resources\ServiceProvider\ServiceProviderResource;
use App\Infrastructure\Repositories\ServiceProviderRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ServiceProviderFindController
{
    use ApiResponser;
    use ApiMessage;
    private $serviceProviderRepository;

    public function __construct(ServiceProviderRepository $serviceProviderRepository)
    {
        $this->serviceProviderRepository = $serviceProviderRepository;
    }

    /**
     * @OA\Get(
     *     path="/ServiceProviders/Find/{serviceProviderId}",
     *     summary="Obtener proveedor de servicio por ID",
     *     description="Retorna la información detallada de un proveedor de servicio específico.",
     *     tags={"ServiceProviders"},
     *     @OA\Parameter(
     *         name="serviceProviderId",
     *         in="path",
     *         required=true,
     *         description="ID del proveedor de servicio",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El proveedor de servicio fue encontrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="serviceProvider", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="item_id", type="integer", example=31),
     *                     @OA\Property(property="name", type="string", example="Protección Pensiones"),
     *                     @OA\Property(property="description", type="string", example="Fondo privado de pensiones."),
     *                     @OA\Property(property="settings", type="object", example={"visible": true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El proveedor de servicio fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El proveedor de servicio no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El proveedor de servicio no existe."),
     *             @OA\Property(property="error", type="string", example="El proveedor de servicio no existe: No se encontró el ID."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el proveedor de servicio.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el proveedor de servicio: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $serviceProviderId)
    {
        try {
            $serviceProviderId = new ServiceProviderId($serviceProviderId ?? null);
            $serviceProviderFindUseCase = new ServiceProviderFindUseCase($this->serviceProviderRepository);
            $serviceProvider = new ServiceProviderResource($serviceProviderFindUseCase->__invoke($serviceProviderId));
            return $this->successResponse(
                $serviceProvider,
                'El proveedor de servicio fue encontrada exitosamente.',
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
