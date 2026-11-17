<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Gallery;

use App\Application\EnterpriseUseCase\EntityUseCase\GalleryUseCase\GalleryAllUseCase;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Gallery\GalleryAllRequest;
use App\Http\Resources\Enterprise\Entity\Gallery\GalleryCollection;
use App\Infrastructure\Repositories\GalleryRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class GalleryAllController
{
    use ApiResponser;
    use ApiMessage;
    private $galleryRepository;

    public function __construct(GalleryRepository $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @OA\Get(
     *     path="/Enterprises/Entities/Galleries/All/{entityId}",
     *     summary="Listar galerías de una entidad",
     *     description="Obtiene todas las galerías (con paginación y filtro opcional de búsqueda) asociadas a una entidad específica.",
     *     tags={"Enterprises - Entities - Galleries"},
     *     @OA\Parameter(
     *         name="entityId",
     *         in="path",
     *         required=true,
     *         description="ID de la entidad a la que pertenecen las galerías",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o metadatos",
     *         @OA\Schema(type="string", example="logo")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de resultados por página",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Galerías obtenidas exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="galleries",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="model", type="object", example={"id": 5, "name": "Entidad Empresarial S.A.S."}),
     *                     @OA\Property(property="name", type="string", example="Logo Principal"),
     *                     @OA\Property(property="file_type", type="string", example="image"),
     *                     @OA\Property(property="file_subtype", type="string", example="logo"),
     *                     @OA\Property(property="path", type="string", example="http://localhost/storage/entities/5/logo.png"),
     *                     @OA\Property(property="mime", type="string", example="image/png"),
     *                     @OA\Property(property="extension", type="string", example="png"),
     *                     @OA\Property(property="size", type="integer", example=204800),
     *                     @OA\Property(property="metadata", type="object", example={"width": 800, "height": 600}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="total", type="integer", example=25),
     *                     @OA\Property(property="count", type="integer", example=10),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="total_pages", type="integer", example=3)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Entidad no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las galerías de la entidad"
     *     )
     * )
     */
    public function __invoke(GalleryAllRequest $request, int $entityId)
    {
        Gate::authorize('allGallery');
        
        try {
            $entityId = new EntityId($entityId ?? null);
            $galleryAllUseCase = new GalleryAllUseCase($this->galleryRepository);
            $galleries = new GalleryCollection($galleryAllUseCase->__invoke($request, $entityId));
            return $this->successResponse(
                $galleries,
                'La galeria de la entidad consultada exitosamente.',
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
