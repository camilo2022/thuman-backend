<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Gender;

use App\Application\GenderUseCase\GenderFindUseCase;
use App\Domain\Gender\Values\GenderId;
use App\Http\Resources\Gender\GenderResource;
use App\Infrastructure\Repositories\GenderRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class GenderFindController
{
    use ApiResponser;
    use ApiMessage;
    private $genderRepository;

    public function __construct(GenderRepository $genderRepository)
    {
        $this->genderRepository = $genderRepository;
    }

    /**
     * @OA\Get(
     *     path="/Genders/Find/{genderId}",
     *     summary="Encontrar género por ID",
     *     description="Retorna el detalle de un género específico (incluye eliminados lógicamente).",
     *     tags={"Genders"},
     *     @OA\Parameter(
     *         name="genderId",
     *         in="path",
     *         required=true,
     *         description="ID del género a consultar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El genero fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="gender", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="item_id", type="integer", example=10),
     *                     @OA\Property(property="name", type="string", example="Masculino"),
     *                     @OA\Property(property="description", type="string", example="Género masculino."),
     *                     @OA\Property(property="settings", type="object", example={"order":1}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-10 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-15 12:45:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El genero fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Género no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El genero no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el genero.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el genero: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $genderId)
    {
        try {
            $genderId = new GenderId($genderId ?? null);
            $genderFindUseCase = new GenderFindUseCase($this->genderRepository);
            $gender = new GenderResource($genderFindUseCase->__invoke($genderId));
            return $this->successResponse(
                $gender,
                'El genero fue encontrada exitosamente.',
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
