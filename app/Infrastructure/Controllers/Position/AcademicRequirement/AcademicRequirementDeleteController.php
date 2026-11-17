<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\AcademicRequirement;

use App\Application\PositionUseCase\AcademicRequirementUseCase\AcademicRequirementDeleteUseCase;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementId;
use App\Infrastructure\Repositories\AcademicRequirementRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AcademicRequirementDeleteController
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
            $academicRequirementId = new AcademicRequirementId($academicRequirementId);
            $academicRequirementDeleteUseCase = new AcademicRequirementDeleteUseCase($this->academicRequirementRepository);
            $academicRequirementDeleteUseCase->__invoke($academicRequirementId);
            return $this->successResponse(
                '',
                'El estudio requerido fue eliminado exitosamente.',
                204
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
