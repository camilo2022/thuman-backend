<?php

declare(strict_types=1);

namespace App\Application\InductionUseCase;

use App\Domain\Induction\Contracts\InductionContract;
use App\Http\Requests\Induction\InductionAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class InductionAllUseCase
{
    private $inductionContract;

    public function __construct(InductionContract $inductionContract)
    {
        $this->inductionContract = $inductionContract;
    }

    public function __invoke(InductionAllRequest $request): LengthAwarePaginator
    {
        return $this->inductionContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
