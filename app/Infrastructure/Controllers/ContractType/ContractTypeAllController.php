<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\ContractType;

use App\Application\ContractTypeUseCase\ContractTypeAllUseCase;
use App\Http\Requests\ContractType\ContractTypeAllRequest;
use App\Http\Resources\ContractType\ContractTypeCollection;
use App\Infrastructure\Repositories\ContractTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ContractTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $contractTypeRepository;

    public function __construct(ContractTypeRepository $contractTypeRepository)
    {
        $this->contractTypeRepository = $contractTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/ContractTypes/All",
     *     summary="Listar tipos de contratos",
     *     description="Obtiene un listado paginado de tipos de contratos (incluye eliminados lógicamente).",
     *     tags={"ContractTypes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="Indefinido")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Los tipos de contratos consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="contractTypes", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=4),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Tipos de Contratos")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Indefinido"),
     *                         @OA\Property(property="description", type="string", example="Contrato de trabajo a término indefinido."),
     *                         @OA\Property(property="settings", type="object", example={"order":1}),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=18),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=2)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Los tipos de contratos consultados exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="search", type="array", @OA\Items(type="string", example="Debe ser una cadena de caracteres.")),
     *                 @OA\Property(property="perPage", type="array", @OA\Items(type="string", example="Debe ser un valor numérico."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener los tipos de contratos.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los tipos de contratos: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(ContractTypeAllRequest $request)
    {
        try {
            $contractTypeAllUseCase = new ContractTypeAllUseCase($this->contractTypeRepository);
            $contractTypes = new ContractTypeCollection($contractTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $contractTypes,
                'Los tipos de contratos consultados exitosamente.',
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
