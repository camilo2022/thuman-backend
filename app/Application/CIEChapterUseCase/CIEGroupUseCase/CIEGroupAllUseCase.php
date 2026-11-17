<?php

declare(strict_types=1);

namespace App\Application\CIEChapterUseCase\CIEGroupUseCase;

use App\Domain\CIEChapter\CIEGroup\Contracts\CIEGroupContract;
use App\Domain\CIEChapter\Values\CIEChapterId;
use App\Http\Requests\CIEChapter\CIEGroup\CIEGroupAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CIEGroupAllUseCase
{
    private $cieGroupContract;

    public function __construct(CIEGroupContract $cieGroupContract)
    {
        $this->cieGroupContract = $cieGroupContract;
    }

    public function __invoke(CIEGroupAllRequest $request, CIEChapterId $cieChapterId): LengthAwarePaginator
    {
        return $this->cieGroupContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $cieChapterId);
    }
}
