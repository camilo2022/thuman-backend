<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalSkill;

use App\Application\ProfileUseCase\ProfessionalSkillUseCase\ProfessionalSkillAllUseCase;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\ProfessionalSkill\ProfessionalSkillAllRequest;
use App\Http\Resources\Profile\ProfessionalSkill\ProfessionalSkillCollection;
use App\Infrastructure\Repositories\ProfessionalSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalSkillAllController
{
    use ApiResponser;
    use ApiMessage;
    private $professionalSkillRepository;

    public function __construct(ProfessionalSkillRepository $professionalSkillRepository)
    {
        $this->professionalSkillRepository = $professionalSkillRepository;
    }

    /**
     * @OA\Get(
     *     path="/Profiles/ProfessionalSkills/All/{profileId}",
     *     summary="Listar habilidades profesionales de un perfil",
     *     tags={"Profiles - ProfessionalSkills"},
     *     @OA\Parameter(
     *         name="profileId",
     *         in="path",
     *         description="ID del perfil al que pertenecen las habilidades",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=12)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda para filtrar habilidades",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=100, example="JavaScript")
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
     *         description="Listado de habilidades obtenido exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="professionalSkills", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=701),
     *                         @OA\Property(property="profile", type="object", example={"id":12,"person_id":345}),
     *                         @OA\Property(property="skill_type", type="object", example={"id":1,"name":"Técnica"}),
     *                         @OA\Property(property="skill", type="object", example={"id":10,"name":"JavaScript"}),
     *                         @OA\Property(property="percentage", type="integer", example=85),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=18),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=2)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las habilidades fueron consultadas exitosamente."),
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
     *         description="Error inesperado al obtener las habilidades.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener las habilidades: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ProfessionalSkillAllRequest $request, int $profileId)
    {
        Gate::authorize('allProfessionalSkill', $profileId);
        
        try {
            $profileId = new ProfileId($profileId ?? null);
            $professionalSkillAllUseCase = new ProfessionalSkillAllUseCase($this->professionalSkillRepository);
            $professionalSkills = new ProfessionalSkillCollection($professionalSkillAllUseCase->__invoke($request, $profileId));
            return $this->successResponse(
                $professionalSkills,
                'Las habilidades fueron consultados exitosamente.',
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
