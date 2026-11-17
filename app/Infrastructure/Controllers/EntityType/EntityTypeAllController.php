<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\EntityType;

use App\Application\EntityTypeUseCase\EntityTypeAllUseCase;
use App\Http\Requests\EntityType\EntityTypeAllRequest;
use App\Http\Resources\EntityType\EntityTypeCollection;
use App\Infrastructure\Repositories\EntityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class EntityTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $entityTypeRepository;

    public function __construct(EntityTypeRepository $entityTypeRepository)
    {
        $this->entityTypeRepository = $entityTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/EntityTypes/All",
     *     summary="Listar tipos de entidades",
     *     description="Obtiene un listado paginado de tipos de entidades (item_id = 34), con búsqueda opcional.",
     *     tags={"EntityTypes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda (máx. 100 caracteres)",
     *         @OA\Schema(type="string", example="Cooperativa")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Cantidad de registros por página",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Los tipos de entidades consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="entityTypes", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="item", type="object", example={"id":34,"name":"Tipos de Entidad"}),
     *                         @OA\Property(property="name", type="string", example="Cooperativa"),
     *                         @OA\Property(property="description", type="string", example="Entidad de tipo cooperativa"),
     *                         @OA\Property(property="settings", type="object", example={"visible":true}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=42),
     *                         @OA\Property(property="count", type="integer", example=15),
     *                         @OA\Property(property="per_page", type="integer", example=15),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=3)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los tipos de entidades consultados exitosamente."),
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
     *                 @OA\Property(property="search", type="string", example="Cooperativa"),
     *                 @OA\Property(property="perPage", type="integer", example=15)
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
     *         description="Error inesperado al obtener los tipos de entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los tipos de entidad: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(EntityTypeAllRequest $request)
    {
        try {
            $entityTypeAllUseCase = new EntityTypeAllUseCase($this->entityTypeRepository);
            $entityTypes = new EntityTypeCollection($entityTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $entityTypes,
                'Los tipos de entidades consultados exitosamente.',
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
