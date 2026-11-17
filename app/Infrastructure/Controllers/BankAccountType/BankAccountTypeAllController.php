<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\BankAccountType;

use App\Application\BankAccountTypeUseCase\BankAccountTypeAllUseCase;
use App\Http\Requests\BankAccountType\BankAccountTypeAllRequest;
use App\Http\Resources\BankAccountType\BankAccountTypeCollection;
use App\Infrastructure\Repositories\BankAccountTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BankAccountTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $bankAccountTypeRepository;

    public function __construct(BankAccountTypeRepository $bankAccountTypeRepository)
    {
        $this->bankAccountTypeRepository = $bankAccountTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/BankAccountTypes/All",
     *     summary="Listar tipos de cuenta bancaria",
     *     description="Obtiene un listado paginado de tipos de cuenta bancaria (incluye eliminados lógicamente).",
     *     tags={"BankAccountTypes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda por nombre o descripción",
     *         @OA\Schema(type="string", maxLength=100, example="Ahorros")
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
     *         description="Los tipos de cuenta bancaria consultados exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="bankAccountTypes", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="item", type="object",
     *                             @OA\Property(property="id", type="integer", example=15),
     *                             @OA\Property(property="name", type="string", example="Tipos de Cuenta Bancaria")
     *                         ),
     *                         @OA\Property(property="name", type="string", example="Cuenta de Ahorros"),
     *                         @OA\Property(property="description", type="string", example="Cuenta para depósitos y retiros frecuentes."),
     *                         @OA\Property(property="settings", type="object", example={"currency":"COP","allow_checks":false}),
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
     *             @OA\Property(property="message", type="string", example="Los tipos de cuenta bancaria consultados exitosamente."),
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
     *         description="Error inesperado al obtener los tipos de cuenta bancaria.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al obtener los tipos de cuenta bancaria: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(BankAccountTypeAllRequest $request)
    {
        try {
            $bankAccountTypeAllUseCase = new BankAccountTypeAllUseCase($this->bankAccountTypeRepository);
            $bankAccountTypes = new BankAccountTypeCollection($bankAccountTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $bankAccountTypes,
                'Los tipos de cuenta bancaria consultados exitosamente.',
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
