<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Benefit;

use App\Application\BenefitUseCase\BenefitFindUseCase;
use App\Domain\Benefit\Values\BenefitId;
use App\Http\Resources\Benefit\BenefitResource;
use App\Infrastructure\Repositories\BenefitRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BenefitFindController
{
    use ApiResponser;
    use ApiMessage;
    private $benefitRepository;

    public function __construct(BenefitRepository $benefitRepository)
    {
        $this->benefitRepository = $benefitRepository;
    }

    /**
     * @OA\Get(
     *     path="/Benefits/Find/{benefitId}",
     *     summary="Encontrar beneficio por ID",
     *     description="Retorna el detalle de un beneficio específico (incluye eliminados lógicamente).",
     *     tags={"Benefits"},
     *     @OA\Parameter(
     *         name="benefitId",
     *         in="path",
     *         required=true,
     *         description="ID del beneficio a consultar",
     *         @OA\Schema(type="integer", example=9)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El beneficio fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="benefit", type="object",
     *                     @OA\Property(property="id", type="integer", example=9),
     *                     @OA\Property(property="item_id", type="integer", example=21),
     *                     @OA\Property(property="name", type="string", example="Auxilio de transporte"),
     *                     @OA\Property(property="description", type="string", example="Subsidio mensual de transporte."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El beneficio fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Beneficio no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El beneficio no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el beneficio.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el beneficio: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $benefitId)
    {
        try {
            $benefitId = new BenefitId($benefitId ?? null);
            $benefitFindUseCase = new BenefitFindUseCase($this->benefitRepository);
            $benefit = new BenefitResource($benefitFindUseCase->__invoke($benefitId));
            return $this->successResponse(
                $benefit,
                'El beneficio fue encontrada exitosamente.',
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
