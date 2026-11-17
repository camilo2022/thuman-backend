<?php

declare(strict_types=1);

namespace App\Application\TerminationTypeUseCase\ReasonUseCase;

use App\Domain\TerminationType\Reason\Reason;
use App\Domain\TerminationType\Reason\Contracts\ReasonContract;
use App\Domain\TerminationType\Reason\Values\ReasonId;

final class ReasonFindUseCase
{
    private $reasonContract;

    public function __construct(ReasonContract $reasonContract)
    {
        $this->reasonContract = $reasonContract;
    }

    public function __invoke(ReasonId $reasonId): ?Reason
    {
        return $this->reasonContract->find($reasonId);
    }
}
