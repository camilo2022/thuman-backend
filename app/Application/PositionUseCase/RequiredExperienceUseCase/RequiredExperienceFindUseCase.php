<?php

declare(strict_types=1);

namespace App\Application\PositionUseCase\RequiredExperienceUseCase;

use App\Domain\Position\RequiredExperience\RequiredExperience;
use App\Domain\Position\RequiredExperience\Contracts\RequiredExperienceContract;
use App\Domain\Position\RequiredExperience\Values\RequiredExperienceId;

final class RequiredExperienceFindUseCase
{
    private $requiredExperienceContract;

    public function __construct(RequiredExperienceContract $requiredExperienceContract)
    {
        $this->requiredExperienceContract = $requiredExperienceContract;
    }

    public function __invoke(RequiredExperienceId $requiredExperienceId): ?RequiredExperience
    {
        return $this->requiredExperienceContract->find($requiredExperienceId);
    }
}
