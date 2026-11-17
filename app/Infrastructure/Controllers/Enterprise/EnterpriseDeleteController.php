<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise;

use App\Application\EnterpriseUseCase\EnterpriseDeleteUseCase;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Resources\Enterprise\EnterpriseResource;
use App\Infrastructure\Repositories\EnterpriseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EnterpriseDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $enterpriseRepository;

    public function __construct(EnterpriseRepository $enterpriseRepository)
    {
        $this->enterpriseRepository = $enterpriseRepository;
    }

    /**
     * @OA\Delete(
     *     path="/Enterprises/Delete/{enterpriseId}",
     *     summary="Eliminar una empresa",
     *     tags={"Enterprises"},
     *     @OA\Parameter(
     *         name="enterpriseId",
     *         in="path",
     *         required=true,
     *         description="ID de la empresa a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La empresa fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="enterprise", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Acme Corp"),
     *                     @OA\Property(property="description", type="string", example="Empresa eliminada del sistema."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 12:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-08-18 13:00:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La empresa fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
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
     *         response=500,
     *         description="Error inesperado al eliminar la empresa.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al eliminar la empresa: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $enterpriseId)
    {
        Gate::authorize('deleteEnterprise');
        
        try {
            $enterpriseId = new EnterpriseId($enterpriseId);
            $enterpriseDeleteUseCase = new EnterpriseDeleteUseCase($this->enterpriseRepository);
            $enterprise = new EnterpriseResource($enterpriseDeleteUseCase->__invoke($enterpriseId));
            return $this->successResponse(
                $enterprise,
                'La empresa fue inactivada exitosamente.',
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
