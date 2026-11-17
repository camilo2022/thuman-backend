<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Template;

use App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase\TemplateDeleteUseCase;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Http\Resources\Enterprise\Entity\Template\TemplateResource;
use App\Infrastructure\Repositories\TemplateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class TemplateDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Enterprises/Entities/Templates/Delete/{templateId}",
     *     summary="Eliminar plantilla de una entidad",
     *     description="Elimina lógicamente una plantilla de entidad por su ID.",
     *     tags={"Enterprises - Entities - Templates"},
     *     @OA\Parameter(
     *         name="templateId",
     *         in="path",
     *         required=true,
     *         description="ID de la plantilla a eliminar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La plantilla fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="template", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="model_id", type="integer", example=10),
     *                     @OA\Property(property="model_type", type="string", example="App\\Models\\Entity"),
     *                     @OA\Property(property="name", type="string", example="plantilla_certificado.png"),
     *                     @OA\Property(property="file_type_id", type="integer", example=98),
     *                     @OA\Property(property="file_subtype_id", type="integer", example=112),
     *                     @OA\Property(property="path", type="string", example="http://localhost/storage/entities/10/plantilla_certificado.png"),
     *                     @OA\Property(property="mime", type="string", example="image/png"),
     *                     @OA\Property(property="extension", type="string", example="png"),
     *                     @OA\Property(property="size", type="integer", example=1048576),
     *                     @OA\Property(property="metadata", type="object", example={"width":2480,"height":3508}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-08-18 10:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La plantilla fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plantilla no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La plantilla de la entidad no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar la plantilla.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar la plantilla de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $templateId)
    {
        Gate::authorize('deleteTemplate');
        
        try {
            $templateId = new TemplateId($templateId);
            $templateDeleteUseCase = new TemplateDeleteUseCase($this->templateRepository);
            $template = new TemplateResource($templateDeleteUseCase->__invoke($templateId));
            return $this->successResponse(
                $template,
                'La plantilla fue inactivada exitosamente.',
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
