<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\MaritalStatus;

use App\Application\MaritalStatusUseCase\MaritalStatusUpdateUseCase;
use App\Domain\MaritalStatus\Values\MaritalStatusId;
use App\Http\Requests\MaritalStatus\MaritalStatusUpdateRequest;
use App\Http\Resources\MaritalStatus\MaritalStatusResource;
use App\Infrastructure\Repositories\MaritalStatusRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class MaritalStatusUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $maritalStatusRepository;

    public function __construct(MaritalStatusRepository $maritalStatusRepository)
    {
        $this->maritalStatusRepository = $maritalStatusRepository;
    }

    /**
     * @OA\Put(
     *     path="/MaritalStatus/Update/{maritalStatusId}",
     *     summary="Actualizar estado civil",
     *     description="Actualiza un estado civil existente (subitem del ítem 12).",
     *     tags={"MaritalStatus"},
     *     @OA\Parameter(
     *         name="maritalStatusId",
     *         in="path",
     *         required=true,
     *         description="ID del estado civil a actualizar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=12, description="Debe existir en items y ser 12"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Unión libre", description="Único dentro del item 12"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Pareja que convive sin vínculo matrimonial.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El estado civil fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="maritalStatus", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=12),
     *                     @OA\Property(property="name", type="string", example="Unión libre"),
     *                     @OA\Property(property="description", type="string", example="Pareja que convive sin vínculo matrimonial."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El estado civil fue actualizado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object", example={"item_id":"Categorización","name":"Nombre","description":"Descripción"}),
     *             @OA\Property(property="errors", type="object",
     *                 example={
     *                     "item_id":{"No existe en la base de datos.","El valor seleccionado es inválido."},
     *                     "name":{"Ya ha sido tomado.","No debe exceder los 80 caracteres."},
     *                     "description":{"No debe exceder los 255 caracteres."}
     *                 }
     *             )
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
     *         description="Error inesperado al actualizar el estado civil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar el estado civil: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(MaritalStatusUpdateRequest $request, int $maritalStatusId)
    {
        try {
            $maritalStatusId = new MaritalStatusId($maritalStatusId ?? null);
            $maritalStatusUpdateUseCase = new MaritalStatusUpdateUseCase($this->maritalStatusRepository);
            $maritalStatus = new MaritalStatusResource($maritalStatusUpdateUseCase->__invoke($request, $maritalStatusId));
            return $this->successResponse(
                $maritalStatus,
                'El estado civil fue actualizado exitosamente.',
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
