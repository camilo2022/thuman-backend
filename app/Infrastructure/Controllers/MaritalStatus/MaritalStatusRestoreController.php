<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\MaritalStatus;

use App\Application\MaritalStatusUseCase\MaritalStatusRestoreUseCase;
use App\Domain\MaritalStatus\Values\MaritalStatusId;
use App\Http\Resources\MaritalStatus\MaritalStatusResource;
use App\Infrastructure\Repositories\MaritalStatusRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class MaritalStatusRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $maritalStatusRepository;

    public function __construct(MaritalStatusRepository $maritalStatusRepository)
    {
        $this->maritalStatusRepository = $maritalStatusRepository;
    }

    /**
     * @OA\Patch(
     *     path="/MaritalStatus/Restore/{maritalStatusId}",
     *     summary="Restaurar estado civil",
     *     description="Restaura lógicamente un estado civil (subitem del ítem 12) por su ID.",
     *     tags={"MaritalStatus"},
     *     @OA\Parameter(
     *         name="maritalStatusId",
     *         in="path",
     *         required=true,
     *         description="ID del estado civil a restaurar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El estado civil fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="maritalStatus", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=12),
     *                     @OA\Property(property="name", type="string", example="Casado(a)"),
     *                     @OA\Property(property="description", type="string", example="Persona con vínculo matrimonial vigente."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El estado civil fue restaurada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
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
     *         description="Error inesperado al restaurar el estado civil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar el estado civil: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $maritalStatusId)
    {
        try {
            $maritalStatusId = new MaritalStatusId($maritalStatusId);
            $maritalStatusRestoreUseCase = new MaritalStatusRestoreUseCase($this->maritalStatusRepository);
            $maritalStatus = new MaritalStatusResource($maritalStatusRestoreUseCase->__invoke($maritalStatusId));
            return $this->successResponse(
                $maritalStatus,
                'El estado civil fue activado exitosamente.',
                204
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
