<?php

declare(strict_types=1);

namespace App\Application\WorkArrangementUseCase;

use App\Domain\WorkArrangement\Contracts\WorkArrangementContract;
use App\Http\Requests\WorkArrangement\WorkArrangementAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class WorkArrangementAllUseCase
{
    private $workArrangementContract;

    public function __construct(WorkArrangementContract $workArrangementContract)
    {
        $this->workArrangementContract = $workArrangementContract;
    }

    public function __invoke(WorkArrangementAllRequest $request): LengthAwarePaginator
    {
        return $this->workArrangementContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
