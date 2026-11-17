<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Contracts;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\CIECode;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeId;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;
use Illuminate\Pagination\LengthAwarePaginator;

interface CIECodeContract
{
    public function all(string|null $search, int|null $perPage, CIESubgroupId $cieSubgroupId): LengthAwarePaginator;

    public function find(CIECodeId $cieCodeId): ?CIECode;
}
