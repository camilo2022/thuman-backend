<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Campaign\Values;

final class CampaignRelationships
{
    private ?object $ally;

    public function __construct(
        ?object $ally = null
    ) {
        $this->ally = $ally;
    }

    public function ally(): ?object
    {
        return $this->ally;
    }

    public function toArray(): array
    {
        return [
            'ally' => $this->ally,
        ];
    }
}
