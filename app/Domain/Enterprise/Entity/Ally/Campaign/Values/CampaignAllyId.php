<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Ally\Campaign\Values;

final class CampaignAllyId
{
    private $value;

    public function __construct(int|null $allyId)
    {
        $this->value = $allyId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
