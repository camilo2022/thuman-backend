<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Location\Departament;

use App\Application\LocationUseCase\DepartamentUseCase\DepartamentFindUseCase;
use App\Domain\Location\Departament\Values\DepartamentId;
use App\Http\Requests\Location\Departament\DepartamentFindRequest;
use App\Http\Resources\Location\Departament\DepartamentResource;
use App\Infrastructure\Repositories\DepartamentRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DepartamentFindController
{
    use ApiResponser;
    use ApiMessage;
    private $departamentRepository;

    public function __construct(DepartamentRepository $departamentRepository)
    {
        $this->departamentRepository = $departamentRepository;
    }

    /**
     * @OA\Get(
     *   path="/Locations/Departaments/Find/{departamentId}",
     *   summary="Encontrar un departamento",
     *   description="Busca un departamento por ID. Si no se envía el ID, se puede enviar 'name' por query para búsqueda alternativa.",
     *   tags={"Locations - Departaments"},
     *   @OA\Parameter(
     *     name="departamentId", in="path", required=false,
     *     description="ID del departamento (opcional si se usa 'name' por query)",
     *     @OA\Schema(type="integer", example=5)
     *   ),
     *   @OA\Parameter(
     *     name="name", in="query", required=false,
     *     description="Nombre del departamento (requerido cuando no se envía 'departamentId')",
     *     @OA\Schema(type="string", example="Antioquia")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="El departamento fue encontrado exitosamente.",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="departament", type="object",
     *           @OA\Property(property="id", type="integer", example=5),
     *           @OA\Property(property="country_id", type="integer", example=48),
     *           @OA\Property(property="name", type="string", example="Antioquia"),
     *           @OA\Property(property="state_code", type="string", example="ANT"),
     *           @OA\Property(property="type", type="string", example="Department"),
     *           @OA\Property(property="latitude", type="string", example="6.5569"),
     *           @OA\Property(property="longitude", type="string", example="-75.3580"),
     *           @OA\Property(property="created_at", type="string", example="2025-01-01 12:00:00"),
     *           @OA\Property(property="updated_at", type="string", example="2025-02-01 12:00:00")
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Error de validación.",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Error de validación."),
     *       @OA\Property(property="attributes", type="object", example={"name":"Departamento"}),
     *       @OA\Property(property="errors", type="object",
     *         @OA\Property(property="name", type="array", @OA\Items(type="string", example="No existe en la base de datos."))
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="El departamento no existe.",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="El departamento no existe.")
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Error inesperado.",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ocurrió un error inesperado."),
     *       @OA\Property(property="error", type="string", example="Detalle del error")
     *     )
     *   )
     * )
     */
    public function __invoke(DepartamentFindRequest $request, int|null $departamentId = null)
    {
        try {
            $departamentId = new DepartamentId($departamentId);
            $departamentFindUseCase = new DepartamentFindUseCase($this->departamentRepository);
            $departament = new DepartamentResource($departamentFindUseCase->__invoke($request, $departamentId));
            return $this->successResponse(
                $departament,
                'El departamento fue encontrado exitosamente.',
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
