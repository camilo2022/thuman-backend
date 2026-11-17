<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\AcademicLevel;

use App\Application\AcademicLevelUseCase\AcademicLevelStoreUseCase;
use App\Http\Requests\AcademicLevel\AcademicLevelStoreRequest;
use App\Http\Resources\AcademicLevel\AcademicLevelResource;
use App\Infrastructure\Repositories\AcademicLevelRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AcademicLevelStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $academicLevelRepository;

    public function __construct(AcademicLevelRepository $academicLevelRepository)
    {
        $this->academicLevelRepository = $academicLevelRepository;
    }

    /**
     * @OA\Post(
     *     path="/AcademicLevels/Store",
     *     summary="Registrar un nuevo nivel académico",
     *     description="Crea un nuevo nivel académico con los datos proporcionados.",
     *     tags={"AcademicLevels"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","name","description"},
     *             @OA\Property(property="item_id", type="integer", example=8, description="Debe ser 8 (categorización fija)"),
     *             @OA\Property(property="name", type="string", maxLength=80, example="Doctorado"),
     *             @OA\Property(property="description", type="string", maxLength=255, example="Programa de educación de posgrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="El nivel académico registrado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="academicLevels", type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="item_id", type="integer", example=8),
     *                     @OA\Property(property="name", type="string", example="Doctorado"),
     *                     @OA\Property(property="description", type="string", example="Programa de educación de posgrado."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-01 10:15:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-01 10:15:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El nivel academico registrado exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=201)
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
     *         description="Error inesperado al guardar el nivel académico.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se ha producido un error."),
     *             @OA\Property(property="error", type="string", example="Error al guardar el nivel academico: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(AcademicLevelStoreRequest $request)
    {
        try {
            $academicLevelStoreUseCase = new AcademicLevelStoreUseCase($this->academicLevelRepository);
            $academicLevel = new AcademicLevelResource($academicLevelStoreUseCase->__invoke($request));
            return $this->successResponse(
                $academicLevel,
                'El nivel académico fue registrado exitosamente.',
                201
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
