<?php

declare(strict_types=1);

namespace App\Application\TerminationTypeUseCase;

use App\Domain\TerminationType\TerminationType;
use App\Domain\TerminationType\Contracts\TerminationTypeContract;
use App\Domain\TerminationType\Values\TerminationTypeId;

final class TerminationTypeRestoreUseCase
{
    private $terminationTypeContract;

    public function __construct(TerminationTypeContract $terminationTypeContract)
    {
        $this->terminationTypeContract = $terminationTypeContract;
    }

    public function __invoke(TerminationTypeId $terminationTypeId): ?TerminationType
    {
        return $this->terminationTypeContract->restore($terminationTypeId);
    }
}
