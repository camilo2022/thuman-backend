<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ServiceProvider;

use App\Application\ServiceProviderUseCase\ServiceProviderStoreUseCase;
use App\Http\Requests\ServiceProvider\ServiceProviderStoreRequest;
use App\Http\Resources\ServiceProvider\ServiceProviderResource;
use App\Infrastructure\Repositories\ServiceProviderRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ServiceProviderStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $serviceProviderRepository;

    public function __construct(ServiceProviderRepository $serviceProviderRepository)
    {
        $this->serviceProviderRepository = $serviceProviderRepository;
    }

    /**
     * @OA\Post(
     *     path="/ServiceProviders/Store",
     *     summary="Registrar proveedor de servicio",
     *     description="Crea un nuevo registro de proveedor de servicio.",
     *     tags={"ServiceProviders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=31, description="Categorización (debe corresponder al ítem de fondos de pensiones)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Colfondos", description="Nombre del proveedor de servicio"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Fondo de pensiones obligatorio y voluntario", description="Descripción del fondo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El proveedor de servicio registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="serviceProvider", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="item_id", type="integer", example=31),
     *                     @OA\Property(property="name", type="string", example="Colfondos"),
     *                     @OA\Property(property="description", type="string", example="Fondo de pensiones obligatorio y voluntario"),
     *                     @OA\Property(property="settings", type="object", example={"visible":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El proveedor de servicio registrado exitosamente."),
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
     *                 @OA\Property(property="item_id", type="string", example="Categorización"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar el proveedor de servicio.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar el proveedor de servicio: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(ServiceProviderStoreRequest $request)
    {
        try {
            $serviceProviderStoreUseCase = new ServiceProviderStoreUseCase($this->serviceProviderRepository);
            $serviceProvider = new ServiceProviderResource($serviceProviderStoreUseCase->__invoke($request));
            return $this->successResponse(
                $serviceProvider,
                'El proveedor de servicio fue registrado exitosamente.',
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
