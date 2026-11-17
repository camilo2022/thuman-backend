<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredExperience;

use App\Application\PositionUseCase\RequiredExperienceUseCase\RequiredExperienceFindUseCase;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceId;
use App\Http\Resources\Position\RequiredExperience\RequiredExperienceResource;
use App\Infrastructure\Repositories\RequiredExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredExperienceFindController
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
            $requiredExperienceId = new RequiredExperienceId($requiredExperienceId ?? null);
            $requiredExperienceFindUseCase = new RequiredExperienceFindUseCase($this->requiredExperienceRepository);
            $requiredExperience = new RequiredExperienceResource($requiredExperienceFindUseCase->__invoke($requiredExperienceId));
            return $this->successResponse(
                $requiredExperience,
                'La experiencia requerida fue encontrado exitosamente.',
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
