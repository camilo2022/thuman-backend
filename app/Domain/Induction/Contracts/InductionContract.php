<?php

declare(strict_types=1);

namespace App\Domain\Induction\Contracts;

use App\Domain\Induction\Induction;
use App\Domain\Induction\Values\InductionId;
use App\Http\Requests\Induction\InductionAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface InductionContract
{
    public function all(string|null $search, int|null $perPage, InductionAllRequest $request): LengthAwarePaginator;

    public function find(InductionId $inductionId): ?Induction;

    public function store(Induction $induction): Induction;

    public function update(Induction $induction, InductionId $inductionId): Induction;

    public function delete(InductionId $inductionId): Induction;

    public function restore(InductionId $inductionId): Induction;
}
