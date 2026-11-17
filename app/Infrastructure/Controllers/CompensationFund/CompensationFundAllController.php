<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CompensationFund;

use App\Application\CompensationFundUseCase\CompensationFundAllUseCase;
use App\Http\Requests\CompensationFund\CompensationFundAllRequest;
use App\Http\Resources\CompensationFund\CompensationFundCollection;
use App\Infrastructure\Repositories\CompensationFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CompensationFundAllController
{
    use ApiResponser;
    use ApiMessage;
    private $compensationFundRepository;

    public function __construct(CompensationFundRepository $compensationFundRepository)
    {
        $this->compensationFundRepository = $compensationFundRepository;
    }

    /**
     * @OA\Get(
     *     path="/CompensationFunds/All",
     *     summary="Listar cajas de compensación",
     *     description="Obtiene un listado paginado de cajas de compensación (incluye eliminadas lógicamente).",
     *     tags={"CompensationFunds"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string", example="Comfama")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Las caja de compensación consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="compensationFunds", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="item", type="object", example={"id":30,"name":"Cajas de compensación"}),
     *                         @OA\Property(property="name", type="string", example="Comfenalco Antioquia"),
     *                         @OA\Property(property="description", type="string", example="Caja de compensación familiar."),
     *                         @OA\Property(property="settings", type="object", example={"code":"CFA","country_id":48}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-01 10:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-10 12:00:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=27),
     *                         @OA\Property(property="count", type="integer", example=15),
     *                         @OA\Property(property="per_page", type="integer", example=15),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=2)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las caja de compensación consultados exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="perPage", type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las cajas de compensación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener las cajas de compensación: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(CompensationFundAllRequest $request)
    {
        try {
            $compensationFundAllUseCase = new CompensationFundAllUseCase($this->compensationFundRepository);
            $compensationFunds = new CompensationFundCollection($compensationFundAllUseCase->__invoke($request));
            return $this->successResponse(
                $compensationFunds,
                'Las caja de compensación consultados exitosamente.',
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
