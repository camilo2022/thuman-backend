<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Area;

use App\Application\AreaUseCase\AreaStoreUseCase;
use App\Http\Requests\Area\AreaStoreRequest;
use App\Http\Resources\Area\AreaResource;
use App\Infrastructure\Repositories\AreaRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AreaStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * @OA\Post(
     *     path="/Areas/Store",
     *     summary="Crear un área",
     *     description="Registra un área nueva.",
     *     tags={"Areas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description"},
     *             @OA\Property(property="name", type="string", maxLength=80, example="Tecnología"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Área encargada de TI y soporte.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La habilidad registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="area", type="object",
     *                     @OA\Property(property="id", type="integer", example=21),
     *                     @OA\Property(property="name", type="string", example="Tecnología"),
     *                     @OA\Property(property="description", type="string", example="Área encargada de TI y soporte."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La habilidad registrado exitosamente."),
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
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="Ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(property="description", type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 255 caracteres.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar el área.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al guardar el area: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(AreaStoreRequest $request)
    {
        try {
            $areaStoreUseCase = new AreaStoreUseCase($this->areaRepository);
            $area = new AreaResource($areaStoreUseCase->__invoke($request));
            return $this->successResponse(
                $area,
                'El área fue registrada exitosamente.',
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
