<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamPolygraph\Contracts;

use App\Domain\Vacant\Application\ExamPolygraph\ExamPolygraph;
use App\Domain\Vacant\Application\ExamPolygraph\File;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphId;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphAllRequest;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ExamPolygraphContract
{
    public function all(string|null $search, int|null $perPage, ExamPolygraphAllRequest $request): LengthAwarePaginator;

    public function find(ExamPolygraphFindRequest $request, ExamPolygraphId $examPolygraphId): ?ExamPolygraph;

    public function store(ExamPolygraph $examPolygraph): ExamPolygraph;

    public function update(ExamPolygraph $examPolygraph, ExamPolygraphId $examPolygraphId): ExamPolygraph;

    public function file(File $file): File;
}
