<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Headquarter;

use App\Application\EnterpriseUseCase\EntityUseCase\HeadquarterUseCase\HeadquarterUpdateUseCase;
use App\Domain\Enterprise\Entity\Headquarter\Values\HeadquarterId;
use App\Http\Requests\Enterprise\Entity\Headquarter\HeadquarterUpdateRequest;
use App\Http\Resources\Enterprise\Entity\Headquarter\HeadquarterResource;
use App\Infrastructure\Repositories\HeadquarterRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class HeadquarterUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $headquarterRepository;

    public function __construct(HeadquarterRepository $headquarterRepository)
    {
        $this->headquarterRepository = $headquarterRepository;
    }

    /**
     * @OA\Put(
     *     path="/Enterprises/Entities/Headquarters/Update/{headquarterId}",
     *     summary="Actualizar sede de una entidad",
     *     description="Actualiza la información de una sede de la entidad.",
     *     tags={"Enterprises - Entities - Headquarters"},
     *     @OA\Parameter(
     *         name="headquarterId",
     *         in="path",
     *         required=true,
     *         description="ID de la sede a actualizar",
     *         @OA\Schema(type="integer", example=9)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"enterprise_id","name","city_id","address"},
     *             @OA\Property(property="enterprise_id", type="integer", description="ID de la empresa", example=3),
     *             @OA\Property(property="name", type="string", description="Nombre de la sede", example="Sede Actualizada"),
     *             @OA\Property(property="city_id", type="integer", description="ID de la ciudad", example=11001),
     *             @OA\Property(property="address", type="string", description="Dirección física de la sede", example="Carrera 45 #100-20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La sede fue actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="headquarter", type="object",
     *                     @OA\Property(property="id", type="integer", example=9),
     *                     @OA\Property(property="entity_id", type="integer", example=15),
     *                     @OA\Property(property="name", type="string", example="Sede Actualizada"),
     *                     @OA\Property(property="city_id", type="integer", example=11001),
     *                     @OA\Property(property="address", type="string", example="Carrera 45 #100-20"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:30:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La sede fue actualizada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La sede de la entidad no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="La sede de la entidad no existe."),
     *                 @OA\Property(property="error", type="string", example="No se encontró el ID especificado.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="enterprise_id", type="string", example="Empresa"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="city_id", type="string", example="Ciudad"),
     *                 @OA\Property(property="address", type="string", example="Dirección")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="Ya ha sido tomado.")
     *                 ),
     *                 @OA\Property(property="address", type="array",
     *                     @OA\Items(type="string", example="No debe exceder los 110 caracteres.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la sede de la entidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="message", type="string", example="Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde."),
     *                 @OA\Property(property="error", type="string", example="Error al actualizar la sede de la entidad: Error en la base de datos.")
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(HeadquarterUpdateRequest $request, int $headquarterId)
    {
        Gate::authorize('updateHeadquarter');
        
        try {
            $headquarterId = new HeadquarterId($headquarterId ?? null);
            $headquarterUpdateUseCase = new HeadquarterUpdateUseCase($this->headquarterRepository);
            $headquarter = new HeadquarterResource($headquarterUpdateUseCase->__invoke($request, $headquarterId));
            return $this->successResponse(
                $headquarter,
                'La sede fue actualizada exitosamente.',
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
