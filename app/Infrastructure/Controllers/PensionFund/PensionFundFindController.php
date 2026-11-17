<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFund;

use App\Application\PensionFundUseCase\PensionFundFindUseCase;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Http\Resources\PensionFund\PensionFundResource;
use App\Infrastructure\Repositories\PensionFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundFindController
{
    use ApiResponser;
    use ApiMessage;
    private $pensionFundRepository;

    public function __construct(PensionFundRepository $pensionFundRepository)
    {
        $this->pensionFundRepository = $pensionFundRepository;
    }

    /**
     * @OA\Get(
     *     path="/PensionFunds/Find/{pensionFundId}",
     *     summary="Obtener fondo de pensión por ID",
     *     description="Retorna la información detallada de un fondo de pensión específico.",
     *     tags={"PensionFunds"},
     *     @OA\Parameter(
     *         name="pensionFundId",
     *         in="path",
     *         required=true,
     *         description="ID del fondo de pensión",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El fondo de pensión fue encontrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="pensionFund", type="object",
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
     *             @OA\Property(property="message", type="string", example="El fondo de pensión fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El fondo de pensión no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El fondo de pensión no existe."),
     *             @OA\Property(property="error", type="string", example="El fondo de pensión no existe: No se encontró el ID."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el fondo de pensión.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el fondo de pensión: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $pensionFundId)
    {
        try {
            $pensionFundId = new PensionFundId($pensionFundId ?? null);
            $pensionFundFindUseCase = new PensionFundFindUseCase($this->pensionFundRepository);
            $pensionFund = new PensionFundResource($pensionFundFindUseCase->__invoke($pensionFundId));
            return $this->successResponse(
                $pensionFund,
                'El fondo de pensión fue encontrada exitosamente.',
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
