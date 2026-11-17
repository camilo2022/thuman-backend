<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Language;

use App\Application\LanguageUseCase\LanguageFindUseCase;
use App\Domain\Language\Values\LanguageId;
use App\Http\Resources\Language\LanguageResource;
use App\Infrastructure\Repositories\LanguageRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LanguageFindController
{
    use ApiResponser;
    use ApiMessage;
    private $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @OA\Get(
     *     path="/Languages/Find/{languageId}",
     *     summary="Encontrar idioma por ID",
     *     description="Retorna el detalle de un idioma específico (incluye eliminados lógicamente).",
     *     tags={"Languages"},
     *     @OA\Parameter(
     *         name="languageId",
     *         in="path",
     *         required=true,
     *         description="ID del idioma a consultar",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El idioma fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="language", type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="item_id", type="integer", example=12),
     *                     @OA\Property(property="name", type="string", example="Inglés"),
     *                     @OA\Property(property="description", type="string", example="Idioma inglés."),
     *                     @OA\Property(property="settings", type="object", example={"iso":"en","nativeName":"English"}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El idioma fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Idioma no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El idioma no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el idioma.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el idioma: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $languageId)
    {
        try {
            $languageId = new LanguageId($languageId ?? null);
            $languageFindUseCase = new LanguageFindUseCase($this->languageRepository);
            $language = new LanguageResource($languageFindUseCase->__invoke($languageId));
            return $this->successResponse(
                $language,
                'El idioma fue encontrada exitosamente.',
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
