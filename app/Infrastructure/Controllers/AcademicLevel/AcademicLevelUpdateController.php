<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AcademicLevel;

use App\Application\AcademicLevelUseCase\AcademicLevelUpdateUseCase;
use App\Domain\AcademicLevel\Values\AcademicLevelId;
use App\Http\Requests\AcademicLevel\AcademicLevelUpdateRequest;
use App\Http\Resources\AcademicLevel\AcademicLevelResource;
use App\Infrastructure\Repositories\AcademicLevelRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AcademicLevelUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $academicLevelRepository;

    public function __construct(AcademicLevelRepository $academicLevelRepository)
    {
        $this->academicLevelRepository = $academicLevelRepository;
    }

    /**
     * @OA\Put(
     *     path="/AcademicLevels/Update/{academicLevelId}",
     *     summary="Actualizar un nivel académico",
     *     description="Actualiza un nivel académico existente por su ID.",
     *     tags={"AcademicLevels"},
     *     @OA\Parameter(
     *         name="academicLevelId",
     *         in="path",
     *         required=true,
     *         description="ID del nivel académico a actualizar",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=8, description="Debe ser 8 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Pregrado"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Programa de educación universitaria.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El nivel académico fue actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="academicLevels", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="item_id", type="integer", example=8),
     *                     @OA\Property(property="name", type="string", example="Pregrado"),
     *                     @OA\Property(property="description", type="string", example="Programa de educación universitaria."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-15 08:20:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-02 14:10:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El nivel academico fue actualizado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nivel académico no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="El nivel academico no existe: No se encontró el ID especificado.")
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
     *                 @OA\Property(property="description", type="string", example="Descripción")
     *             ),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="item_id", type="array", @OA\Items(type="string", example="No existe en la base de datos.")),
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Ya ha sido tomado.")),
     *                 @OA\Property(property="description", type="array", @OA\Items(type="string", example="No debe exceder los 255 caracteres."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al actualizar el nivel académico.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al actualizar el nivel academico: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(AcademicLevelUpdateRequest $request, int $academicLevelId)
    {
        try {
            $academicLevelId = new AcademicLevelId($academicLevelId ?? null);
            $academicLevelUpdateUseCase = new AcademicLevelUpdateUseCase($this->academicLevelRepository);
            $academicLevel = new AcademicLevelResource($academicLevelUpdateUseCase->__invoke($request, $academicLevelId));
            return $this->successResponse(
                $academicLevel,
                'El nivel académico fue actualizado exitosamente.',
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
