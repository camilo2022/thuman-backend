<?php

declare(strict_types=1);

namespace App\Application\CIEChapterUseCase\CIEGroupUseCase\CIESubgroupUseCase;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Contracts\CIESubgroupContract;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;
use App\Http\Requests\CIEChapter\CIEGroup\CIESubgroup\CIESubgroupAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CIESubgroupAllUseCase
{
    private $cieSubgroupContract;

    public function __construct(CIESubgroupContract $cieSubgroupContract)
    {
        $this->cieSubgroupContract = $cieSubgroupContract;
    }

    public function __invoke(CIESubgroupAllRequest $request, CIEGroupId $cieGroupId): LengthAwarePaginator
    {
        return $this->cieSubgroupContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $cieGroupId);
    }
}
