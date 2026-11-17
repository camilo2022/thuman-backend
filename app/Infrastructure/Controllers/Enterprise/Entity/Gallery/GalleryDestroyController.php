<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Gallery;

use App\Application\EnterpriseUseCase\EntityUseCase\GalleryUseCase\GalleryDestroyUseCase;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryId;
use App\Http\Resources\Enterprise\Entity\Gallery\GalleryResource;
use App\Infrastructure\Repositories\GalleryRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class GalleryDestroyController
{
    use ApiResponser;
    use ApiMessage;
    private $galleryRepository;

    public function __construct(GalleryRepository $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Enterprises/Entities/Galleries/Destroy/{galleryId}",
     *     summary="Eliminar archivo/imagen de la galería de una entidad",
     *     description="Elimina lógicamente un registro de galería por su ID.",
     *     tags={"Enterprises - Entities - Galleries"},
     *     @OA\Parameter(
     *         name="galleryId",
     *         in="path",
     *         required=true,
     *         description="ID del archivo/imagen a eliminar",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La foto fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="gallery", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="model_id", type="integer", example=5),
     *                     @OA\Property(property="model_type", type="string", example="App\\Models\\Entity"),
     *                     @OA\Property(property="name", type="string", example="logo_principal.png"),
     *                     @OA\Property(property="file_type_id", type="integer", example=103),
     *                     @OA\Property(property="file_subtype_id", type="integer", example=135),
     *                     @OA\Property(property="path", type="string", example="http://localhost/storage/entities/5/logo_principal.png"),
     *                     @OA\Property(property="mime", type="string", example="image/png"),
     *                     @OA\Property(property="extension", type="string", example="png"),
     *                     @OA\Property(property="size", type="integer", example=204800),
     *                     @OA\Property(property="metadata", type="object", example={"width":1024,"height":512}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-08-18 11:30:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La foto fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Archivo de la entidad no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El archivo de la entidad no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar el archivo de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar el archivo de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $galleryId)
    {
        // Gate::authorize('destroyGallery');
        
        try {
            $galleryId = new GalleryId($galleryId);
            $galleryDestroyUseCase = new GalleryDestroyUseCase($this->galleryRepository);
            $galleryDestroyUseCase->__invoke($galleryId);
            return $this->successResponse(
                '',
                'La imagen fue eliminada exitosamente.',
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
