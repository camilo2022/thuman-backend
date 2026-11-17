<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\AcademicRequirement;

use App\Application\PositionUseCase\AcademicRequirementUseCase\AcademicRequirementAllUseCase;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\AcademicRequirement\AcademicRequirementAllRequest;
use App\Http\Resources\Position\AcademicRequirement\AcademicRequirementCollection;
use App\Infrastructure\Repositories\AcademicRequirementRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AcademicRequirementAllController
{
    use ApiResponser;
    use ApiMessage;
    private $academicRequirementRepository;

    public function __construct(AcademicRequirementRepository $academicRequirementRepository)
    {
        $this->academicRequirementRepository = $academicRequirementRepository;
    }

    public function __invoke(AcademicRequirementAllRequest $request, int $positionId)
    {
        try {
            $positionId = new PositionId($positionId ?? null);
            $academicRequirementAllUseCase = new AcademicRequirementAllUseCase($this->academicRequirementRepository);
            $academicRequirements = new AcademicRequirementCollection($academicRequirementAllUseCase->__invoke($request, $positionId));
            return $this->successResponse(
                $academicRequirements,
                'Los estudios requeridos fueron consultados exitosamente.',
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
