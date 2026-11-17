<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\TestTechnicalUseCase;

use App\Domain\Vacant\Application\TestTechnical\Contracts\TestTechnicalContract;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class TestTechnicalAllUseCase
{
    private $testTechnicalContract;

    public function __construct(TestTechnicalContract $testTechnicalContract)
    {
        $this->testTechnicalContract = $testTechnicalContract;
    }

    public function __invoke(TestTechnicalAllRequest $request): LengthAwarePaginator
    {
        return $this->testTechnicalContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
