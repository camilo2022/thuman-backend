<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocument;

use App\Application\FiscalDocumentUseCase\FiscalDocumentRestoreUseCase;
use App\Domain\FiscalDocument\Values\FiscalDocumentId;
use App\Http\Resources\FiscalDocument\FiscalDocumentResource;
use App\Infrastructure\Repositories\FiscalDocumentRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentRepository;

    public function __construct(FiscalDocumentRepository $fiscalDocumentRepository)
    {
        $this->fiscalDocumentRepository = $fiscalDocumentRepository;
    }

    /**
     * @OA\Patch(
     *     path="/FiscalDocuments/Restore/{fiscalDocumentId}",
     *     summary="Restaurar documento fiscal",
     *     description="Restaura lógicamente un documento fiscal por su ID.",
     *     tags={"FiscalDocuments"},
     *     @OA\Parameter(
     *         name="fiscalDocumentId",
     *         in="path",
     *         required=true,
     *         description="ID del documento fiscal a restaurar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El documento fiscal fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="fiscalDocument", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="item_id", type="integer", example=31),
     *                     @OA\Property(property="name", type="string", example="Protección Pensiones"),
     *                     @OA\Property(property="description", type="string", example="Fondo de pensión — régimen obligatorio"),
     *                     @OA\Property(property="settings", type="object", example={"visible":true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El documento fiscal fue restaurada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fondo de pensión no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="El documento fiscal no existe: No se encontró el ID especificado."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al restaurar el documento fiscal.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar el documento fiscal: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $fiscalDocumentId)
    {
        try {
            $fiscalDocumentId = new FiscalDocumentId($fiscalDocumentId);
            $fiscalDocumentRestoreUseCase = new FiscalDocumentRestoreUseCase($this->fiscalDocumentRepository);
            $fiscalDocument = new FiscalDocumentResource($fiscalDocumentRestoreUseCase->__invoke($fiscalDocumentId));
            return $this->successResponse(
                $fiscalDocument,
                'El documento fiscal fue activado exitosamente.',
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
