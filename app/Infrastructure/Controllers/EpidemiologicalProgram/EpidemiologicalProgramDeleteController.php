<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\EpidemiologicalProgram;

use App\Application\EpidemiologicalProgramUseCase\EpidemiologicalProgramDeleteUseCase;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramId;
use App\Http\Resources\EpidemiologicalProgram\EpidemiologicalProgramResource;
use App\Infrastructure\Repositories\EpidemiologicalProgramRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EpidemiologicalProgramDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $epidemiologicalProgramRepository;

    public function __construct(EpidemiologicalProgramRepository $epidemiologicalProgramRepository)
    {
        $this->epidemiologicalProgramRepository = $epidemiologicalProgramRepository;
    }

    /**
     * @OA\Delete(
     *     path="/EpidemiologicalPrograms/Delete/{epidemiologicalProgramId}",
     *     summary="Eliminar programa de vigilancia epidemiológica",
     *     description="Elimina lógicamente un programa de vigilancia epidemiológica por su ID (item_id = 34).",
     *     tags={"EpidemiologicalPrograms"},
     *     @OA\Parameter(
     *         name="epidemiologicalProgramId",
     *         in="path",
     *         required=true,
     *         description="ID del programa de vigilancia epidemiológica a eliminar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="El programa de vigilancia epidemiológica fue eliminada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="epidemiologicalProgram", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=34),
     *                     @OA\Property(property="name", type="string", example="Sociedad Limitada"),
     *                     @OA\Property(property="description", type="string", example="Entidad societaria tipo LTDA."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-08-18 10:20:00")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El programa de vigilancia epidemiológica fue eliminada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de entidad no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El programa de vigilancia epidemiológica no existe."),
     *             @OA\Property(property="error", type="string", example="El programa de vigilancia epidemiológica no existe: No se encontró el ID especificado."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al eliminar el programa de vigilancia epidemiológica.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al eliminar el programa de vigilancia epidemiológica: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(int $epidemiologicalProgramId)
    {
        try {
            $epidemiologicalProgramId = new EpidemiologicalProgramId($epidemiologicalProgramId);
            $epidemiologicalProgramDeleteUseCase = new EpidemiologicalProgramDeleteUseCase($this->epidemiologicalProgramRepository);
            $epidemiologicalProgram = new EpidemiologicalProgramResource($epidemiologicalProgramDeleteUseCase->__invoke($epidemiologicalProgramId));
            return $this->successResponse(
                $epidemiologicalProgram,
                'El programa de vigilancia epidemiológica fue inactivada exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
            );
        }  catch (Exception $e) {
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
