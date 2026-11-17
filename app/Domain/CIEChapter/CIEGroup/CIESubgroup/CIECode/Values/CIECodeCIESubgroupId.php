<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values;

final class CIECodeCIESubgroupId
{
    private $value;

    public function __construct(int|null $cieSubgroupId)
    {
        $this->value = $cieSubgroupId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
