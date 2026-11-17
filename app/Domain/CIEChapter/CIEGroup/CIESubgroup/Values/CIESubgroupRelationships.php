<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values;

final class CIESubgroupRelationships
{
    private ?object $cie_group;

    public function __construct(
        ?object $cie_group = null
    ) {
        $this->cie_group = $cie_group;
    }

    public function cieGroup(): ?object
    {
        return $this->cie_group;
    }

    public function toArray(): array
    {
        return [
            'cie_group' => $this->cie_group
        ];
    }
}
