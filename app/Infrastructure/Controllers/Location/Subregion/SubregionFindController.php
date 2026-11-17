<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Subregion;

use App\Application\LocationUseCase\SubregionUseCase\SubregionFindUseCase;
use App\Domain\Location\Subregion\Values\SubregionId;
use App\Http\Requests\Location\Subregion\SubregionFindRequest;
use App\Http\Resources\Location\Subregion\SubregionResource;
use App\Infrastructure\Repositories\SubregionRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SubregionFindController
{
    use ApiResponser;
    use ApiMessage;
    private $subregionRepository;

    public function __construct(SubregionRepository $subregionRepository)
    {
        $this->subregionRepository = $subregionRepository;
    }

    /**
     * @OA\Get(
     *     path="/Locations/Subregions/Find/{subregionId}",
     *     summary="Encontrar subregión por ID",
     *     description="Retorna una subregión por su identificador.",
     *     tags={"Locations - Subregions"},
     *     @OA\Parameter(
     *         name="subregionId", in="path", required=true,
     *         description="ID de la subregión",
     *         @OA\Schema(type="integer"), example=1
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La subregión fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="subregion", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="region_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Northern Africa"),
     *                     @OA\Property(
     *                         property="translations", type="object",
     *                         example={"es":"Norte de África","en":"Northern Africa","fr":"Afrique du Nord","de":"Nordafrika","zh-CN":"北部非洲"}
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La subregion fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subregión no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La subregion no existe: No se encontró el registro.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la subregión.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar la subregion: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(SubregionFindRequest $request, int|null $subregionId = null)
    {
        try {
            $subregionId = new SubregionId($subregionId);
            $subregionFindUseCase = new SubregionFindUseCase($this->subregionRepository);
            $subregion = new SubregionResource($subregionFindUseCase->__invoke($request, $subregionId));
            return $this->successResponse(
                $subregion,
                'La subregion fue encontrada exitosamente.',
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
