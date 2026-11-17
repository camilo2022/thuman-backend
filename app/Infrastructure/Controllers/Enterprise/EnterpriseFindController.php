<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise;

use App\Application\EnterpriseUseCase\EnterpriseFindUseCase;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Resources\Enterprise\EnterpriseResource;
use App\Infrastructure\Repositories\EnterpriseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EnterpriseFindController
{
    use ApiResponser;
    use ApiMessage;
    private $enterpriseRepository;

    public function __construct(EnterpriseRepository $enterpriseRepository)
    {
        $this->enterpriseRepository = $enterpriseRepository;
    }

    /**
     * @OA\Get(
     *     path="/Enterprises/Find/{enterpriseId}",
     *     summary="Buscar una empresa por ID",
     *     tags={"Enterprises"},
     *     @OA\Parameter(
     *         name="enterpriseId",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La empresa fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="enterprise", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Acme Corp"),
     *                     @OA\Property(property="description", type="string", example="Empresa dedicada al desarrollo de software."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La empresa fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la empresa.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La empresa no existe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar la empresa.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al buscar la empresa: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $enterpriseId)
    {
        Gate::authorize('findEnterprise');
        
        try {
            $enterpriseId = new EnterpriseId($enterpriseId ?? null);
            $enterpriseFindUseCase = new EnterpriseFindUseCase($this->enterpriseRepository);
            $enterprise = new EnterpriseResource($enterpriseFindUseCase->__invoke($enterpriseId));
            return $this->successResponse(
                $enterprise,
                'La empresa fue encontrada exitosamente.',
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
