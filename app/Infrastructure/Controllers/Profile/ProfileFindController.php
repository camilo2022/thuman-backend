<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile;

use App\Application\ProfileUseCase\ProfileFindUseCase;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\ProfileFindRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Infrastructure\Repositories\ProfileRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfileFindController
{
    use ApiResponser;
    use ApiMessage;
    private $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
    
    /**
     * @OA\Get(
     *     path="/Profiles/Find/{profileId}",
     *     summary="Buscar un perfil",
     *     tags={"Profiles"},
     *     @OA\Parameter(
     *         name="profileId",
     *         in="path",
     *         description="ID del perfil. Si se omite, se puede buscar por person_id (query).",
     *         required=false,
     *         @OA\Schema(type="integer", format="int64", example=12)
     *     ),
     *     @OA\Parameter(
     *         name="person_id",
     *         in="query",
     *         description="ID de la persona asociada al perfil. Requerido si no se envía profileId.",
     *         required=false,
     *         @OA\Schema(type="integer", format="int64", example=345)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil consultado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="profile", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="person_id", type="integer", example=345),
     *                     @OA\Property(property="professional_title", type="string", example="Ingeniero de Sistemas"),
     *                     @OA\Property(property="professional_description", type="string", example="Desarrollador full-stack con 5 años de experiencia."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 09:30:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El perfil fue consultado exitosamente."),
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
     *                 @OA\Property(property="person_id", type="string", example="Persona")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="person_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
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
     *         description="Error inesperado al buscar el perfil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar el perfil: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ProfileFindRequest $request, int|null $profileId = null)
    {        
        Gate::authorize('findProfile', [$profileId, (object) [
            'person_id' => $request->input('person_id')
        ]]);

        try {
            $profileId = new ProfileId($profileId);
            $profileFindUseCase = new ProfileFindUseCase($this->profileRepository);
            $profile = new ProfileResource($profileFindUseCase->__invoke($request, $profileId));
            return $this->successResponse(
                $profile,
                'El perfil fue encontrado exitosamente.',
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
