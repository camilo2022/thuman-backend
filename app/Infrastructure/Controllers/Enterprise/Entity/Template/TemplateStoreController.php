<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Template;

use App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase\TemplateStoreUseCase;
use App\Http\Requests\Enterprise\Entity\Template\TemplateStoreRequest;
use App\Http\Resources\Enterprise\Entity\Template\TemplateResource;
use App\Infrastructure\Repositories\TemplateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class TemplateStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @OA\Post(
     *     path="/Enterprises/Entities/Templates/Store",
     *     summary="Registrar una nueva plantilla de entidad",
     *     description="Permite almacenar una nueva plantilla asociada a una entidad.",
     *     tags={"Enterprises - Entities - Templates"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"entity_id","file_type_id","file_subtype_id","file"},
     *                 @OA\Property(
     *                     property="entity_id",
     *                     type="integer",
     *                     description="ID de la entidad",
     *                     example=10
     *                 ),
     *                 @OA\Property(
     *                     property="file_type_id",
     *                     type="integer",
     *                     description="Tipo de archivo (debe ser 98)",
     *                     example=98
     *                 ),
     *                 @OA\Property(
     *                     property="file_subtype_id",
     *                     type="integer",
     *                     description="Subtipo de archivo (ej. 111–118). Debe ser único por entidad.",
     *                     example=112
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de la plantilla (png, jpg, jpeg). Máx 5 MB"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La plantilla fue registrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="template", type="object",
     *                     @OA\Property(property="id", type="integer", example=25),
     *                     @OA\Property(property="model_id", type="integer", example=10),
     *                     @OA\Property(property="model_type", type="string", example="App\\Models\\Entity"),
     *                     @OA\Property(property="name", type="string", example="Plantilla Encabezado.jpg"),
     *                     @OA\Property(property="file_type_id", type="integer", example=98),
     *                     @OA\Property(property="file_subtype_id", type="integer", example=112),
     *                     @OA\Property(property="path", type="string", example="http://localhost/storage/entities/10/templates/encabezado.jpg"),
     *                     @OA\Property(property="mime", type="string", example="image/jpeg"),
     *                     @OA\Property(property="extension", type="string", example="jpg"),
     *                     @OA\Property(property="size", type="integer", example=512000),
     *                     @OA\Property(property="metadata", type="object", example={"author":"Admin","resolution":"1920x1080"}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La plantilla fue registrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación en los campos enviados",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="file", type="array",
     *                     @OA\Items(type="string", example="Extensión inválida. Permitidas png, jpg, jpeg.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno al guardar la plantilla",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error inesperado al guardar la plantilla de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(TemplateStoreRequest $request)
    {
        Gate::authorize('storeTemplate');
        
        try {
            $templateStoreUseCase = new TemplateStoreUseCase($this->templateRepository);
            $template = new TemplateResource($templateStoreUseCase->__invoke($request));
            return $this->successResponse(
                $template,
                'La plantilla fue registrada exitosamente.',
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
