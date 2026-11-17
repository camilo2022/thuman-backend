<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ServiceProvider;

use App\Application\ServiceProviderUseCase\ServiceProviderDeleteUseCase;
use App\Domain\ServiceProvider\Values\ServiceProviderId;
use App\Http\Resources\ServiceProvider\ServiceProviderResource;
use App\Infrastructure\Repositories\ServiceProviderRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ServiceProviderDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $serviceProviderRepository;

    public function __construct(ServiceProviderRepository $serviceProviderRepository)
    {
        $this->serviceProviderRepository = $serviceProviderRepository;
    }

    /**
     * @OA\Delete(
     *     path="/ServiceProviders/Delete/{serviceProviderId}",
     *     summary="Eliminar proveedor de servicio",
     *     description="Elimina lógicamente un proveedor de servicio por su ID.",
     *     tags={"ServiceProviders"},
     *     @OA\Parameter(
     *         name="serviceProviderId",
     *         in="path",
     *         required=true,
     *         description="ID del proveedor de servicio a eliminar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El proveedor de servicio fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="serviceProvider", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="item_id", type="integer", example=31),
     *                     @OA\Property(property="name", type="string", example="Protección Pensiones"),
     *                     @OA\Property(property="description", type="string", example="Fondo de pensión — régimen obligatorio"),
     *                     @OA\Property(property="settings", type="object", example={"visible":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-08-18 10:20:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El proveedor de servicio fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fondo de pensión no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="El proveedor de servicio no existe: No se encontró el ID especificado."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar el proveedor de servicio.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar el proveedor de servicio: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $serviceProviderId)
    {
        try {
            $serviceProviderId = new ServiceProviderId($serviceProviderId);
            $serviceProviderDeleteUseCase = new ServiceProviderDeleteUseCase($this->serviceProviderRepository);
            $serviceProvider = new ServiceProviderResource($serviceProviderDeleteUseCase->__invoke($serviceProviderId));
            return $this->successResponse(
                $serviceProvider,
                'El proveedor de servicio fue inactivado exitosamente.',
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
