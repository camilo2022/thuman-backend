<?php

declare(strict_types=1);

namespace App\Application\CIEChapterUseCase\CIEGroupUseCase;

use App\Domain\CIEChapter\CIEGroup\CIEGroup;
use App\Domain\CIEChapter\CIEGroup\Contracts\CIEGroupContract;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;

final class CIEGroupFindUseCase
{
    private $cieGroupContract;

    public function __construct(CIEGroupContract $cieGroupContract)
    {
        $this->cieGroupContract = $cieGroupContract;
    }

    public function __invoke(CIEGroupId $cieGroupId): ?CIEGroup
    {
        return $this->cieGroupContract->find($cieGroupId);
    }
}
