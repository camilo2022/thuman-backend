<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Ally;

use App\Application\EnterpriseUseCase\EntityUseCase\AllyUseCase\AllyFindUseCase;
use App\Domain\Enterprise\Entity\Ally\Values\AllyId;
use App\Http\Resources\Enterprise\Entity\Ally\AllyResource;
use App\Infrastructure\Repositories\AllyRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class AllyFindController
{
    use ApiResponser;
    use ApiMessage;
    private $allieRepository;

    public function __construct(AllyRepository $allieRepository)
    {
        $this->allieRepository = $allieRepository;
    }

    /**
     * @OA\Get(
     *     path="/Enterprises/Entities/Allys/Find/{AllyId}",
     *     summary="Consultar sede de una entidad",
     *     description="Obtiene la información detallada de una sede específica por su ID.",
     *     tags={"Enterprises - Entities - Allys"},
     *     @OA\Parameter(
     *         name="AllyId",
     *         in="path",
     *         required=true,
     *         description="ID de la sede a consultar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El aliado fue encontrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="Ally", type="object",
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
     *             @OA\Property(property="message", type="string", example="El aliado fue encontrado exitosamente."),
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
    public function __invoke(int $allieId)
    {
        Gate::authorize('findAlly');
        
        try {
            $allieId = new AllyId($allieId ?? null);
            $allieFindUseCase = new AllyFindUseCase($this->allieRepository);
            $allie = new AllyResource($allieFindUseCase->__invoke($allieId));
            return $this->successResponse(
                $allie,
                'El aliado fue encontrado exitosamente.',
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
