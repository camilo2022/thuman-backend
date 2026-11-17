<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Campaign\Values;

final class CampaignDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
