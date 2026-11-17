<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredExperience;

use App\Application\PositionUseCase\RequiredExperienceUseCase\RequiredExperienceUpdateUseCase;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceId;
use App\Http\Requests\Position\RequiredExperience\RequiredExperienceUpdateRequest;
use App\Http\Resources\Position\RequiredExperience\RequiredExperienceResource;
use App\Infrastructure\Repositories\RequiredExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredExperienceUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $requiredExperienceRepository;

    public function __construct(RequiredExperienceRepository $requiredExperienceRepository)
    {
        $this->requiredExperienceRepository = $requiredExperienceRepository;
    }

    public function __invoke(RequiredExperienceUpdateRequest $request, int $requiredExperienceId)
    {
        try {
            $requiredExperienceId = new RequiredExperienceId($requiredExperienceId ?? null);
            $requiredExperienceUpdateUseCase = new RequiredExperienceUpdateUseCase($this->requiredExperienceRepository);
            $requiredExperience = new RequiredExperienceResource($requiredExperienceUpdateUseCase->__invoke($request, $requiredExperienceId));
            return $this->successResponse(
                $requiredExperience,
                'La experiencia requerida fue actualizado exitosamente.',
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
