<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocument;

use App\Application\FiscalDocumentUseCase\FiscalDocumentStoreUseCase;
use App\Http\Requests\FiscalDocument\FiscalDocumentStoreRequest;
use App\Http\Resources\FiscalDocument\FiscalDocumentResource;
use App\Infrastructure\Repositories\FiscalDocumentRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentRepository;

    public function __construct(FiscalDocumentRepository $fiscalDocumentRepository)
    {
        $this->fiscalDocumentRepository = $fiscalDocumentRepository;
    }

    /**
     * @OA\Post(
     *     path="/FiscalDocuments/Store",
     *     summary="Registrar documento fiscal",
     *     description="Crea un nuevo registro de documento fiscal.",
     *     tags={"FiscalDocuments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=31, description="Categorización (debe corresponder al ítem de fondos de pensiones)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Colfondos", description="Nombre del documento fiscal"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Fondo de pensiones obligatorio y voluntario", description="Descripción del fondo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El documento fiscal registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="fiscalDocument", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="item_id", type="integer", example=31),
     *                     @OA\Property(property="name", type="string", example="Colfondos"),
     *                     @OA\Property(property="description", type="string", example="Fondo de pensiones obligatorio y voluntario"),
     *                     @OA\Property(property="settings", type="object", example={"visible":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El documento fiscal registrado exitosamente."),
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
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar el documento fiscal.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar el documento fiscal: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(FiscalDocumentStoreRequest $request)
    {
        try {
            $fiscalDocumentStoreUseCase = new FiscalDocumentStoreUseCase($this->fiscalDocumentRepository);
            $fiscalDocument = new FiscalDocumentResource($fiscalDocumentStoreUseCase->__invoke($request));
            return $this->successResponse(
                $fiscalDocument,
                'El documento fiscal fue registrado exitosamente.',
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
