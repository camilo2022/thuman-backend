<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\SkillType;

use App\Application\SkillTypeUseCase\SkillTypeFindUseCase;
use App\Domain\SkillType\Values\SkillTypeId;
use App\Http\Resources\SkillType\SkillTypeResource;
use App\Infrastructure\Repositories\SkillTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SkillTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $skillTypeRepository;

    public function __construct(SkillTypeRepository $skillTypeRepository)
    {
        $this->skillTypeRepository = $skillTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/SkillTypes/Find/{skillTypeId}",
     *     summary="Buscar tipo de habilidad por ID",
     *     description="Retorna el detalle de un tipo de habilidad.",
     *     tags={"SkillTypes"},
     *     @OA\Parameter(
     *         name="skillTypeId",
     *         in="path",
     *         required=true,
     *         description="ID del tipo de habilidad",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo de habilidad fue encontrada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="skillType",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Blanda"),
     *                     @OA\Property(property="description", type="string", example="Habilidades sociales y actitudinales."),
     *                     @OA\Property(property="settings", type="object", example={"regex":"^[1-5]$","message":"Seleccione una calificación de 1 a 5 estrellas."}),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 11:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="El tipo de habilidad fue encontrada exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de habilidad no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No encontrado."),
     *             @OA\Property(property="error", type="string", example="El tipo de habilidad no existe: No se encontró el ID especificado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al buscar el tipo de habilidad.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado."),
     *             @OA\Property(property="error", type="string", example="Error al buscar el tipo de habilidad: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(int $skillTypeId)
    {
        try {
            $skillTypeId = new SkillTypeId($skillTypeId ?? null);
            $skillTypeFindUseCase = new SkillTypeFindUseCase($this->skillTypeRepository);
            $skillType = new SkillTypeResource($skillTypeFindUseCase->__invoke($skillTypeId));
            return $this->successResponse(
                $skillType,
                'El tipo de habilidad fue encontrada exitosamente.',
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
