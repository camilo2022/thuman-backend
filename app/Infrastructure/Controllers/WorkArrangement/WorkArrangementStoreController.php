<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\WorkArrangement;

use App\Application\WorkArrangementUseCase\WorkArrangementStoreUseCase;
use App\Http\Requests\WorkArrangement\WorkArrangementStoreRequest;
use App\Http\Resources\WorkArrangement\WorkArrangementResource;
use App\Infrastructure\Repositories\WorkArrangementRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class WorkArrangementStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $workArrangementRepository;

    public function __construct(WorkArrangementRepository $workArrangementRepository)
    {
        $this->workArrangementRepository = $workArrangementRepository;
    }

    /**
     * @OA\Post(
     *     path="/WorkArrangements/Store",
     *     summary="Registrar una nueva modalidad de trabajo",
     *     description="Crea una nueva modalidad de trabajo con los datos proporcionados.",
     *     tags={"WorkArrangements"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=3, description="Debe ser 3 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Híbrido"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Trabajo combinado entre remoto y presencial.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La modalidad de trabajo registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="workArrangement", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Híbrido"),
     *                     @OA\Property(property="description", type="string", example="Trabajo combinado entre remoto y presencial."),
     *                     @OA\Property(property="settings", type="object", example={"order":2}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-01 10:15:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-01 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La modalidad de trabajo registrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
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
     *         response=500,
     *         description="Error inesperado al guardar la modalidad de trabajo.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar la modalidad de trabajo: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(WorkArrangementStoreRequest $request)
    {
        try {
            $workArrangementStoreUseCase = new WorkArrangementStoreUseCase($this->workArrangementRepository);
            $workArrangement = new WorkArrangementResource($workArrangementStoreUseCase->__invoke($request));
            return $this->successResponse(
                $workArrangement,
                'La modalidad de trabajo registrado exitosamente.',
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
