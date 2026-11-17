<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DocumentType;

use App\Application\DocumentTypeUseCase\DocumentTypeUpdateUseCase;
use App\Domain\DocumentType\Values\DocumentTypeId;
use App\Http\Requests\DocumentType\DocumentTypeUpdateRequest;
use App\Http\Resources\DocumentType\DocumentTypeResource;
use App\Infrastructure\Repositories\DocumentTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DocumentTypeUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepository)
    {
        $this->documentTypeRepository = $documentTypeRepository;
    }

    /**
     * @OA\Put(
     *     path="/DocumentTypes/Update/{documentTypeId}",
     *     summary="Actualizar un tipo de documento",
     *     description="Actualiza un tipo de documento existente por su ID.",
     *     tags={"DocumentTypes"},
     *     @OA\Parameter(
     *         name="documentTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de documento a actualizar",
     *         @OA\Schema(type="integer", example=11)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"person_type_id","item_id","name","description","settings"},
     *             @OA\Property(property="person_type_id", type="integer", example=2, description="ID de subitem perteneciente al item 5 (Tipos de Personas)"),
     *             @OA\Property(property="item_id", type="integer", example=4, description="Debe ser 4 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Cédula de Ciudadanía"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Documento nacional de identidad."),
     *             @OA\Property(
     *                 property="settings",
     *                 type="object",
     *                 required={"abreviature","regex","message"},
     *                 @OA\Property(property="abreviature", type="string", example="CC"),
     *                 @OA\Property(property="regex", type="string", example="^[0-9]{6,10}$"),
     *                 @OA\Property(property="message", type="string", maxLength=255, example="El documento debe contener entre 6 y 10 dígitos.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo de documento fue actualizado exitosamente.",
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
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-02 14:10:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de documento fue actualizado exitosamente."),
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
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="person_type_id", type="string", example="Tipo de persona"),
     *                 @OA\Property(property="item_id", type="string", example="Categorización"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción"),
     *                 @OA\Property(property="settings", type="string", example="Configuración"),
     *                 @OA\Property(property="settings.abreviature", type="string", example="Abreviatura"),
     *                 @OA\Property(property="settings.regex", type="string", example="Expresión regular"),
     *                 @OA\Property(property="settings.message", type="string", example="Mensaje")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="person_type_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="Debe ser 4.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres.")),
     *                 @OA\Property(property="settings", type="array", @OA\Items(type="string", example="Debe ser una lista de datos.")),
     *                 @OA\Property(property="settings.abreviature", type="array", @OA\Items(type="string", example="Formato de abreviatura inválido. Ejemplos permitidos: S.A.S., LTDA, CC.")),
     *                 @OA\Property(property="settings.regex", type="array", @OA\Items(type="string", example="Debe ser una expresión regular válida.")),
     *                 @OA\Property(property="settings.message", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el tipo de documento.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar el tipo de documento: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(DocumentTypeUpdateRequest $request, int $documentTypeId)
    {
        try {
            $documentTypeId = new DocumentTypeId($documentTypeId ?? null);
            $documentTypeUpdateUseCase = new DocumentTypeUpdateUseCase($this->documentTypeRepository);
            $documentType = new DocumentTypeResource($documentTypeUpdateUseCase->__invoke($request, $documentTypeId));
            return $this->successResponse(
                $documentType,
                'El tipo de documento fue actualizado exitosamente.',
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
