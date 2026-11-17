<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\Contracts;

use App\Domain\CIEChapter\CIEGroup\CIEGroup;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;
use App\Domain\CIEChapter\Values\CIEChapterId;
use Illuminate\Pagination\LengthAwarePaginator;

interface CIEGroupContract
{
    public function all(string|null $search, int|null $perPage, CIEChapterId $cieChapterId): LengthAwarePaginator;

    public function find(CIEGroupId $cieGroupId): ?CIEGroup;
}
