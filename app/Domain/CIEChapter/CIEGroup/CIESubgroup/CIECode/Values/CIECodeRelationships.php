<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values;

final class CIECodeRelationships
{
    private ?object $cie_subgroup;

    public function __construct(
        ?object $cie_subgroup = null
    ) {
        $this->cie_subgroup = $cie_subgroup;
    }

    public function cieSubgroup(): ?object
    {
        return $this->cie_subgroup;
    }

    public function toArray(): array
    {
        return [
            'cie_subgroup' => $this->cie_subgroup
        ];
    }
}
