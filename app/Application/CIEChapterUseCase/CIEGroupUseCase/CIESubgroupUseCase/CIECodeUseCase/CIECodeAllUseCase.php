<?php

declare(strict_types=1);

namespace App\Application\CIEChapterUseCase\CIEGroupUseCase\CIESubgroupUseCase\CIECodeUseCase;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Contracts\CIECodeContract;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;
use App\Http\Requests\CIEChapter\CIEGroup\CIESubgroup\CIECode\CIECodeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class CIECodeAllUseCase
{
    private $cieCodeContract;

    public function __construct(CIECodeContract $cieCodeContract)
    {
        $this->cieCodeContract = $cieCodeContract;
    }

    public function __invoke(CIECodeAllRequest $request, CIESubgroupId $cieSubgroupId): LengthAwarePaginator
    {
        return $this->cieCodeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $cieSubgroupId);
    }
}
