<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ContractType;

use App\Application\ContractTypeUseCase\ContractTypeRestoreUseCase;
use App\Domain\ContractType\Values\ContractTypeId;
use App\Http\Resources\ContractType\ContractTypeResource;
use App\Infrastructure\Repositories\ContractTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractTypeRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $contractTypeRepository;

    public function __construct(ContractTypeRepository $contractTypeRepository)
    {
        $this->contractTypeRepository = $contractTypeRepository;
    }

    /**
     * @OA\Patch(
     *     path="/ContractTypes/Restore/{contractTypeId}",
     *     summary="Restaurar un tipo de contrato",
     *     description="Restaura un tipo de contrato previamente eliminado lógicamente por su ID.",
     *     tags={"ContractTypes"},
     *     @OA\Parameter(
     *         name="contractTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de contrato a restaurar",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El tipo de contrato fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="contractType", type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="item_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Indefinido"),
     *                     @OA\Property(property="description", type="string", example="Contrato de trabajo a término indefinido."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de contrato fue restaurada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de contrato no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El tipo de contrato no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al restaurar el tipo de contrato.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al restaurar el tipo de contrato: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $contractTypeId)
    {
        try {
            $contractTypeId = new ContractTypeId($contractTypeId);
            $contractTypeRestoreUseCase = new ContractTypeRestoreUseCase($this->contractTypeRepository);
            $contractType = new ContractTypeResource($contractTypeRestoreUseCase->__invoke($contractTypeId));
            return $this->successResponse(
                $contractType,
                'El tipo de contrato fue activado exitosamente.',
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
