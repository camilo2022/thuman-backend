<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial\Values;

final class InterviewInitialIndication
{
    private $value;

    public function __construct(string|null $indication)
    {
        $this->value = $indication;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
