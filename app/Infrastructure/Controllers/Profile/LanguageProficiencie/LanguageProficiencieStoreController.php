<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\LanguageProficiencie;

use App\Application\ProfileUseCase\LanguageProficiencieUseCase\LanguageProficiencieStoreUseCase;
use App\Http\Requests\Profile\LanguageProficiencie\LanguageProficiencieStoreRequest;
use App\Http\Resources\Profile\LanguageProficiencie\LanguageProficiencieResource;
use App\Infrastructure\Repositories\LanguageProficiencieRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class LanguageProficiencieStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $languageProficiencieRepository;

    public function __construct(LanguageProficiencieRepository $languageProficiencieRepository)
    {
        $this->languageProficiencieRepository = $languageProficiencieRepository;
    }

    /**
     * @OA\Post(
     *     path="/Profiles/LanguageProficiencies/Store",
     *     summary="Registrar un idioma en un perfil",
     *     tags={"Profiles - LanguageProficiencies"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para registrar un idioma",
     *         @OA\JsonContent(
     *             required={"profile_id","language_id","language_level_id"},
     *             @OA\Property(property="profile_id", type="integer", format="int64", example=12, description="Debe existir en profiles.id"),
     *             @OA\Property(property="language_id", type="integer", format="int64", example=1, description="Debe existir en subitems con item_id=23 (Idiomas)"),
     *             @OA\Property(property="language_level_id", type="integer", format="int64", example=3, description="Debe existir en subitems con item_id=22 (Niveles idiomáticos)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El idioma fue registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="languageProficiencie", type="object",
     *                     @OA\Property(property="id", type="integer", example=501),
     *                     @OA\Property(property="profile", type="integer", example=12),
     *                     @OA\Property(property="language_id", type="integer", example=1),
     *                     @OA\Property(property="language_level_id", type="integer", example=3),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El idioma fue registrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(
     *                 property="attributes",
     *                 type="object",
     *                 @OA\Property(property="profile_id", type="string", example="Perfil"),
     *                 @OA\Property(property="language_id", type="string", example="Idioma"),
     *                 @OA\Property(property="language_level_id", type="string", example="Nivel idiomático")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="profile_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 ),
     *                 @OA\Property(
     *                     property="language_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="Es requerido.")
     *                 ),
     *                 @OA\Property(
     *                     property="language_level_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar el idioma.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al guardar el idioma: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(LanguageProficiencieStoreRequest $request)
    {
        Gate::authorize('storeLanguageProficiencie', $request->input('profile_id'));
        
        try {
            $languageProficiencieStoreUseCase = new LanguageProficiencieStoreUseCase($this->languageProficiencieRepository);
            $languageProficiencie = new LanguageProficiencieResource($languageProficiencieStoreUseCase->__invoke($request));
            return $this->successResponse(
                $languageProficiencie,
                'El idioma fue registrado exitosamente.',
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
