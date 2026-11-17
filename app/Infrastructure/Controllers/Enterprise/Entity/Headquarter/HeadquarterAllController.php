<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Headquarter;

use App\Application\EnterpriseUseCase\EntityUseCase\HeadquarterUseCase\HeadquarterAllUseCase;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Headquarter\HeadquarterAllRequest;
use App\Http\Resources\Enterprise\Entity\Headquarter\HeadquarterCollection;
use App\Infrastructure\Repositories\HeadquarterRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class HeadquarterAllController
{
    use ApiResponser;
    use ApiMessage;
    private $headquarterRepository;

    public function __construct(HeadquarterRepository $headquarterRepository)
    {
        $this->headquarterRepository = $headquarterRepository;
    }

    /**
     * @OA\Get(
     *     path="/Enterprises/Entities/Headquarters/All/{entityId}",
     *     summary="Listar sedes de una entidad",
     *     description="Obtiene todas las sedes de una entidad específica, con opciones de búsqueda y paginación.",
     *     tags={"Enterprises - Entities - Headquarters"},
     *     @OA\Parameter(
     *         name="entityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Filtro de búsqueda por nombre de la sede u otros atributos",
     *         @OA\Schema(type="string", example="Sede Principal")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de sedes obtenido exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="headquarters", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="entity", type="object", example={"id":15,"name":"Empresa Ejemplo S.A.S."}),
     *                         @OA\Property(property="name", type="string", example="Sede Principal"),
     *                         @OA\Property(property="city", type="object", example={"id":11001,"name":"Bogotá","departament":{"id":11,"name":"Cundinamarca","country":{"id":57,"name":"Colombia"}}}),
     *                         @OA\Property(property="address", type="string", example="Calle 123 #45-67"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=25),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=3)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las sedes de la entidad consultada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación en los parámetros enviados.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las sedes de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener la sede de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(HeadquarterAllRequest $request, int $entityId)
    {
        Gate::authorize('allHeadquarter');
        
        try {
            $entityId = new EntityId($entityId ?? null);
            $headquarterAllUseCase = new HeadquarterAllUseCase($this->headquarterRepository);
            $enterpriseGalleries = new HeadquarterCollection($headquarterAllUseCase->__invoke($request, $entityId));
            return $this->successResponse(
                $enterpriseGalleries,
                'Las sedes de la entidad consultada exitosamente.',
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
