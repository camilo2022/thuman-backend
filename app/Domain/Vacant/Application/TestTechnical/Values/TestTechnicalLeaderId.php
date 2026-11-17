<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Values;

final class TestTechnicalLeaderId
{
    private $value;

    public function __construct(int|null $leaderId)
    {
        $this->value = $leaderId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
