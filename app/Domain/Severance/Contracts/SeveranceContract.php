<?php

declare(strict_types=1);

namespace App\Domain\Severance\Contracts;

use App\Domain\Severance\File;
use App\Domain\Severance\Severance;
use App\Domain\Severance\Values\SeveranceId;
use App\Http\Requests\Severance\SeveranceAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface SeveranceContract
{
    public function all(string|null $search, int|null $perPage, SeveranceAllRequest $request): LengthAwarePaginator;

    public function find(SeveranceId $severanceId): ?Severance;

    public function store(Severance $severance): Severance;

    public function update(Severance $severance, SeveranceId $severanceId): Severance;

    public function file(File $file): File;
}
