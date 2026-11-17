<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredExperience;

use App\Application\PositionUseCase\RequiredExperienceUseCase\RequiredExperienceDeleteUseCase;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceId;
use App\Infrastructure\Repositories\RequiredExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredExperienceDeleteController
{
    use ApiResponser;
    use ApiMessage;
    private $requiredExperienceRepository;

    public function __construct(RequiredExperienceRepository $requiredExperienceRepository)
    {
        $this->requiredExperienceRepository = $requiredExperienceRepository;
    }

    public function __invoke(int $requiredExperienceId)
    {
        try {
            $requiredExperienceId = new RequiredExperienceId($requiredExperienceId);
            $requiredExperienceDeleteUseCase = new RequiredExperienceDeleteUseCase($this->requiredExperienceRepository);
            $requiredExperienceDeleteUseCase->__invoke($requiredExperienceId);
            return $this->successResponse(
                '',
                'La experiencia requerida fue inactivada exitosamente.',
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
