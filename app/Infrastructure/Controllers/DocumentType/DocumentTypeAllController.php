<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DocumentType;

use App\Application\DocumentTypeUseCase\DocumentTypeAllUseCase;
use App\Http\Requests\DocumentType\DocumentTypeAllRequest;
use App\Http\Resources\DocumentType\DocumentTypeCollection;
use App\Infrastructure\Repositories\DocumentTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DocumentTypeAllController
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
     *     path="/DocumentTypes/All",
     *     summary="Listar tipos de documentos",
     *     description="Obtiene un listado paginado de tipos de documentos (incluye eliminados lógicamente).",
     *     tags={"DocumentTypes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="Cédula")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Los tipos de documentos consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documentTypes", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=11),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=4),
     *                             @OA\Property(property="name", type="string", example="Tipos de Documentos")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Cédula de Ciudadanía"),
     *                         @OA\Property(property="description", type="string", example="Documento nacional de identidad."),
     *                         @OA\Property(property="settings", type="object",
     *                             @OA\Property(property="abreviature", type="string", example="CC"),
     *                             @OA\Property(property="regex", type="string", example="^[0-9]{6,10}$"),
     *                             @OA\Property(property="message", type="string", example="El documento debe contener entre 6 y 10 dígitos.")
     *                         ),
     *                         @OA\Property(property="person_type", type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="item_id", type="integer", example=5),
     *                             @OA\Property(property="name", type="string", example="Empleado"),
     *                             @OA\Property(property="description", type="string", example="Persona contratada por la empresa.")
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=25),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=3)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los tipos de documentos consultados exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener los tipos de documentos.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los tipos de documentos: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(DocumentTypeAllRequest $request)
    {
        try {
            $documentTypeAllUseCase = new DocumentTypeAllUseCase($this->documentTypeRepository);
            $documentTypes = new DocumentTypeCollection($documentTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $documentTypes,
                'Los tipos de documentos fueron consultados exitosamente.',
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
