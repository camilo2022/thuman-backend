<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Sex;

use App\Application\SexUseCase\SexFindUseCase;
use App\Domain\Sex\Values\SexId;
use App\Http\Resources\Sex\SexResource;
use App\Infrastructure\Repositories\SexRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SexFindController
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
     *     path="/Sexes/Find/{sexId}",
     *     summary="Encontrar sexo por ID",
     *     description="Retorna el detalle de un sexo específico (incluye eliminados lógicamente).",
     *     tags={"Sexes"},
     *     @OA\Parameter(
     *         name="sexId",
     *         in="path",
     *         required=true,
     *         description="ID del sexo a consultar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El sexo fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="sex", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="item_id", type="integer", example=9),
     *                     @OA\Property(property="name", type="string", example="Masculino"),
     *                     @OA\Property(property="description", type="string", example="Sexo masculino."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El sexo fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sexo no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El sexo no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el sexo.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el sexo: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $sexId)
    {
        try {
            $sexId = new SexId($sexId ?? null);
            $sexFindUseCase = new SexFindUseCase($this->sexRepository);
            $sex = new SexResource($sexFindUseCase->__invoke($sexId));
            return $this->successResponse(
                $sex,
                'El sexo fue encontrada exitosamente.',
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
