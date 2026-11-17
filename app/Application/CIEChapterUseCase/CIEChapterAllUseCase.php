<?php

declare(strict_types=1);

namespace App\Application\CIEChapterUseCase;

use App\Domain\CIEChapter\Contracts\CIEChapterContract;
use App\Http\Requests\CIEChapter\CIEChapterAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CIEChapterAllUseCase
{
    private $cieChapterContract;

    public function __construct(CIEChapterContract $cieChapterContract)
    {
        $this->cieChapterContract = $cieChapterContract;
    }

    public function __invoke(CIEChapterAllRequest $request): LengthAwarePaginator
    {
        return $this->cieChapterContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
