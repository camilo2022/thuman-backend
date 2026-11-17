<?php

declare(strict_types=1);

namespace App\Domain\DisabilityType\Values;

final class DisabilityTypeRelationships
{
    private ?object $item;

    public function __construct(
        ?object $item = null
    ) {
        $this->item = $item;
    }

    public function item(): ?object
    {
        return $this->item;
    }

    public function toArray(): array
    {
        return [
            'item' => $this->item
        ];
    }
}
