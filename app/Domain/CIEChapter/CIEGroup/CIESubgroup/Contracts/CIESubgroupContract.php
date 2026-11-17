<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\CIESubgroup\Contracts;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIESubgroup;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;
use Illuminate\Pagination\LengthAwarePaginator;

interface CIESubgroupContract
{
    public function all(string|null $search, int|null $perPage, CIEGroupId $cieGroupId): LengthAwarePaginator;

    public function find(CIESubgroupId $cieSubgroupId): ?CIESubgroup;
}
