<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ContractType;

use App\Application\ContractTypeUseCase\ContractTypeUpdateUseCase;
use App\Domain\ContractType\Values\ContractTypeId;
use App\Http\Requests\ContractType\ContractTypeUpdateRequest;
use App\Http\Resources\ContractType\ContractTypeResource;
use App\Infrastructure\Repositories\ContractTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractTypeUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $contractTypeRepository;

    public function __construct(ContractTypeRepository $contractTypeRepository)
    {
        $this->contractTypeRepository = $contractTypeRepository;
    }

    /**
     * @OA\Put(
     *     path="/ContractTypes/Update/{contractTypeId}",
     *     summary="Actualizar un tipo de contrato",
     *     description="Actualiza un tipo de contrato existente por su ID.",
     *     tags={"ContractTypes"},
     *     @OA\Parameter(
     *         name="contractTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de contrato a actualizar",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=1, description="Debe ser 1 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Indefinido"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Contrato de trabajo a término indefinido.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo de contrato fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="contractType", type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="item_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Indefinido"),
     *                     @OA\Property(property="description", type="string", example="Contrato de trabajo a término indefinido."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-02 14:10:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de contrato fue actualizado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
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
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el tipo de contrato.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar el tipo de contrato: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ContractTypeUpdateRequest $request, int $contractTypeId)
    {
        try {
            $contractTypeId = new ContractTypeId($contractTypeId ?? null);
            $contractTypeUpdateUseCase = new ContractTypeUpdateUseCase($this->contractTypeRepository);
            $contractType = new ContractTypeResource($contractTypeUpdateUseCase->__invoke($request, $contractTypeId));
            return $this->successResponse(
                $contractType,
                'El tipo de contrato fue actualizado exitosamente.',
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
