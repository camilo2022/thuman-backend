<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocument;

use App\Application\FiscalDocumentUseCase\FiscalDocumentFindUseCase;
use App\Domain\FiscalDocument\Values\FiscalDocumentId;
use App\Http\Resources\FiscalDocument\FiscalDocumentResource;
use App\Infrastructure\Repositories\FiscalDocumentRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentFindController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentRepository;

    public function __construct(FiscalDocumentRepository $fiscalDocumentRepository)
    {
        $this->fiscalDocumentRepository = $fiscalDocumentRepository;
    }

    /**
     * @OA\Get(
     *     path="/FiscalDocuments/Find/{fiscalDocumentId}",
     *     summary="Obtener documento fiscal por ID",
     *     description="Retorna la información detallada de un documento fiscal específico.",
     *     tags={"FiscalDocuments"},
     *     @OA\Parameter(
     *         name="fiscalDocumentId",
     *         in="path",
     *         required=true,
     *         description="ID del documento fiscal",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El documento fiscal fue encontrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="fiscalDocument", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="item_id", type="integer", example=31),
     *                     @OA\Property(property="name", type="string", example="Protección Pensiones"),
     *                     @OA\Property(property="description", type="string", example="Fondo privado de pensiones."),
     *                     @OA\Property(property="settings", type="object", example={"visible": true}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El documento fiscal fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El documento fiscal no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El documento fiscal no existe."),
     *             @OA\Property(property="error", type="string", example="El documento fiscal no existe: No se encontró el ID."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el documento fiscal.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el documento fiscal: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $fiscalDocumentId)
    {
        try {
            $fiscalDocumentId = new FiscalDocumentId($fiscalDocumentId ?? null);
            $fiscalDocumentFindUseCase = new FiscalDocumentFindUseCase($this->fiscalDocumentRepository);
            $fiscalDocument = new FiscalDocumentResource($fiscalDocumentFindUseCase->__invoke($fiscalDocumentId));
            return $this->successResponse(
                $fiscalDocument,
                'El documento fiscal fue encontrada exitosamente.',
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
