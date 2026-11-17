<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DocumentType;

use App\Application\DocumentTypeUseCase\DocumentTypeSettingUseCase;
use App\Domain\DocumentType\Values\DocumentTypeId;
use App\Http\Requests\DocumentType\DocumentTypeSettingRequest;
use App\Http\Resources\DocumentType\DocumentTypeResource;
use App\Infrastructure\Repositories\DocumentTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DocumentTypeSettingController
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
     *     path="/DocumentTypes/Setting/{documentTypeId}",
     *     summary="Configura un tipo de documento",
     *     description="Configura un tipo de documento existente por su ID.",
     *     tags={"DocumentTypes"},
     *     @OA\Parameter(
     *         name="documentTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de documento a configurar",
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
     *                 required={"abreviature","validations"},
     *                 @OA\Property(
     *                     property="abreviature",
     *                     type="string",
     *                     example="CC",
     *                     description="Debe cumplir el patrón: ^[A-ZÁÉÍÓÚÜ]{2,10}(\\.[A-ZÁÉÍÓÚÜ]{1,10})*\\.?$"
     *                 ),
     *                 @OA\Property(
     *                     property="validations",
     *                     type="array",
     *                     minItems=1,
     *                     description="Lista de configuraciones por país",
     *                     @OA\Items(
     *                         type="object",
     *                         required={"country_id","regex","message"},
     *                         @OA\Property(property="id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="country_id", type="integer", example=48, description="Debe existir en tabla countries"),
     *                         @OA\Property(property="regex", type="string", example="/^[0-9]{6,10}$/"),
     *                         @OA\Property(property="message", type="string", maxLength=255, example="El documento debe contener entre 6 y 10 dígitos."),
     *                         @OA\Property(property="created_at", type="string", format="date-time", nullable=true, example="2025-03-02 14:10:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example="2025-05-01 08:00:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 )
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
     *                     @OA\Property(
     *                         property="settings",
     *                         type="object",
     *                         @OA\Property(property="abreviature", type="string", example="CC"),
     *                         @OA\Property(
     *                             property="validations",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=7),
     *                                 @OA\Property(property="country_id", type="integer", example=48),
     *                                 @OA\Property(property="regex", type="string", example="/^[0-9]{6,10}$/"),
     *                                 @OA\Property(property="message", type="string", example="El documento debe contener entre 6 y 10 dígitos."),
     *                                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-02 14:10:00"),
     *                                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-01 08:00:00"),
     *                                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                             )
     *                         )
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
     *                 @OA\Property(property="settings.validations", type="string", example="Validaciones"),
     *                 @OA\Property(property="settings.validations.0.country_id", type="string", example="País (configuración n° 1)"),
     *                 @OA\Property(property="settings.validations.0.regex", type="string", example="Expresión regular (configuración n° 1)"),
     *                 @OA\Property(property="settings.validations.0.message", type="string", example="Mensaje (configuración n° 1)")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="person_type_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="Debe ser 4.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres.")),
     *                 @OA\Property(property="settings", type="array", @OA\Items(type="string", example="Debe ser una lista de datos.")),
     *                 @OA\Property(property="settings.abreviature", type="array", @OA\Items(type="string", example="Formato de abreviatura inválido. Ejemplos permitidos: S.A.S., LTDA, CC.")),
     *                 @OA\Property(property="settings.validations", type="array", @OA\Items(type="string", example="Debe tener minimo 1 validación.")),
     *                 @OA\Property(property="settings.validations.0.country_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="settings.validations.0.regex", type="array", @OA\Items(type="string", example="Debe ser una expresión regular válida.")),
     *                 @OA\Property(property="settings.validations.0.message", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
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
    public function __invoke(DocumentTypeSettingRequest $request, int $documentTypeId)
    {
        try {
            $documentTypeId = new DocumentTypeId($documentTypeId ?? null);
            $documentTypeSettingUseCase = new DocumentTypeSettingUseCase($this->documentTypeRepository);
            $documentType = new DocumentTypeResource($documentTypeSettingUseCase->__invoke($request, $documentTypeId));
            return $this->successResponse(
                $documentType,
                'La configuración del tipo de documento fue realizada exitosamente.',
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
