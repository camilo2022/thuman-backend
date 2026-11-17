<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Headquarter;

use App\Application\EnterpriseUseCase\EntityUseCase\HeadquarterUseCase\HeadquarterFindUseCase;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterId;
use App\Http\Resources\Enterprise\Entity\Headquarter\HeadquarterResource;
use App\Infrastructure\Repositories\HeadquarterRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class HeadquarterFindController
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
     *     path="/Enterprises/Entities/Headquarters/Find/{headquarterId}",
     *     summary="Consultar sede de una entidad",
     *     description="Obtiene la información detallada de una sede específica por su ID.",
     *     tags={"Enterprises - Entities - Headquarters"},
     *     @OA\Parameter(
     *         name="headquarterId",
     *         in="path",
     *         required=true,
     *         description="ID de la sede a consultar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La sede fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="headquarter", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="entity_id", type="integer", example=15),
     *                     @OA\Property(property="name", type="string", example="Sede Norte"),
     *                     @OA\Property(property="city_id", type="integer", example=11001),
     *                     @OA\Property(property="address", type="string", example="Carrera 50 #123-45"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La sede fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sede de la entidad no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La sede de la entidad no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la sede de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar la sede de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $headquarterId)
    {
        Gate::authorize('findHeadquarter');
        
        try {
            $headquarterId = new HeadquarterId($headquarterId ?? null);
            $headquarterFindUseCase = new HeadquarterFindUseCase($this->headquarterRepository);
            $headquarter = new HeadquarterResource($headquarterFindUseCase->__invoke($headquarterId));
            return $this->successResponse(
                $headquarter,
                'La sede fue encontrada exitosamente.',
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
