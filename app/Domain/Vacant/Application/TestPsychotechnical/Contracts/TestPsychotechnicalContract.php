<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical\Contracts;

use App\Domain\Vacant\Application\TestPsychotechnical\TestPsychotechnical;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalId;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalAllRequest;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface TestPsychotechnicalContract
{
    public function all(string|null $search, int|null $perPage, TestPsychotechnicalAllRequest $request): LengthAwarePaginator;

    public function find(TestPsychotechnicalFindRequest $request, TestPsychotechnicalId $testPsychotechnicalId): ?TestPsychotechnical;

    public function store(TestPsychotechnical $testPsychotechnical): TestPsychotechnical;

    public function update(TestPsychotechnical $testPsychotechnical, TestPsychotechnicalId $testPsychotechnicalId): TestPsychotechnical;
}
