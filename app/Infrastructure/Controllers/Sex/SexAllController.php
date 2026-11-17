<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Sex;

use App\Application\SexUseCase\SexAllUseCase;
use App\Http\Requests\Sex\SexAllRequest;
use App\Http\Resources\Sex\SexCollection;
use App\Infrastructure\Repositories\SexRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SexAllController
{
    use ApiResponser;
    use ApiMessage;
    private $sexRepository;

    public function __construct(SexRepository $sexRepository)
    {
        $this->sexRepository = $sexRepository;
    }

    /**
     * @OA\Get(
     *     path="/Sexes/All",
     *     summary="Listar sexos",
     *     description="Obtiene un listado paginado de sexos (incluye eliminados lógicamente).",
     *     tags={"Sexes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="Femenino")
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
     *         description="Los sexos consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="sexes", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=9),
     *                             @OA\Property(property="name", type="string", example="Sexos")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Femenino"),
     *                         @OA\Property(property="description", type="string", example="Sexo femenino."),
     *                         @OA\Property(property="settings", type="object", example={"order":2}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=3),
     *                         @OA\Property(property="count", type="integer", example=3),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=1)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los sexos consultados exitosamente."),
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
     *         description="Error inesperado al obtener los sexos.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los sexos: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(SexAllRequest $request)
    {
        try {
            $sexAllUseCase = new SexAllUseCase($this->sexRepository);
            $sexes = new SexCollection($sexAllUseCase->__invoke($request));
            return $this->successResponse(
                $sexes,
                'Los sexos consultados exitosamente.',
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
