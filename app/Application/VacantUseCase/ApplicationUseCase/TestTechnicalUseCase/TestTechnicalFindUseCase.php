<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\TestTechnicalUseCase;

use App\Domain\Vacant\Application\TestTechnical\TestTechnical;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalId;
use App\Domain\Vacant\Application\TestTechnical\Contracts\TestTechnicalContract;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalFindRequest;

final class TestTechnicalFindUseCase
{
    private $testTechnicalContract;

    public function __construct(TestTechnicalContract $testTechnicalContract)
    {
        $this->testTechnicalContract = $testTechnicalContract;
    }

    public function __invoke(TestTechnicalFindRequest $request, TestTechnicalId $testTechnicalId): ?TestTechnical
    {
        return $this->testTechnicalContract->find($request, $testTechnicalId);
    }
}
