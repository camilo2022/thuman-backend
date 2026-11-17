<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\LanguageProficiencie;

use App\Application\ProfileUseCase\LanguageProficiencieUseCase\LanguageProficiencieFindUseCase;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieId;
use App\Http\Resources\Profile\LanguageProficiencie\LanguageProficiencieResource;
use App\Infrastructure\Repositories\LanguageProficiencieRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class LanguageProficiencieFindController
{
    use ApiResponser;
    use ApiMessage;
    private $languageProficiencieRepository;

    public function __construct(LanguageProficiencieRepository $languageProficiencieRepository)
    {
        $this->languageProficiencieRepository = $languageProficiencieRepository;
    }

    /**
     * @OA\Get(
     *     path="/Profiles/LanguageProficiencies/Find/{languageProficiencieId}",
     *     summary="Buscar un idioma de un perfil",
     *     tags={"Profiles - LanguageProficiencies"},
     *     @OA\Parameter(
     *         name="languageProficiencieId",
     *         in="path",
     *         description="ID del idioma asociado al perfil",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=501)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El idioma fue encontrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="languageProficiencie", type="object",
     *                     @OA\Property(property="id", type="integer", example=501),
     *                     @OA\Property(property="profile", type="integer", example=12),
     *                     @OA\Property(property="language_id", type="integer", example=1),
     *                     @OA\Property(property="language_level_id", type="integer", example=3),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El idioma fue encontrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el idioma.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El idioma no existe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el idioma.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar el idioma: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $languageProficiencieId)
    {
        Gate::authorize('findLanguageProficiencie', $languageProficiencieId);
        
        try {
            $languageProficiencieId = new LanguageProficiencieId($languageProficiencieId ?? null);
            $languageProficiencieFindUseCase = new LanguageProficiencieFindUseCase($this->languageProficiencieRepository);
            $languageProficiencie = new LanguageProficiencieResource($languageProficiencieFindUseCase->__invoke($languageProficiencieId));
            return $this->successResponse(
                $languageProficiencie,
                'El idioma fue encontrado exitosamente.',
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
