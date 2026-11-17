<?php

declare(strict_types=1);

namespace App\Application\InductionUseCase\StepUseCase;

use App\Domain\Induction\Step\Step;
use App\Domain\Induction\Step\Contracts\StepContract;
use App\Domain\Induction\Step\Values\StepId;

final class StepDeleteUseCase
{
    private $stepContract;

    public function __construct(StepContract $stepContract)
    {
        $this->stepContract = $stepContract;
    }

    public function __invoke(StepId $stepId): ?Step
    {
        return $this->stepContract->delete($stepId);
    }
}
