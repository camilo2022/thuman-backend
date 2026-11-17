<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\EpidemiologicalProgram;

use App\Application\EpidemiologicalProgramUseCase\EpidemiologicalProgramFindUseCase;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramId;
use App\Http\Resources\EpidemiologicalProgram\EpidemiologicalProgramResource;
use App\Infrastructure\Repositories\EpidemiologicalProgramRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class EpidemiologicalProgramFindController
{
    use ApiResponser;
    use ApiMessage;
    private $epidemiologicalProgramRepository;

    public function __construct(EpidemiologicalProgramRepository $epidemiologicalProgramRepository)
    {
        $this->epidemiologicalProgramRepository = $epidemiologicalProgramRepository;
    }

    /**
     * @OA\Get(
     *     path="/EpidemiologicalPrograms/Find/{epidemiologicalProgramId}",
     *     summary="Consultar programa de vigilancia epidemiológica por ID",
     *     description="Obtiene el detalle de un programa de vigilancia epidemiológica (item_id = 34) por su identificador único.",
     *     tags={"EpidemiologicalPrograms"},
     *     @OA\Parameter(
     *         name="epidemiologicalProgramId",
     *         in="path",
     *         required=true,
     *         description="ID del programa de vigilancia epidemiológica",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El programa de vigilancia epidemiológica fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="epidemiologicalProgram", type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="item_id", type="integer", example=34),
     *                     @OA\Property(property="name", type="string", example="Cooperativa"),
     *                     @OA\Property(property="description", type="string", example="Entidad de tipo cooperativa"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El programa de vigilancia epidemiológica fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El programa de vigilancia epidemiológica no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El programa de vigilancia epidemiológica no existe."),
     *             @OA\Property(property="error", type="string", example="No se encontró el programa de vigilancia epidemiológica con el ID especificado."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el programa de vigilancia epidemiológica.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el programa de vigilancia epidemiológica: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $epidemiologicalProgramId)
    {
        try {
            $epidemiologicalProgramId = new EpidemiologicalProgramId($epidemiologicalProgramId ?? null);
            $epidemiologicalProgramFindUseCase = new EpidemiologicalProgramFindUseCase($this->epidemiologicalProgramRepository);
            $epidemiologicalProgram = new EpidemiologicalProgramResource($epidemiologicalProgramFindUseCase->__invoke($epidemiologicalProgramId));
            return $this->successResponse(
                $epidemiologicalProgram,
                'El programa de vigilancia epidemiológica fue encontrada exitosamente.',
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
