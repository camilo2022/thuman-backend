<?php

declare(strict_types=1);

namespace App\Domain\Induction\Step\Contracts;

use App\Domain\Induction\Step\File;
use App\Domain\Induction\Step\Step;
use App\Domain\Induction\Step\Values\StepId;
use App\Domain\Induction\Values\InductionId;
use App\Http\Requests\Induction\Step\StepAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface StepContract
{
    public function all(string|null $search, int|null $perPage, StepAllRequest $request, InductionId $inductionId): LengthAwarePaginator;

    public function find(StepId $stepId): ?Step;

    public function store(Step $step): Step;

    public function update(Step $step, StepId $stepId): Step;

    public function delete(StepId $stepId): Step;

    public function restore(StepId $stepId): Step;

    public function file(File $file): File;
}
