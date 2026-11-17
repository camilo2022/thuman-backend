<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Contracts;

use App\Domain\Vacant\Application\TestTechnical\TestTechnical;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalId;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalAllRequest;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface TestTechnicalContract
{
    public function all(string|null $search, int|null $perPage, TestTechnicalAllRequest $request): LengthAwarePaginator;

    public function find(TestTechnicalFindRequest $request, TestTechnicalId $testTechnicalId): ?TestTechnical;

    public function store(TestTechnical $testTechnical): TestTechnical;

    public function update(TestTechnical $testTechnical, TestTechnicalId $testTechnicalId): TestTechnical;
}
