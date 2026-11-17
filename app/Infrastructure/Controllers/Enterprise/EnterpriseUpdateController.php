<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise;

use App\Application\EnterpriseUseCase\EnterpriseUpdateUseCase;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Requests\Enterprise\EnterpriseUpdateRequest;
use App\Http\Resources\Enterprise\EnterpriseResource;
use App\Infrastructure\Repositories\EnterpriseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EnterpriseUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $enterpriseRepository;

    public function __construct(EnterpriseRepository $enterpriseRepository)
    {
        $this->enterpriseRepository = $enterpriseRepository;
    }

    /**
     * @OA\Put(
     *     path="/Enterprises/Update/{enterpriseId}",
     *     summary="Actualizar una empresa existente",
     *     tags={"Enterprises"},
     *     @OA\Parameter(
     *         name="enterpriseId",
     *         in="path",
     *         required=true,
     *         description="ID de la empresa a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=80, example="Acme Corp Actualizada"),
     *             @OA\Property(property="description", type="string", maxLength=500, example="Empresa actualizada dedicada al desarrollo de software.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La empresa fue actualizada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="enterprise", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Acme Corp Actualizada"),
     *                     @OA\Property(property="description", type="string", example="Empresa actualizada dedicada al desarrollo de software."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 12:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La empresa fue actualizada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La empresa no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La empresa no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="El campo Nombre es requerido.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar la empresa.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al actualizar la empresa: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(EnterpriseUpdateRequest $request, int $enterpriseId)
    {
        Gate::authorize('updateEnterprise');
        
        try {
            $enterpriseId = new EnterpriseId($enterpriseId ?? null);
            $enterpriseUpdateUseCase = new EnterpriseUpdateUseCase($this->enterpriseRepository);
            $enterprise = new EnterpriseResource($enterpriseUpdateUseCase->__invoke($request, $enterpriseId));
            return $this->successResponse(
                $enterprise,
                'La empresa fue actualizada exitosamente.',
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
