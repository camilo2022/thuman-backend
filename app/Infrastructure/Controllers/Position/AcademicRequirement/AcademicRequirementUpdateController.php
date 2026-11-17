<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\AcademicRequirement;

use App\Application\PositionUseCase\AcademicRequirementUseCase\AcademicRequirementUpdateUseCase;
use App\Domain\Position\AcademicRequirement\Values\AcademicRequirementId;
use App\Http\Requests\Position\AcademicRequirement\AcademicRequirementUpdateRequest;
use App\Http\Resources\Position\AcademicRequirement\AcademicRequirementResource;
use App\Infrastructure\Repositories\AcademicRequirementRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AcademicRequirementUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $academicRequirementRepository;

    public function __construct(AcademicRequirementRepository $academicRequirementRepository)
    {
        $this->academicRequirementRepository = $academicRequirementRepository;
    }

    public function __invoke(AcademicRequirementUpdateRequest $request, int $academicRequirementId)
    {
        try {
            $academicRequirementId = new AcademicRequirementId($academicRequirementId ?? null);
            $academicRequirementUpdateUseCase = new AcademicRequirementUpdateUseCase($this->academicRequirementRepository);
            $academicRequirement = new AcademicRequirementResource($academicRequirementUpdateUseCase->__invoke($request, $academicRequirementId));
            return $this->successResponse(
                $academicRequirement,
                'El estudio requeridos fue actualizado exitosamente.',
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
