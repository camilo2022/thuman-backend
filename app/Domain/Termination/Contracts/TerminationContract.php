<?php

declare(strict_types=1);

namespace App\Domain\Termination\Contracts;

use App\Domain\Termination\File;
use App\Domain\Termination\Termination;
use App\Domain\Termination\Values\TerminationId;
use App\Http\Requests\Termination\TerminationAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface TerminationContract
{
    public function all(string|null $search, int|null $perPage, TerminationAllRequest $request): LengthAwarePaginator;

    public function find(TerminationId $terminationId): ?Termination;

    public function store(Termination $termination): Termination;

    public function update(Termination $termination, TerminationId $terminationId): Termination;

    public function file(File $file): File;
}
