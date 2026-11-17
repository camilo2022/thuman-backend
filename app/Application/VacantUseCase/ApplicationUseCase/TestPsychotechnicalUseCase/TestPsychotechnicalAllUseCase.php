<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\TestPsychotechnicalUseCase;

use App\Domain\Vacant\Application\TestPsychotechnical\Contracts\TestPsychotechnicalContract;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class TestPsychotechnicalAllUseCase
{
    private $testPsychotechnicalContract;

    public function __construct(TestPsychotechnicalContract $testPsychotechnicalContract)
    {
        $this->testPsychotechnicalContract = $testPsychotechnicalContract;
    }

    public function __invoke(TestPsychotechnicalAllRequest $request): LengthAwarePaginator
    {
        return $this->testPsychotechnicalContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
