<?php

declare(strict_types=1);

namespace App\Domain\Employee\Values;

final class EmployeeCampaignId
{
    private $value;

    public function __construct(int|null $campaignId)
    {
        $this->value = $campaignId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
