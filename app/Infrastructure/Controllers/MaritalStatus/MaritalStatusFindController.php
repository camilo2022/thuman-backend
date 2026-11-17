<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\MaritalStatus;

use App\Application\MaritalStatusUseCase\MaritalStatusFindUseCase;
use App\Domain\MaritalStatus\Values\MaritalStatusId;
use App\Http\Resources\MaritalStatus\MaritalStatusResource;
use App\Infrastructure\Repositories\MaritalStatusRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class MaritalStatusFindController
{
    use ApiResponser;
    use ApiMessage;
    private $maritalStatusRepository;

    public function __construct(MaritalStatusRepository $maritalStatusRepository)
    {
        $this->maritalStatusRepository = $maritalStatusRepository;
    }

    /**
     * @OA\Get(
     *     path="/MaritalStatus/Find/{maritalStatusId}",
     *     summary="Obtener un estado civil por ID",
     *     description="Retorna un estado civil (incluye eliminados lógicamente) si pertenece al ítem 12.",
     *     tags={"MaritalStatus"},
     *     @OA\Parameter(
     *         name="maritalStatusId",
     *         in="path",
     *         required=true,
     *         description="ID del estado civil a consultar",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El estado civil fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="maritalStatus", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="item_id", type="integer", example=12),
     *                     @OA\Property(property="name", type="string", example="Soltero"),
     *                     @OA\Property(property="description", type="string", example="Persona sin vínculo matrimonial."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El estado civil fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estado civil no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error."),
     *             @OA\Property(property="error", type="string", example="El estado civil no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el estado civil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el estado civil: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $maritalStatusId)
    {
        try {
            $maritalStatusId = new MaritalStatusId($maritalStatusId ?? null);
            $maritalStatusFindUseCase = new MaritalStatusFindUseCase($this->maritalStatusRepository);
            $maritalStatus = new MaritalStatusResource($maritalStatusFindUseCase->__invoke($maritalStatusId));
            return $this->successResponse(
                $maritalStatus,
                'El estado civil fue encontrada exitosamente.',
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
