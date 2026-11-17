<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\ProfessionalSkill;

use App\Application\ProfileUseCase\ProfessionalSkillUseCase\ProfessionalSkillStoreUseCase;
use App\Http\Requests\Profile\ProfessionalSkill\ProfessionalSkillStoreRequest;
use App\Http\Resources\Profile\ProfessionalSkill\ProfessionalSkillResource;
use App\Infrastructure\Repositories\ProfessionalSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfessionalSkillStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $professionalSkillRepository;

    public function __construct(ProfessionalSkillRepository $professionalSkillRepository)
    {
        $this->professionalSkillRepository = $professionalSkillRepository;
    }

    /**
     * @OA\Post(
     *     path="/Profiles/ProfessionalSkills/Store",
     *     summary="Registrar una habilidad profesional",
     *     tags={"Profiles - ProfessionalSkills"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"profile_id","skill_type_id","skill_id","percentage"},
     *             @OA\Property(property="profile_id", type="integer", example=12, description="ID del perfil"),
     *             @OA\Property(property="skill_type_id", type="integer", example=1, description="ID del tipo de habilidad (blanda o dura)"),
     *             @OA\Property(property="skill_id", type="integer", example=32, description="ID de la habilidad"),
     *             @OA\Property(property="percentage", type="integer", example=85, description="Calificación de la habilidad. Para habilidades blandas solo números entre 1 y 5, para habilidades duras solo entre 1 y 100")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La habilidad fue registrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="professionalSkill", type="object",
     *                     @OA\Property(property="id", type="integer", example=701),
     *                     @OA\Property(property="profile_id", type="integer", example=12),
     *                     @OA\Property(property="skill_type_id", type="integer", example=1),
     *                     @OA\Property(property="skill_id", type="integer", example=32),
     *                     @OA\Property(property="percentage", type="integer", example=85),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La habilidad fue registrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación en los parámetros enviados.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="profile_id", type="array",
     *                     @OA\Items(type="string", example="El perfil es requerido.")
     *                 ),
     *                 @OA\Property(property="percentage", type="array",
     *                     @OA\Items(type="string", example="Seleccione una calificación de 1 a 5 estrellas.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al guardar la habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error inesperado al guardar la habilidad."),
     *             @OA\Property(property="error", type="string", example="Error al guardar la habilidad: violación de restricción de base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ProfessionalSkillStoreRequest $request)
    {
        Gate::authorize('storeProfessionalSkill', $request->input('profile_id'));
        
        try {
            $professionalSkillStoreUseCase = new ProfessionalSkillStoreUseCase($this->professionalSkillRepository);
            $professionalSkill = new ProfessionalSkillResource($professionalSkillStoreUseCase->__invoke($request));
            return $this->successResponse(
                $professionalSkill,
                'La habilidad fue registrado exitosamente.',
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
