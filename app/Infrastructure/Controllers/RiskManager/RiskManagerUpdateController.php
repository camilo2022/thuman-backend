<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\RiskManager;

use App\Application\RiskManagerUseCase\RiskManagerUpdateUseCase;
use App\Domain\RiskManager\Values\RiskManagerId;
use App\Http\Requests\RiskManager\RiskManagerUpdateRequest;
use App\Http\Resources\RiskManager\RiskManagerResource;
use App\Infrastructure\Repositories\RiskManagerRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RiskManagerUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $riskManagerRepository;

    public function __construct(RiskManagerRepository $riskManagerRepository)
    {
        $this->riskManagerRepository = $riskManagerRepository;
    }

    /**
     * @OA\Put(
     *     path="/RiskManagers/Update/{riskManagerId}",
     *     summary="Actualizar administradora de riesgo laboral",
     *     description="Actualiza una administradora de riesgo laboral existente.",
     *     tags={"RiskManagers"},
     *     @OA\Parameter(
     *         name="riskManagerId",
     *         in="path",
     *         required=true,
     *         description="ID de la administradora de riesgo laboral a actualizar",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=27, description="Debe existir en items y ser 27"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="ARL Sura"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Administradora de Riesgos Laborales Sura.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La administradora de riesgo laboral fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="riskManager", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="item_id", type="integer", example=27),
     *                     @OA\Property(property="name", type="string", example="ARL Sura"),
     *                     @OA\Property(property="description", type="string", example="Administradora de Riesgos Laborales Sura."),
     *                     @OA\Property(property="settings", type="object", example={"nit":"890.123.456-7"}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La administradora de riesgo laboral fue actualizado exitosamente."),
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
     *                 @OA\Property(property="item_id", type="string", example="Categorización"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Administradora de riesgo laboral no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="La administradora de riesgo laboral no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la administradora de riesgo laboral.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar la administradora de riesgo laboral: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(RiskManagerUpdateRequest $request, int $riskManagerId)
    {
        try {
            $riskManagerId = new RiskManagerId($riskManagerId ?? null);
            $riskManagerUpdateUseCase = new RiskManagerUpdateUseCase($this->riskManagerRepository);
            $riskManager = new RiskManagerResource($riskManagerUpdateUseCase->__invoke($request, $riskManagerId));
            return $this->successResponse(
                $riskManager,
                'La administradora de riesgo laboral fue actualizada exitosamente.',
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
