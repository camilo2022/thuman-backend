<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile;

use App\Application\ProfileUseCase\ProfileStoreUseCase;
use App\Http\Requests\Profile\ProfileStoreRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Infrastructure\Repositories\ProfileRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class ProfileStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
    
    /**
     * @OA\Post(
     *     path="/Profiles/Store",
     *     summary="Registrar un perfil",
     *     tags={"Profiles"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear el perfil",
     *         @OA\JsonContent(
     *             required={"person_id","professional_title","professional_description"},
     *             @OA\Property(property="person_id", type="integer", format="int64", example=345, description="Debe existir en people.id y ser único en profiles.person_id"),
     *             @OA\Property(property="professional_title", type="string", example="Ingeniero de Sistemas"),
     *             @OA\Property(property="professional_description", type="string", maxLength=500, example="Desarrollador full-stack con experiencia en Laravel, Vue y Docker.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El perfil fue registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="profile", type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="person_id", type="integer", example=345),
     *                     @OA\Property(property="professional_title", type="string", example="Ingeniero de Sistemas"),
     *                     @OA\Property(property="professional_description", type="string", example="Desarrollador full-stack con experiencia en Laravel, Vue y Docker."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 08:15:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 08:15:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El perfil fue registrado exitosamente."),
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
     *                     @OA\Items(type="string", example="No existe en la base de datos.")
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
     *         response=500,
     *         description="Error al guardar el perfil.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al guardar el perfil: Error en la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(ProfileStoreRequest $request)
    {
        Gate::authorize('storeProfile', $request->input('person_id'));

        try {
            $profileStoreUseCase = new ProfileStoreUseCase($this->profileRepository);
            $profile = new ProfileResource($profileStoreUseCase->__invoke($request));
            return $this->successResponse(
                $profile,
                'El perfil fue registrado exitosamente.',
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
