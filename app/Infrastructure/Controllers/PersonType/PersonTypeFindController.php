<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PersonType;

use App\Application\PersonTypeUseCase\PersonTypeFindUseCase;
use App\Domain\PersonType\Values\PersonTypeId;
use App\Http\Resources\PersonType\PersonTypeResource;
use App\Infrastructure\Repositories\PersonTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PersonTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $personTypeRepository;

    public function __construct(PersonTypeRepository $personTypeRepository)
    {
        $this->personTypeRepository = $personTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/PersonTypes/Find/{personTypeId}",
     *     summary="Encontrar tipo de persona por ID",
     *     description="Retorna el detalle de un tipo de persona específico (incluye eliminados lógicamente).",
     *     tags={"PersonTypes"},
     *     @OA\Parameter(
     *         name="personTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de persona a consultar",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo de persona fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="personType", type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="item_id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Empleado"),
     *                     @OA\Property(property="description", type="string", example="Persona contratada por la empresa."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de persona fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de persona no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El tipo de persona no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el tipo de persona.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el tipo de persona: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $personTypeId)
    {
        try {
            $personTypeId = new PersonTypeId($personTypeId ?? null);
            $personTypeFindUseCase = new PersonTypeFindUseCase($this->personTypeRepository);
            $personType = new PersonTypeResource($personTypeFindUseCase->__invoke($personTypeId));
            return $this->successResponse(
                $personType,
                'El tipo de persona fue encontrada exitosamente.',
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
