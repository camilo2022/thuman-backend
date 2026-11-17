<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\TestPsychotechnicalUseCase;

use App\Domain\Vacant\Application\TestPsychotechnical\TestPsychotechnical;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalId;
use App\Domain\Vacant\Application\TestPsychotechnical\Contracts\TestPsychotechnicalContract;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalFindRequest;

final class TestPsychotechnicalFindUseCase
{
    private $testPsychotechnicalContract;

    public function __construct(TestPsychotechnicalContract $testPsychotechnicalContract)
    {
        $this->testPsychotechnicalContract = $testPsychotechnicalContract;
    }

    public function __invoke(TestPsychotechnicalFindRequest $request, TestPsychotechnicalId $testPsychotechnicalId): ?TestPsychotechnical
    {
        return $this->testPsychotechnicalContract->find($request, $testPsychotechnicalId);
    }
}
