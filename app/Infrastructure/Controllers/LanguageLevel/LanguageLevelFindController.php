<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\LanguageLevel;

use App\Application\LanguageLevelUseCase\LanguageLevelFindUseCase;
use App\Domain\LanguageLevel\Values\LanguageLevelId;
use App\Http\Resources\LanguageLevel\LanguageLevelResource;
use App\Infrastructure\Repositories\LanguageLevelRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LanguageLevelFindController
{
    use ApiResponser;
    use ApiMessage;
    private $languageLevelRepository;

    public function __construct(LanguageLevelRepository $languageLevelRepository)
    {
        $this->languageLevelRepository = $languageLevelRepository;
    }

    /**
     * @OA\Get(
     *     path="/LanguageLevels/Find/{languageLevelId}",
     *     summary="Encontrar nivel de idioma por ID",
     *     description="Retorna el detalle de un nivel de idioma específico (incluye eliminados lógicamente).",
     *     tags={"LanguageLevels"},
     *     @OA\Parameter(
     *         name="languageLevelId",
     *         in="path",
     *         required=true,
     *         description="ID del nivel de idioma a consultar",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El nivel de idioma fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="languageLevel", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="item_id", type="integer", example=22),
     *                     @OA\Property(property="name", type="string", example="C1"),
     *                     @OA\Property(property="description", type="string", example="Usuario competente avanzado."),
     *                     @OA\Property(property="settings", type="object", example={"cefr":"C1","order":5}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El nivel de idioma fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nivel de idioma no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El niveles de idioma no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el nivel de idioma.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el niveles de idioma: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $languageLevelId)
    {
        try {
            $languageLevelId = new LanguageLevelId($languageLevelId ?? null);
            $languageLevelFindUseCase = new LanguageLevelFindUseCase($this->languageLevelRepository);
            $languageLevel = new LanguageLevelResource($languageLevelFindUseCase->__invoke($languageLevelId));
            return $this->successResponse(
                $languageLevel,
                'El nivel de idioma fue encontrada exitosamente.',
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
