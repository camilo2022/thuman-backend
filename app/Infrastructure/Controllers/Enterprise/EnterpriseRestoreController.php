<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise;

use App\Application\EnterpriseUseCase\EnterpriseRestoreUseCase;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Resources\Enterprise\EnterpriseResource;
use App\Infrastructure\Repositories\EnterpriseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EnterpriseRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $enterpriseRepository;

    public function __construct(EnterpriseRepository $enterpriseRepository)
    {
        $this->enterpriseRepository = $enterpriseRepository;
    }

    /**
     * @OA\Patch(
     *     path="/Enterprises/Restore/{enterpriseId}",
     *     summary="Restaurar una empresa eliminada",
     *     tags={"Enterprises"},
     *     @OA\Parameter(
     *         name="enterpriseId",
     *         in="path",
     *         required=true,
     *         description="ID de la empresa a restaurar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="La empresa fue restaurada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="enterprise", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Acme Corp"),
     *                     @OA\Property(property="description", type="string", example="Empresa restaurada correctamente."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 12:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La empresa fue restaurada exitosamente."),
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
     *         description="Error inesperado al restaurar la empresa.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al restaurar la empresa: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $enterpriseId)
    {
        Gate::authorize('restoreEnterprise');
        
        try {
            $enterpriseId = new EnterpriseId($enterpriseId);
            $enterpriseRestoreUseCase = new EnterpriseRestoreUseCase($this->enterpriseRepository);
            $enterprise = new EnterpriseResource($enterpriseRestoreUseCase->__invoke($enterpriseId));
            return $this->successResponse(
                $enterprise,
                'La empresa fue activada exitosamente.',
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
