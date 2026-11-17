<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\BankAccountType;

use App\Application\BankAccountTypeUseCase\BankAccountTypeFindUseCase;
use App\Domain\BankAccountType\Values\BankAccountTypeId;
use App\Http\Resources\BankAccountType\BankAccountTypeResource;
use App\Infrastructure\Repositories\BankAccountTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BankAccountTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $bankAccountTypeRepository;

    public function __construct(BankAccountTypeRepository $bankAccountTypeRepository)
    {
        $this->bankAccountTypeRepository = $bankAccountTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/BankAccountTypes/Find/{bankAccountTypeId}",
     *     summary="Encontrar tipo de cuenta bancaria por ID",
     *     description="Retorna el detalle de un tipo de cuenta bancaria específico (incluye eliminados lógicamente).",
     *     tags={"BankAccountTypes"},
     *     @OA\Parameter(
     *         name="bankAccountTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de cuenta bancaria a consultar",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo de cuenta bancaria fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="bankAccountType", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="item_id", type="integer", example=15),
     *                     @OA\Property(property="name", type="string", example="Cuenta Corriente"),
     *                     @OA\Property(property="description", type="string", example="Cuenta que permite emisión de cheques."),
     *                     @OA\Property(property="settings", type="object", example={"currency":"COP","allow_checks":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de cuenta bancaria fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de cuenta bancaria no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El tipo de cuenta bancaria no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el tipo de cuenta bancaria.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el tipo de cuenta bancaria: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $bankAccountTypeId)
    {
        try {
            $bankAccountTypeId = new BankAccountTypeId($bankAccountTypeId ?? null);
            $bankAccountTypeFindUseCase = new BankAccountTypeFindUseCase($this->bankAccountTypeRepository);
            $bankAccountType = new BankAccountTypeResource($bankAccountTypeFindUseCase->__invoke($bankAccountTypeId));
            return $this->successResponse(
                $bankAccountType,
                'El tipo de cuenta bancaria fue encontrada exitosamente.',
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
