<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFund;

use App\Application\PensionFundUseCase\PensionFundAllUseCase;
use App\Http\Requests\PensionFund\PensionFundAllRequest;
use App\Http\Resources\PensionFund\PensionFundCollection;
use App\Infrastructure\Repositories\PensionFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundAllController
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
     *     path="/PensionFunds/All",
     *     summary="Listar fondos de pensiones",
     *     description="Obtiene un listado paginado de los fondos de pensiones con opción de búsqueda.",
     *     tags={"PensionFunds"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string", maxLength=100, example="Protección")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=25)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Los fondos de pensiones consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="pensionFunds", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=31),
     *                             @OA\Property(property="name", type="string", example="Fondos de pensiones"),
     *                             @OA\Property(property="description", type="string", example="Catálogo para fondos de pensiones")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Protección Pensiones"),
     *                         @OA\Property(property="description", type="string", example="Fondo privado de pensiones."),
     *                         @OA\Property(property="settings", type="object", example={"visible": true}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=120),
     *                         @OA\Property(property="count", type="integer", example=25),
     *                         @OA\Property(property="per_page", type="integer", example=25),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=5)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los fondos de pensiones consultados exitosamente."),
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
     *                 @OA\Property(property="search", type="string", example="Protección"),
     *                 @OA\Property(property="perPage", type="integer", example=25)
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico."))
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener los fondos de pensiones.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los fondos de pensiones: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(PensionFundAllRequest $request)
    {
        try {
            $pensionFundAllUseCase = new PensionFundAllUseCase($this->pensionFundRepository);
            $pensionFunds = new PensionFundCollection($pensionFundAllUseCase->__invoke($request));
            return $this->successResponse(
                $pensionFunds,
                'Los fondos de pensiones consultados exitosamente.',
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
