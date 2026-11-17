<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Template;

use App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase\TemplateFindUseCase;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Http\Requests\Enterprise\Entity\Template\TemplateFindRequest;
use App\Http\Resources\Enterprise\Entity\Template\TemplateResource;
use App\Infrastructure\Repositories\TemplateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class TemplateFindController
{
    use ApiResponser;
    use ApiMessage;
    private $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @OA\Get(
     *     path="/Enterprises/Entities/Templates/Find/{templateId}",
     *     summary="Consultar una plantilla de entidad",
     *     description="Obtiene la información detallada de una plantilla específica de la entidad.",
     *     tags={"Enterprises - Entities - Templates"},
     *     @OA\Parameter(
     *         name="templateId",
     *         in="path",
     *         required=true,
     *         description="ID de la plantilla",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La plantilla fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="template", type="object",
     *                     @OA\Property(property="id", type="integer", example=15),
     *                     @OA\Property(property="model_id", type="integer", example=10),
     *                     @OA\Property(property="model_type", type="string", example="App\\Models\\Entity"),
     *                     @OA\Property(property="name", type="string", example="Plantilla Factura.docx"),
     *                     @OA\Property(property="file_type_id", type="integer", example=98),
     *                     @OA\Property(property="file_subtype_id", type="integer", example=112),
     *                     @OA\Property(property="path", type="string", example="http://localhost/storage/entities/10/plantillas/factura.docx"),
     *                     @OA\Property(property="mime", type="string", example="application/vnd.openxmlformats-officedocument.wordprocessingml.document"),
     *                     @OA\Property(property="extension", type="string", example="docx"),
     *                     @OA\Property(property="size", type="integer", example=24576),
     *                     @OA\Property(property="metadata", type="object", example={"author":"Admin","pages":3}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 08:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La plantilla fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La plantilla no existe",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La plantilla de la entidad no existe: No query results for model [App\\Models\\Template] 15")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al buscar la plantilla de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error inesperado al buscar la plantilla de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(TemplateFindRequest $request, int|null $templateId = null)
    {
        Gate::authorize('findTemplate');
        
        try {
            $templateId = new TemplateId($templateId);
            $templateFindUseCase = new TemplateFindUseCase($this->templateRepository);
            $template = new TemplateResource($templateFindUseCase->__invoke($request, $templateId));
            return $this->successResponse(
                $template,
                'La plantilla fue encontrada exitosamente.',
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
