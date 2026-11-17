<?php

declare(strict_types=1);

namespace App\Application\CIEChapterUseCase\CIEGroupUseCase\CIESubgroupUseCase\CIECodeUseCase;

use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\CIECode;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Contracts\CIECodeContract;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeId;

final class CIECodeFindUseCase
{
    private $cieCodeContract;

    public function __construct(CIECodeContract $cieCodeContract)
    {
        $this->cieCodeContract = $cieCodeContract;
    }

    public function __invoke(CIECodeId $cieCodeId): ?CIECode
    {
        return $this->cieCodeContract->find($cieCodeId);
    }
}
