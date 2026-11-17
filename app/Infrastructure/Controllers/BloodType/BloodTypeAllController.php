<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\BloodType;

use App\Application\BloodTypeUseCase\BloodTypeAllUseCase;
use App\Http\Requests\BloodType\BloodTypeAllRequest;
use App\Http\Resources\BloodType\BloodTypeCollection;
use App\Infrastructure\Repositories\BloodTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BloodTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $bloodTypeRepository;

    public function __construct(BloodTypeRepository $bloodTypeRepository)
    {
        $this->bloodTypeRepository = $bloodTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/BloodTypes/All",
     *     summary="Listar grupos sanguíneos",
     *     description="Obtiene un listado paginado de grupos sanguíneos (incluye eliminados lógicamente).",
     *     tags={"BloodTypes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="O+")
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
     *         description="Los grupos sanguineos consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="bloodTypes", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=4),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=11),
     *                             @OA\Property(property="name", type="string", example="Grupos Sanguíneos")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="O+"),
     *                         @OA\Property(property="description", type="string", example="Grupo sanguíneo O positivo."),
     *                         @OA\Property(property="settings", type="object", example={"rh":"positivo"}),
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
     *             @OA\Property(property="message", type="string", example="Los grupos sanguineos consultados exitosamente."),
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
     *         description="Error inesperado al obtener los grupos sanguíneos.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los grupos sanguíneos: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(BloodTypeAllRequest $request)
    {
        try {
            $bloodTypeAllUseCase = new BloodTypeAllUseCase($this->bloodTypeRepository);
            $bloodTypes = new BloodTypeCollection($bloodTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $bloodTypes,
                'Los grupos sanguineos consultados exitosamente.',
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
