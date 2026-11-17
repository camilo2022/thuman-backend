<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Contracts;

use App\Domain\Enterprise\Enterprise;
use App\Domain\Enterprise\Logo;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Requests\Enterprise\EnterpriseAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface EnterpriseContract
{
    public function all(string|null $search, int|null $perPage, EnterpriseAllRequest $request): LengthAwarePaginator;

    public function find(EnterpriseId $enterpriseId): ?Enterprise;

    public function store(Enterprise $enterprise): Enterprise;

    public function update(Enterprise $enterprise, EnterpriseId $enterpriseId): Enterprise;

    public function delete(EnterpriseId $enterpriseId): Enterprise;

    public function restore(EnterpriseId $enterpriseId): Enterprise;

    public function logo(Logo $logo): Logo;
}
