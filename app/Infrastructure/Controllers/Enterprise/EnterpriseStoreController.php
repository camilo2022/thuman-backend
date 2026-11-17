<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise;

use App\Application\EnterpriseUseCase\EnterpriseStoreUseCase;
use App\Http\Requests\Enterprise\EnterpriseStoreRequest;
use App\Http\Resources\Enterprise\EnterpriseResource;
use App\Infrastructure\Repositories\EnterpriseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

final class EnterpriseStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $enterpriseRepository;

    public function __construct(EnterpriseRepository $enterpriseRepository)
    {
        $this->enterpriseRepository = $enterpriseRepository;
    }

    /**
     * @OA\Post(
     *     path="/Enterprises/Store",
     *     summary="Registrar una nueva empresa",
     *     tags={"Enterprises"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=80, example="Acme Corp"),
     *             @OA\Property(property="description", type="string", maxLength=500, example="Empresa dedicada al desarrollo de software.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La empresa fue registrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="enterprise", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Acme Corp"),
     *                     @OA\Property(property="description", type="string", example="Empresa dedicada al desarrollo de software."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="La empresa fue registrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
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
     *         description="Error inesperado al guardar la empresa.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al guardar la empresa: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(EnterpriseStoreRequest $request)
    {
        Gate::authorize('storeEnterprise');
        
        try {
            $enterpriseStoreUseCase = new EnterpriseStoreUseCase($this->enterpriseRepository);
            $enterprise = new EnterpriseResource($enterpriseStoreUseCase->__invoke($request));
            return $this->successResponse(
                $enterprise,
                'La empresa fue registrada exitosamente.',
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
