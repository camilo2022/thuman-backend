<?php

declare(strict_types=1);

namespace App\Application\CIEChapterUseCase\CIEGroupUseCase\CIESubgroupUseCase;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIESubgroup;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Contracts\CIESubgroupContract;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;

final class CIESubgroupFindUseCase
{
    private $cieSubgroupContract;

    public function __construct(CIESubgroupContract $cieSubgroupContract)
    {
        $this->cieSubgroupContract = $cieSubgroupContract;
    }

    public function __invoke(CIESubgroupId $cieSubgroupId): ?CIESubgroup
    {
        return $this->cieSubgroupContract->find($cieSubgroupId);
    }
}
