<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\EpidemiologicalProgram;

use App\Application\EpidemiologicalProgramUseCase\EpidemiologicalProgramUpdateUseCase;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramId;
use App\Http\Requests\EpidemiologicalProgram\EpidemiologicalProgramUpdateRequest;
use App\Http\Resources\EpidemiologicalProgram\EpidemiologicalProgramResource;
use App\Infrastructure\Repositories\EpidemiologicalProgramRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class EpidemiologicalProgramUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $epidemiologicalProgramRepository;

    public function __construct(EpidemiologicalProgramRepository $epidemiologicalProgramRepository)
    {
        $this->epidemiologicalProgramRepository = $epidemiologicalProgramRepository;
    }

    /**
     * @OA\Put(
     *     path="/EpidemiologicalPrograms/Update/{epidemiologicalProgramId}",
     *     summary="Actualizar programa de vigilancia epidemiológica",
     *     description="Permite actualizar un programa de vigilancia epidemiológica existente (item_id = 34) con su abreviatura.",
     *     tags={"EpidemiologicalPrograms"},
     *     @OA\Parameter(
     *         name="epidemiologicalProgramId",
     *         in="path",
     *         required=true,
     *         description="ID del programa de vigilancia epidemiológica",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description","settings"},
     *             @OA\Property(property="item_id", type="integer", example=34, description="Categorización (debe ser 34)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Sociedad Limitada"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Entidad societaria tipo LTDA."),
     *             @OA\Property(
     *                 property="settings",
     *                 type="object",
     *                 @OA\Property(property="abreviature", type="string", example="LTDA", description="Solo mayúsculas y puntos, p. ej. S.A.S., LTDA, CC.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El programa de vigilancia epidemiológica fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="epidemiologicalProgram", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="item_id", type="integer", example=34),
     *                     @OA\Property(property="name", type="string", example="Sociedad Limitada"),
     *                     @OA\Property(property="description", type="string", example="Entidad societaria tipo LTDA."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 09:30:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El programa de vigilancia epidemiológica fue actualizado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El programa de vigilancia epidemiológica no existe.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El programa de vigilancia epidemiológica no existe."),
     *             @OA\Property(property="error", type="string", example="No se encontró el programa de vigilancia epidemiológica con ID proporcionado."),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(property="attributes", type="object",
     *                 @OA\Property(property="item_id", type="string", example="Categorización"),
     *                 @OA\Property(property="name", type="string", example="Nombre"),
     *                 @OA\Property(property="description", type="string", example="Descripción"),
     *                 @OA\Property(property="settings", type="string", example="Configuración"),
     *                 @OA\Property(property="settings.abreviature", type="string", example="Abreviatura")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres.")),
     *                 @OA\Property(property="settings", type="array", @OA\Items(type="string", example="Debe ser una lista de datos.")),
     *                 @OA\Property(property="settings.abreviature", type="array", @OA\Items(type="string", example="Formato de abreviatura inválido. Ejemplos permitidos: S.A.S., LTDA, CC."))
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el programa de vigilancia epidemiológica.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar el programa de vigilancia epidemiológica: Error en la base de datos."),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function __invoke(EpidemiologicalProgramUpdateRequest $request, int $epidemiologicalProgramId)
    {
        try {
            $epidemiologicalProgramId = new EpidemiologicalProgramId($epidemiologicalProgramId ?? null);
            $epidemiologicalProgramUpdateUseCase = new EpidemiologicalProgramUpdateUseCase($this->epidemiologicalProgramRepository);
            $epidemiologicalProgram = new EpidemiologicalProgramResource($epidemiologicalProgramUpdateUseCase->__invoke($request, $epidemiologicalProgramId));
            return $this->successResponse(
                $epidemiologicalProgram,
                'El programa de vigilancia epidemiológica fue actualizado exitosamente.',
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
