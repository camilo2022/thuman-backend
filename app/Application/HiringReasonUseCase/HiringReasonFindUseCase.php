<?php

declare(strict_types=1);

namespace App\Application\HiringReasonUseCase;

use App\Domain\HiringReason\HiringReason;
use App\Domain\HiringReason\Contracts\HiringReasonContract;
use App\Domain\HiringReason\Values\HiringReasonId;

final class HiringReasonFindUseCase
{
    private $hiringReasonContract;

    public function __construct(HiringReasonContract $hiringReasonContract)
    {
        $this->hiringReasonContract = $hiringReasonContract;
    }

    public function __invoke(HiringReasonId $hiringReasonId): ?HiringReason
    {
        return $this->hiringReasonContract->find($hiringReasonId);
    }
}
