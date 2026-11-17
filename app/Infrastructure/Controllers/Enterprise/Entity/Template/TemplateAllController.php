<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Template;

use App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase\TemplateAllUseCase;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Template\TemplateAllRequest;
use App\Http\Resources\Enterprise\Entity\Template\TemplateCollection;
use App\Infrastructure\Repositories\TemplateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class TemplateAllController
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
     *     path="/Enterprises/Entities/Templates/All/{entityId}",
     *     summary="Listar plantillas de una entidad",
     *     description="Obtiene todas las plantillas asociadas a una entidad específica con soporte de búsqueda y paginación.",
     *     tags={"Enterprises - Entities - Templates"},
     *     @OA\Parameter(
     *         name="entityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda para filtrar plantillas",
     *         @OA\Schema(type="string", example="contrato")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Cantidad de registros por página",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Las plantillas de la entidad consultada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="templates", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=25),
     *                         @OA\Property(property="model", type="string", example="App\\Models\\Enterprise\\Entity"),
     *                         @OA\Property(property="name", type="string", example="Plantilla Contrato.docx"),
     *                         @OA\Property(property="file_type", type="string", example="Documento"),
     *                         @OA\Property(property="file_subtype", type="string", example="Contrato"),
     *                         @OA\Property(property="path", type="string", example="http://localhost/storage/entities/10/plantillas/contrato.docx"),
     *                         @OA\Property(property="mime", type="string", example="application/vnd.openxmlformats-officedocument.wordprocessingml.document"),
     *                         @OA\Property(property="extension", type="string", example="docx"),
     *                         @OA\Property(property="size", type="integer", example=30720),
     *                         @OA\Property(property="metadata", type="object", example={"author":"Admin","pages":12}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:45:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=5),
     *                         @OA\Property(property="count", type="integer", example=5),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=1)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las plantillas de la entidad consultada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación en parámetros de entrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="perPage", type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las plantillas de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error inesperado al obtener las plantillas de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(TemplateAllRequest $request, int $entityId)
    {
        Gate::authorize('allTemplate');
        
        try {
            $entityId = new EntityId($entityId ?? null);
            $templateAllUseCase = new TemplateAllUseCase($this->templateRepository);
            $templates = new TemplateCollection($templateAllUseCase->__invoke($request, $entityId));
            return $this->successResponse(
                $templates,
                'Las plantillas de la entidad consultada exitosamente.',
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
