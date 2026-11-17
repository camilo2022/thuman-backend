<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\Contracts;

use App\Domain\CIEChapter\CIEChapter;
use App\Domain\CIEChapter\Values\CIEChapterId;
use Illuminate\Pagination\LengthAwarePaginator;

interface CIEChapterContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(CIEChapterId $cieChapterId): ?CIEChapter;
}
