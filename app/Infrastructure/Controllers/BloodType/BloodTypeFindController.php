<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\BloodType;

use App\Application\BloodTypeUseCase\BloodTypeFindUseCase;
use App\Domain\BloodType\Values\BloodTypeId;
use App\Http\Resources\BloodType\BloodTypeResource;
use App\Infrastructure\Repositories\BloodTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BloodTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $bloodTypeRepository;

    public function __construct(BloodTypeRepository $bloodTypeRepository)
    {
        $this->bloodTypeRepository = $bloodTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/BloodTypes/Find/{bloodTypeId}",
     *     summary="Encontrar grupo sanguíneo por ID",
     *     description="Retorna el detalle de un grupo sanguíneo específico (incluye eliminados lógicamente).",
     *     tags={"BloodTypes"},
     *     @OA\Parameter(
     *         name="bloodTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del grupo sanguíneo a consultar",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El grupo sanguineo fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="bloodType", type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="item_id", type="integer", example=11),
     *                     @OA\Property(property="name", type="string", example="A-"),
     *                     @OA\Property(property="description", type="string", example="Grupo sanguíneo A negativo."),
     *                     @OA\Property(property="settings", type="object", example={"rh":"negativo"}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El grupo sanguineo fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Grupo sanguíneo no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El tipo de sangre no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el grupo sanguíneo.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el tipo de sangre: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $bloodTypeId)
    {
        try {
            $bloodTypeId = new BloodTypeId($bloodTypeId ?? null);
            $bloodTypeFindUseCase = new BloodTypeFindUseCase($this->bloodTypeRepository);
            $bloodType = new BloodTypeResource($bloodTypeFindUseCase->__invoke($bloodTypeId));
            return $this->successResponse(
                $bloodType,
                'El grupo sanguineo fue encontrada exitosamente.',
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
