<?php

declare(strict_types=1);

namespace App\Application\BenefitUseCase;

use App\Domain\Benefit\Benefit;
use App\Domain\Benefit\Contracts\BenefitContract;
use App\Domain\Benefit\Values\BenefitId;

final class BenefitRestoreUseCase
{
    private $benefitContract;

    public function __construct(BenefitContract $benefitContract)
    {
        $this->benefitContract = $benefitContract;
    }

    public function __invoke(BenefitId $benefitId): ?Benefit
    {
        return $this->benefitContract->restore($benefitId);
    }
}
