<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\LanguageProficiencie;

use App\Application\ProfileUseCase\LanguageProficiencieUseCase\LanguageProficiencieAllUseCase;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\LanguageProficiencie\LanguageProficiencieAllRequest;
use App\Http\Resources\Profile\LanguageProficiencie\LanguageProficiencieCollection;
use App\Infrastructure\Repositories\LanguageProficiencieRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class LanguageProficiencieAllController
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
     *     path="/Profiles/LanguageProficiencies/All/{profileId}",
     *     summary="Listar idiomas de un perfil",
     *     tags={"Profiles - LanguageProficiencies"},
     *     @OA\Parameter(
     *         name="profileId",
     *         in="path",
     *         description="ID del perfil al que pertenecen los idiomas",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=12)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda para filtrar idiomas",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=100, example="Inglés")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Número de registros por página",
     *         required=false,
     *         @OA\Schema(type="integer", format="int32", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de idiomas obtenido exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="languageProficiencies", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=501),
     *                         @OA\Property(property="profile", type="object", example={"id":12,"person_id":345}),
     *                         @OA\Property(property="language", type="object", example={"id":1,"name":"Inglés"}),
     *                         @OA\Property(property="language_level", type="object", example={"id":3,"name":"Avanzado"}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=25),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=3)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los idiomas fueron consultados exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
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
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="search",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser una cadena de caracteres.")
     *                 ),
     *                 @OA\Property(
     *                     property="perPage",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener los idiomas.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener los idiomas: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(LanguageProficiencieAllRequest $request, int $profileId)
    {
        Gate::authorize('allLanguageProficiencie', $profileId);
        
        try {
            $profileId = new ProfileId($profileId ?? null);
            $languageProficiencieAllUseCase = new LanguageProficiencieAllUseCase($this->languageProficiencieRepository);
            $languageProficiencies = new LanguageProficiencieCollection($languageProficiencieAllUseCase->__invoke($request, $profileId));
            return $this->successResponse(
                $languageProficiencies,
                'Los idiomas fueron consultados exitosamente.',
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
