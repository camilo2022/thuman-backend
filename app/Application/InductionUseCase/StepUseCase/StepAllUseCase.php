<?php

declare(strict_types=1);

namespace App\Application\InductionUseCase\StepUseCase;

use App\Domain\Induction\Step\Contracts\StepContract;
use App\Domain\Induction\Values\InductionId;
use App\Http\Requests\Induction\Step\StepAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class StepAllUseCase
{
    private $stepContract;

    public function __construct(StepContract $stepContract)
    {
        $this->stepContract = $stepContract;
    }

    public function __invoke(StepAllRequest $request, InductionId $inductionId): LengthAwarePaginator
    {
        return $this->stepContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request, $inductionId);
    }
}
