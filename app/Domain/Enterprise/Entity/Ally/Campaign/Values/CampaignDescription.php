<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Campaign\Values;

final class CampaignDescription
{
    private $value;

    public function __construct(string $description)
    {
        $this->value = $description;
    }

    public function value(): string
    {
        return $this->value;
    }
}
