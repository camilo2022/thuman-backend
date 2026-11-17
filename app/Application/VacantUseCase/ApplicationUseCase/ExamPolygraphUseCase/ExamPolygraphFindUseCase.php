<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\ExamPolygraphUseCase;

use App\Domain\Vacant\Application\ExamPolygraph\ExamPolygraph;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphId;
use App\Domain\Vacant\Application\ExamPolygraph\Contracts\ExamPolygraphContract;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphFindRequest;

final class ExamPolygraphFindUseCase
{
    private $examPolygraphContract;

    public function __construct(ExamPolygraphContract $examPolygraphContract)
    {
        $this->examPolygraphContract = $examPolygraphContract;
    }

    public function __invoke(ExamPolygraphFindRequest $request, ExamPolygraphId $examPolygraphId): ?ExamPolygraph
    {
        return $this->examPolygraphContract->find($request, $examPolygraphId);
    }
}
