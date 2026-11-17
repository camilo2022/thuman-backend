<?php

declare(strict_types=1);

namespace App\Application\DisabilityUseCase;

use App\Domain\Disability\Disability;
use App\Domain\Disability\Contracts\DisabilityContract;
use App\Domain\Disability\Values\DisabilityId;

final class DisabilityFindUseCase
{
    private $disabilityContract;

    public function __construct(DisabilityContract $disabilityContract)
    {
        $this->disabilityContract = $disabilityContract;
    }

    public function __invoke(DisabilityId $disabilityId): ?Disability
    {
        return $this->disabilityContract->find($disabilityId);
    }
}
