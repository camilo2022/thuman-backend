<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Contracts;

use App\Domain\Benefit\Benefit;
use App\Domain\Benefit\Icon;
use App\Domain\Benefit\Values\BenefitId;
use App\Http\Requests\Benefit\BenefitAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface BenefitContract
{
    public function all(string|null $search, int|null $perPage, BenefitAllRequest $request): LengthAwarePaginator;

    public function find(BenefitId $benefitId): ?Benefit;

    public function store(Benefit $benefit): Benefit;

    public function update(Benefit $benefit, BenefitId $benefitId): Benefit;

    public function delete(BenefitId $benefitId): Benefit;

    public function restore(BenefitId $benefitId): Benefit;

    public function icon(Icon $icon): Icon;
}
