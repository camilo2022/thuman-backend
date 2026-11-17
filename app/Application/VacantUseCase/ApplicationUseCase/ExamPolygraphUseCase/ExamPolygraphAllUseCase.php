<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\ExamPolygraphUseCase;

use App\Domain\Vacant\Application\ExamPolygraph\Contracts\ExamPolygraphContract;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ExamPolygraphAllUseCase
{
    private $examPolygraphContract;

    public function __construct(ExamPolygraphContract $examPolygraphContract)
    {
        $this->examPolygraphContract = $examPolygraphContract;
    }

    public function __invoke(ExamPolygraphAllRequest $request): LengthAwarePaginator
    {
        return $this->examPolygraphContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
