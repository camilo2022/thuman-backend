<?php

declare(strict_types=1);

namespace App\Application\InductionUseCase;

use App\Domain\Induction\Induction;
use App\Domain\Induction\Contracts\InductionContract;
use App\Domain\Induction\Values\InductionId;

final class InductionDeleteUseCase
{
    private $inductionContract;

    public function __construct(InductionContract $inductionContract)
    {
        $this->inductionContract = $inductionContract;
    }

    public function __invoke(InductionId $inductionId): ?Induction
    {
        return $this->inductionContract->delete($inductionId);
    }
}
