<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\AcademicRequirement;

use App\Application\PositionUseCase\AcademicRequirementUseCase\AcademicRequirementStoreUseCase;
use App\Http\Requests\Position\AcademicRequirement\AcademicRequirementStoreRequest;
use App\Http\Resources\Position\AcademicRequirement\AcademicRequirementResource;
use App\Infrastructure\Repositories\AcademicRequirementRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class AcademicRequirementStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $academicRequirementRepository;

    public function __construct(AcademicRequirementRepository $academicRequirementRepository)
    {
        $this->academicRequirementRepository = $academicRequirementRepository;
    }

    public function __invoke(AcademicRequirementStoreRequest $request)
    {
        try {
            $academicRequirementStoreUseCase = new AcademicRequirementStoreUseCase($this->academicRequirementRepository);
            $academicRequirement = new AcademicRequirementResource($academicRequirementStoreUseCase->__invoke($request));
            return $this->successResponse(
                $academicRequirement,
                'El estudio requeridos fue registrado exitosamente.',
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
