<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFund;

use App\Application\CompensationFundUseCase\CompensationFundStoreUseCase;
use App\Http\Requests\CompensationFund\CompensationFundStoreRequest;
use App\Http\Resources\CompensationFund\CompensationFundResource;
use App\Infrastructure\Repositories\CompensationFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CompensationFundStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundRepository;

    public function __construct(CompensationFundRepository $compensationFundRepository)
    {
        $this->compensationFundRepository = $compensationFundRepository;
    }

    /**
     * @OA\Post(
     *     path="/CompensationFunds/Store",
     *     summary="Registrar caja de compensación",
     *     tags={"CompensationFunds"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=30),
     *             @OA\Property(property="name", type="string", example="Comfandi"),
     *             @OA\Property(property="description", type="string", example="Caja de compensación del Valle del Cauca")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Caja de compensación registrada exitosamente.",
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
     *                 @OA\Property(property="item_id", type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 ),
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
     *         description="Error inesperado al guardar la caja de compensación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Exception"),
     *             @OA\Property(property="error", type="string", example="Error al guardar la caja de compensación: error en base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(CompensationFundStoreRequest $request)
    {
        try {
            $compensationFundStoreUseCase = new CompensationFundStoreUseCase($this->compensationFundRepository);
            $compensationFund = new CompensationFundResource($compensationFundStoreUseCase->__invoke($request));
            return $this->successResponse(
                $compensationFund,
                'La caja de compensación registrado exitosamente.',
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
