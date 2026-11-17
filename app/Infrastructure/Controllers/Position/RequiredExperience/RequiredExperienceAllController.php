<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredExperience;

use App\Application\PositionUseCase\RequiredExperienceUseCase\RequiredExperienceAllUseCase;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\RequiredExperience\RequiredExperienceAllRequest;
use App\Http\Resources\Position\RequiredExperience\RequiredExperienceCollection;
use App\Infrastructure\Repositories\RequiredExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredExperienceAllController
{
    use ApiResponser;
    use ApiMessage;
    private $requiredExperienceRepository;

    public function __construct(RequiredExperienceRepository $requiredExperienceRepository)
    {
        $this->requiredExperienceRepository = $requiredExperienceRepository;
    }

    public function __invoke(RequiredExperienceAllRequest $request, int $positionId)
    {
        try {
            $positionId = new PositionId($positionId ?? null);
            $requiredExperienceAllUseCase = new RequiredExperienceAllUseCase($this->requiredExperienceRepository);
            $requiredExperiences = new RequiredExperienceCollection($requiredExperienceAllUseCase->__invoke($request, $positionId));
            return $this->successResponse(
                $requiredExperiences,
                'Las experiencias requeridas fueron consultados exitosamente.',
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
