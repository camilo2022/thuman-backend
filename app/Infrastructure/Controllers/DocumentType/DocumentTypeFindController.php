<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DocumentType;

use App\Application\DocumentTypeUseCase\DocumentTypeFindUseCase;
use App\Domain\DocumentType\Values\DocumentTypeId;
use App\Http\Resources\DocumentType\DocumentTypeResource;
use App\Infrastructure\Repositories\DocumentTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DocumentTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepository)
    {
        $this->documentTypeRepository = $documentTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/DocumentTypes/Find/{documentTypeId}",
     *     summary="Encontrar tipo de documento por ID",
     *     description="Retorna el detalle de un tipo de documento específico (incluye eliminados lógicamente).",
     *     tags={"DocumentTypes"},
     *     @OA\Parameter(
     *         name="documentTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de documento a consultar",
     *         @OA\Schema(type="integer", example=11)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo de documento fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documentType", type="object",
     *                     @OA\Property(property="id", type="integer", example=11),
     *                     @OA\Property(property="item_id", type="integer", example=4),
     *                     @OA\Property(property="person_type_id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Cédula de Ciudadanía"),
     *                     @OA\Property(property="description", type="string", example="Documento nacional de identidad."),
     *                     @OA\Property(property="settings", type="object",
     *                         @OA\Property(property="abreviature", type="string", example="CC"),
     *                         @OA\Property(property="regex", type="string", example="^[0-9]{6,10}$"),
     *                         @OA\Property(property="message", type="string", example="El documento debe contener entre 6 y 10 dígitos.")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de documento fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de documento no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El tipo de documento no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el tipo de documento.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el tipo de documento: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $documentTypeId)
    {
        try {
            $documentTypeId = new DocumentTypeId($documentTypeId ?? null);
            $documentTypeFindUseCase = new DocumentTypeFindUseCase($this->documentTypeRepository);
            $documentType = new DocumentTypeResource($documentTypeFindUseCase->__invoke($documentTypeId));
            return $this->successResponse(
                $documentType,
                'El tipo de documento fue encontrado exitosamente.',
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
