<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\AcademicRequirement;

use App\Application\PositionUseCase\AcademicRequirementUseCase\AcademicRequirementFindUseCase;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementId;
use App\Http\Resources\Position\AcademicRequirement\AcademicRequirementResource;
use App\Infrastructure\Repositories\AcademicRequirementRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AcademicRequirementFindController
{
    use ApiResponser;
    use ApiMessage;
    private $academicRequirementRepository;

    public function __construct(AcademicRequirementRepository $academicRequirementRepository)
    {
        $this->academicRequirementRepository = $academicRequirementRepository;
    }

    public function __invoke(int $academicRequirementId)
    {
        try {
            $academicRequirementId = new AcademicRequirementId($academicRequirementId ?? null);
            $academicRequirementFindUseCase = new AcademicRequirementFindUseCase($this->academicRequirementRepository);
            $academicRequirement = new AcademicRequirementResource($academicRequirementFindUseCase->__invoke($academicRequirementId));
            return $this->successResponse(
                $academicRequirement,
                'El estudio requerido fue encontrado exitosamente.',
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
