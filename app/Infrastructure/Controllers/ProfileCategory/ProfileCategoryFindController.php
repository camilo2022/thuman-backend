<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ProfileCategory;

use App\Application\ProfileCategoryUseCase\ProfileCategoryFindUseCase;
use App\Domain\ProfileCategory\Values\ProfileCategoryId;
use App\Http\Resources\ProfileCategory\ProfileCategoryResource;
use App\Infrastructure\Repositories\ProfileCategoryRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ProfileCategoryFindController
{
    use ApiResponser;
    use ApiMessage;
    private $profileCategoryRepository;

    public function __construct(ProfileCategoryRepository $profileCategoryRepository)
    {
        $this->profileCategoryRepository = $profileCategoryRepository;
    }

    /**
     * @OA\Get(
     *     path="/ProfileCategories/Find/{profileCategoryId}",
     *     summary="Encontrar categoría de perfil por ID",
     *     description="Retorna el detalle de una categoría de perfil específica (incluye eliminados lógicamente).",
     *     tags={"ProfileCategories"},
     *     @OA\Parameter(
     *         name="profileCategoryId",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría de perfil a consultar",
     *         @OA\Schema(type="integer", example=6)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La categoria del perfil fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="profileCategory", type="object",
     *                     @OA\Property(property="id", type="integer", example=6),
     *                     @OA\Property(property="item_id", type="integer", example=13),
     *                     @OA\Property(property="name", type="string", example="Operativo"),
     *                     @OA\Property(property="description", type="string", example="Funciones operativas de soporte."),
     *                     @OA\Property(property="settings", type="object", example={"order":2}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La categoria del perfil fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría de perfil no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El categoria de perfil no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la categoría de perfil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el categoria de perfil: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $profileCategoryId)
    {
        try {
            $profileCategoryId = new ProfileCategoryId($profileCategoryId ?? null);
            $profileCategoryFindUseCase = new ProfileCategoryFindUseCase($this->profileCategoryRepository);
            $profileCategory = new ProfileCategoryResource($profileCategoryFindUseCase->__invoke($profileCategoryId));
            return $this->successResponse(
                $profileCategory,
                'La categoria del perfil fue encontrada exitosamente.',
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
