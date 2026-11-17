<?php

declare(strict_types=1);

namespace App\Application\TerminationUseCase;

use App\Domain\Termination\Termination;
use App\Domain\Termination\Contracts\TerminationContract;
use App\Domain\Termination\Values\TerminationId;

final class TerminationFindUseCase
{
    private $terminationContract;

    public function __construct(TerminationContract $terminationContract)
    {
        $this->terminationContract = $terminationContract;
    }

    public function __invoke(TerminationId $terminationId): ?Termination
    {
        return $this->terminationContract->find($terminationId);
    }
}
