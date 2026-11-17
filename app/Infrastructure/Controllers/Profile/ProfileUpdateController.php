<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile;

use App\Application\ProfileUseCase\ProfileUpdateUseCase;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Infrastructure\Repositories\ProfileRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfileUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * @OA\Put(
     *     path="/Profiles/Update/{profileId}",
     *     summary="Actualizar un perfil",
     *     tags={"Profiles"},
     *     @OA\Parameter(
     *         name="profileId",
     *         in="path",
     *         description="ID del perfil a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=12)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el perfil",
     *         @OA\JsonContent(
     *             required={"person_id","professional_title","professional_description"},
     *             @OA\Property(property="person_id", type="integer", format="int64", example=345, description="Debe existir en people.id y ser único en profiles.person_id (excepto el mismo registro)"),
     *             @OA\Property(property="professional_title", type="string", example="Ingeniero de Sistemas"),
     *             @OA\Property(property="professional_description", type="string", maxLength=500, example="Especialista en desarrollo backend con experiencia en Laravel y PostgreSQL.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El perfil fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="profile", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="person_id", type="integer", example=345),
     *                     @OA\Property(property="professional_title", type="string", example="Ingeniero de Sistemas"),
     *                     @OA\Property(property="professional_description", type="string", example="Especialista en desarrollo backend con experiencia en Laravel y PostgreSQL."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-01 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 09:15:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El perfil fue actualizado exitosamente."),
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
     *                 @OA\Property(property="person_id", type="string", example="Persona"),
     *                 @OA\Property(property="professional_title", type="string", example="Titulo profesional"),
     *                 @OA\Property(property="professional_description", type="string", example="Descripcion profesional")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="person_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="Ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(
     *                     property="professional_title",
     *                     type="array",
     *                     @OA\Items(type="string", example="Es requerido.")
     *                 ),
     *                 @OA\Property(
     *                     property="professional_description",
     *                     type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 500 caracteres.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el perfil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El perfil no existe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el perfil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar el perfil: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ProfileUpdateRequest $request, int $profileId)
    {
        Gate::authorize('updateProfile', $profileId);
        
        try {
            $profileId = new ProfileId($profileId ?? null);
            $profileUpdateUseCase = new ProfileUpdateUseCase($this->profileRepository);
            $profile = new ProfileResource($profileUpdateUseCase->__invoke($request, $profileId));
            return $this->successResponse(
                $profile,
                'El perfil fue actualizado exitosamente.',
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
