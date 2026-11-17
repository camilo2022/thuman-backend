<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\Values;

final class CIEGroupRelationships
{
    private ?object $cie_chapter;

    public function __construct(
        ?object $cie_chapter = null
    ) {
        $this->cie_chapter = $cie_chapter;
    }

    public function cieGroup(): ?object
    {
        return $this->cie_chapter;
    }

    public function toArray(): array
    {
        return [
            'cie_chapter' => $this->cie_chapter
        ];
    }
}
