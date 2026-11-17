<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Template;

use App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase\TemplateUpdateUseCase;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Http\Requests\Enterprise\Entity\Template\TemplateUpdateRequest;
use App\Http\Resources\Enterprise\Entity\Template\TemplateResource;
use App\Infrastructure\Repositories\TemplateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class TemplateUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @OA\Put(
     *     path="/Enterprises/Entities/Templates/Update/{templateId}",
     *     summary="Actualizar una plantilla de entidad",
     *     description="Permite actualizar los datos de una plantilla asociada a una entidad.",
     *     tags={"Enterprises - Entities - Templates"},
     *     @OA\Parameter(
     *         name="templateId",
     *         in="path",
     *         required=true,
     *         description="ID de la plantilla a actualizar",
     *         @OA\Schema(type="integer", example=25)
     *     ),
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
     *                     example=113
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
     *         response=200,
     *         description="La plantilla fue actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="template", type="object",
     *                     @OA\Property(property="id", type="integer", example=25),
     *                     @OA\Property(property="model_id", type="integer", example=10),
     *                     @OA\Property(property="model_type", type="string", example="App\\Models\\Entity"),
     *                     @OA\Property(property="name", type="string", example="Plantilla Actualizada.jpg"),
     *                     @OA\Property(property="file_type_id", type="integer", example=98),
     *                     @OA\Property(property="file_subtype_id", type="integer", example=113),
     *                     @OA\Property(property="path", type="string", example="http://localhost/storage/entities/10/templates/actualizada.jpg"),
     *                     @OA\Property(property="mime", type="string", example="image/jpeg"),
     *                     @OA\Property(property="extension", type="string", example="jpg"),
     *                     @OA\Property(property="size", type="integer", example=460800),
     *                     @OA\Property(property="metadata", type="object", example={"author":"Admin","resolution":"1280x720"}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La plantilla fue actualizada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La plantilla no existe",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La plantilla de la entidad no existe.")
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
     *         description="Error interno al actualizar la plantilla",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error inesperado al actualizar la plantilla de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(TemplateUpdateRequest $request, int $templateId)
    {
        Gate::authorize('updateTemplate');
        
        try {
            $templateId = new TemplateId($templateId ?? null);
            $templateUpdateUseCase = new TemplateUpdateUseCase($this->templateRepository);
            $template = new TemplateResource($templateUpdateUseCase->__invoke($request, $templateId));
            return $this->successResponse(
                $template,
                'La plantilla fue actualizada exitosamente.',
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
