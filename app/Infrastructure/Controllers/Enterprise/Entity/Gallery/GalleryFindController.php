<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Gallery;

use App\Application\EnterpriseUseCase\EntityUseCase\GalleryUseCase\GalleryFindUseCase;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryId;
use App\Http\Resources\Enterprise\Entity\Gallery\GalleryResource;
use App\Infrastructure\Repositories\GalleryRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class GalleryFindController
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
     *     path="/Enterprises/Entities/Galleries/Find/{galleryId}",
     *     summary="Obtener detalle de una galería",
     *     description="Devuelve la información detallada de un archivo/imagen específico de la galería de una entidad.",
     *     tags={"Enterprises - Entities - Galleries"},
     *     @OA\Parameter(
     *         name="galleryId",
     *         in="path",
     *         required=true,
     *         description="ID de la galería",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Galería encontrada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="gallery",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="model_id", type="integer", example=5),
     *                 @OA\Property(property="model_type", type="string", example="App\\Models\\Entity"),
     *                 @OA\Property(property="name", type="string", example="Logo principal"),
     *                 @OA\Property(property="file_type_id", type="integer", example=98),
     *                 @OA\Property(property="file_subtype_id", type="integer", example=111),
     *                 @OA\Property(property="path", type="string", example="http://localhost/storage/entities/5/logo.png"),
     *                 @OA\Property(property="mime", type="string", example="image/png"),
     *                 @OA\Property(property="extension", type="string", example="png"),
     *                 @OA\Property(property="size", type="integer", example=204800),
     *                 @OA\Property(property="metadata", type="object", example={"width": 800, "height": 600}),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Galería no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la galería"
     *     )
     * )
     */
    public function __invoke(int $galleryId)
    {
        Gate::authorize('findGallery');
        
        try {
            $galleryId = new GalleryId($galleryId ?? null);
            $galleryFindUseCase = new GalleryFindUseCase($this->galleryRepository);
            $gallery = new GalleryResource($galleryFindUseCase->__invoke($galleryId));
            return $this->successResponse(
                $gallery,
                'La imagen fue encontrada exitosamente.',
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
