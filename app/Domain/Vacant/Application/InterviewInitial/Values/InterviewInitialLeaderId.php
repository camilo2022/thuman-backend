<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial\Values;

final class InterviewInitialLeaderId
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
