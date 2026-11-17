<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Gallery;

use App\Application\EnterpriseUseCase\EntityUseCase\GalleryUseCase\GalleryStoreUseCase;
use App\Http\Requests\Enterprise\Entity\Gallery\GalleryStoreRequest;
use App\Http\Resources\Enterprise\Entity\Gallery\GalleryResource;
use App\Infrastructure\Repositories\GalleryRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class GalleryStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $galleryRepository;

    public function __construct(GalleryRepository $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @OA\Post(
     *     path="/Enterprises/Entities/Galleries/Store",
     *     summary="Registrar archivo/imagen en la galería de una entidad",
     *     description="Sube un archivo (png/jpg/jpeg, máx. 5 MB) a la galería de una entidad. Requiere tipología de archivo válida.",
     *     tags={"Enterprises - Entities - Galleries"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"file_type_id","file_subtype_id","file"},
     *                 @OA\Property(
     *                     property="file_type_id",
     *                     type="integer",
     *                     example=103,
     *                     description="Tipo de archivo (subitems, item_id=16). Debe ser 103."
     *                 ),
     *                 @OA\Property(
     *                     property="file_subtype_id",
     *                     type="integer",
     *                     example=135,
     *                     description="Subtipo de archivo (subitems, item_id=17). Permitidos: 135, 136, 137."
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo a subir (mimes: png,jpg,jpeg; max: 5120 KB)."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La foto fue registrada exitosamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="gallery",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="model_id", type="integer", example=5),
     *                 @OA\Property(property="model_type", type="string", example="App\\Models\\Entity"),
     *                 @OA\Property(property="name", type="string", example="logo_principal.png"),
     *                 @OA\Property(property="file_type_id", type="integer", example=103),
     *                 @OA\Property(property="file_subtype_id", type="integer", example=135),
     *                 @OA\Property(property="path", type="string", example="http://localhost/storage/entities/5/logo_principal.png"),
     *                 @OA\Property(property="mime", type="string", example="image/png"),
     *                 @OA\Property(property="extension", type="string", example="png"),
     *                 @OA\Property(property="size", type="integer", example=204800),
     *                 @OA\Property(property="metadata", type="object", example={"width":1024,"height":512}),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="file_type_id", type="array",
     *                     @OA\Items(type="string", example="Valor inválido. Permitidos: 103.")
     *                 ),
     *                 @OA\Property(property="file_subtype_id", type="array",
     *                     @OA\Items(type="string", example="Valor inválido. Permitidos: 135, 136, 137.")
     *                 ),
     *                 @OA\Property(property="file", type="array",
     *                     @OA\Items(type="string", example="Extensión inválida. Permitidas png,jpg,jpeg.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar el archivo de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al guardar el archivo de la entidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(GalleryStoreRequest $request)
    {
        Gate::authorize('storeGallery');
        
        try {
            $galleryStoreUseCase = new GalleryStoreUseCase($this->galleryRepository);
            $gallery = new GalleryResource($galleryStoreUseCase->__invoke($request));
            return $this->successResponse(
                $gallery,
                'La imagen fue registrada exitosamente.',
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
