<?php

declare(strict_types=1);

namespace App\Application\CIEChapterUseCase;

use App\Domain\CIEChapter\CIEChapter;
use App\Domain\CIEChapter\Contracts\CIEChapterContract;
use App\Domain\CIEChapter\Values\CIEChapterId;

final class CIEChapterFindUseCase
{
    private $cieChapterContract;

    public function __construct(CIEChapterContract $cieChapterContract)
    {
        $this->cieChapterContract = $cieChapterContract;
    }

    public function __invoke(CIEChapterId $cieChapterId): ?CIEChapter
    {
        return $this->cieChapterContract->find($cieChapterId);
    }
}
