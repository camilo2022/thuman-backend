<?php

declare(strict_types=1);

namespace App\Domain\Disability\Contracts;

use App\Domain\Disability\Disability;
use App\Domain\Disability\File;
use App\Domain\Disability\Values\DisabilityId;
use App\Http\Requests\Disability\DisabilityAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface DisabilityContract
{
    public function all(string|null $search, int|null $perPage, DisabilityAllRequest $request): LengthAwarePaginator;

    public function find(DisabilityId $disabilityId): ?Disability;

    public function store(Disability $disability): Disability;

    public function update(Disability $disability, DisabilityId $disabilityId): Disability;

    public function file(File $file): File;
}
