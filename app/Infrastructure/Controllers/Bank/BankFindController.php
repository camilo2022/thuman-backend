<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Bank;

use App\Application\BankUseCase\BankFindUseCase;
use App\Domain\Bank\Values\BankId;
use App\Http\Resources\Bank\BankResource;
use App\Infrastructure\Repositories\BankRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BankFindController
{
    use ApiResponser;
    use ApiMessage;
    private $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    /**
     * @OA\Get(
     *     path="/Banks/Find/{bankId}",
     *     summary="Encontrar banco por ID",
     *     description="Retorna el detalle de un banco específico (incluye eliminados lógicamente).",
     *     tags={"Banks"},
     *     @OA\Parameter(
     *         name="bankId",
     *         in="path",
     *         required=true,
     *         description="ID del banco a consultar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El banco fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="bank", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="item_id", type="integer", example=14),
     *                     @OA\Property(property="name", type="string", example="Banco Nacional"),
     *                     @OA\Property(property="description", type="string", example="Entidad financiera de cobertura nacional."),
     *                     @OA\Property(property="settings", type="object", example={"swift":"BNACCOBB","country":"CO"}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El banco fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Banco no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El banco no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el banco.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el banco: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $bankId)
    {
        try {
            $bankId = new BankId($bankId ?? null);
            $bankFindUseCase = new BankFindUseCase($this->bankRepository);
            $bank = new BankResource($bankFindUseCase->__invoke($bankId));
            return $this->successResponse(
                $bank,
                'El banco fue encontrada exitosamente.',
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
