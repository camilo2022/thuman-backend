<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Area;

use App\Application\AreaUseCase\AreaAllUseCase;
use App\Http\Requests\Area\AreaAllRequest;
use App\Http\Resources\Area\AreaCollection;
use App\Infrastructure\Repositories\AreaRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AreaAllController
{
    use ApiResponser;
    use ApiMessage;
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * @OA\Get(
     *     path="/Areas/All",
     *     summary="Listar todas las áreas",
     *     tags={"Areas"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda para filtrar áreas",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Número de registros por página",
     *         required=false,
     *         @OA\Schema(type="integer", format="int32", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Las áreas fueron consultadas exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="areas", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Tecnología"),
     *                         @OA\Property(property="description", type="string", example="Área encargada de TI"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02 21:00:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=50),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=5)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las áreas fueron consultadas exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(
     *                 property="attributes",
     *                 type="object",
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="search",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser una cadena de caracteres.")
     *                 ),
     *                 @OA\Property(
     *                     property="perPage",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las áreas.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error inesperado al obtener las áreas: Error en la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(AreaAllRequest $request)
    {
        try {
            $areaAllUseCase = new AreaAllUseCase($this->areaRepository);
            $areas = new AreaCollection($areaAllUseCase->__invoke($request));
            return $this->successResponse(
                $areas,
                'Las areas fueron consultadas exitosamente.',
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
