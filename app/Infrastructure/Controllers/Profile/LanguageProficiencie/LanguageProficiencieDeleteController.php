<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\LanguageProficiencie;

use App\Application\ProfileUseCase\LanguageProficiencieUseCase\LanguageProficiencieDeleteUseCase;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieId;
use App\Infrastructure\Repositories\LanguageProficiencieRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class LanguageProficiencieDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $languageProficiencieRepository;

    public function __construct(LanguageProficiencieRepository $languageProficiencieRepository)
    {
        $this->languageProficiencieRepository = $languageProficiencieRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Profiles/LanguageProficiencies/Delete/{languageProficiencieId}",
     *     summary="Eliminar un idioma de un perfil",
     *     tags={"Profiles - LanguageProficiencies"},
     *     @OA\Parameter(
     *         name="languageProficiencieId",
     *         in="path",
     *         description="ID del idioma asociado al perfil a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=501)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El idioma fue eliminado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="string", example=""),
     *             @OA\Property(property="message", type="string", example="El idioma fue eliminado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
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
     *         description="Error inesperado al eliminar el idioma.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar el idioma: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $languageProficiencieId)
    {
        Gate::authorize('deleteLanguageProficiencie', $languageProficiencieId);
        
        try {
            $languageProficiencieId = new LanguageProficiencieId($languageProficiencieId);
            $languageProficiencieDeleteUseCase = new LanguageProficiencieDeleteUseCase($this->languageProficiencieRepository);
            $languageProficiencieDeleteUseCase->__invoke($languageProficiencieId);
            return $this->successResponse(
                '',
                'El idioma fue eliminado exitosamente.',
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
