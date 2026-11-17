<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Campaign\Values;

final class CampaignId
{
    private $value;

    public function __construct(int|null $id)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
