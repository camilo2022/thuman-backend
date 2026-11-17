<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\RiskManager;

use App\Application\RiskManagerUseCase\RiskManagerAllUseCase;
use App\Http\Requests\RiskManager\RiskManagerAllRequest;
use App\Http\Resources\RiskManager\RiskManagerCollection;
use App\Infrastructure\Repositories\RiskManagerRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RiskManagerAllController
{
    use ApiResponser;
    use ApiMessage;
    private $riskManagerRepository;

    public function __construct(RiskManagerRepository $riskManagerRepository)
    {
        $this->riskManagerRepository = $riskManagerRepository;
    }

    /**
     * @OA\Get(
     *     path="/RiskManagers/All",
     *     summary="Listar administradoras de riesgos laborales",
     *     description="Obtiene un listado paginado de administradoras de riesgos laborales (incluye eliminados lógicamente).",
     *     tags={"RiskManagers"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="ARL Sura")
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
     *         description="Los niveles de idiomas consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="riskManagers", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=4),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=27),
     *                             @OA\Property(property="name", type="string", example="Administradoras de Riesgos Laborales")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="ARL Sura"),
     *                         @OA\Property(property="description", type="string", example="Administradora de Riesgos Laborales Sura."),
     *                         @OA\Property(property="settings", type="object", example={"nit":"890.123.456-7"}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=8),
     *                         @OA\Property(property="count", type="integer", example=8),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=1)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los niveles de idiomas consultados exitosamente."),
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
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las administradoras de riesgos laborales.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener las administradoras de riegos laborales: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(RiskManagerAllRequest $request)
    {
        try {
            $riskManagerAllUseCase = new RiskManagerAllUseCase($this->riskManagerRepository);
            $riskManagers = new RiskManagerCollection($riskManagerAllUseCase->__invoke($request));
            return $this->successResponse(
                $riskManagers,
                'Las administradoras de riesgos laborales consultados exitosamente.',
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
