<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\MaritalStatus;

use App\Application\MaritalStatusUseCase\MaritalStatusStoreUseCase;
use App\Http\Requests\MaritalStatus\MaritalStatusStoreRequest;
use App\Http\Resources\MaritalStatus\MaritalStatusResource;
use App\Infrastructure\Repositories\MaritalStatusRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class MaritalStatusStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $maritalStatusRepository;

    public function __construct(MaritalStatusRepository $maritalStatusRepository)
    {
        $this->maritalStatusRepository = $maritalStatusRepository;
    }

    /**
     * @OA\Post(
     *     path="/MaritalStatus/Store",
     *     summary="Registrar estado civil",
     *     description="Crea un nuevo estado civil (subitem del ítem 12).",
     *     tags={"MaritalStatus"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=12, description="Debe existir en items y ser 12"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Casado", description="Único dentro del item 12"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Persona con vínculo matrimonial.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El estado civil registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="maritalStatus", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=12),
     *                     @OA\Property(property="name", type="string", example="Casado"),
     *                     @OA\Property(property="description", type="string", example="Persona con vínculo matrimonial."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", nullable=true, format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El estado civil registrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
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
     *         response=500,
     *         description="Error inesperado al guardar el estado civil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar el estado civil: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(MaritalStatusStoreRequest $request)
    {
        try {
            $maritalStatusStoreUseCase = new MaritalStatusStoreUseCase($this->maritalStatusRepository);
            $maritalStatus = new MaritalStatusResource($maritalStatusStoreUseCase->__invoke($request));
            return $this->successResponse(
                $maritalStatus,
                'El estado civil registrado exitosamente.',
                201
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
