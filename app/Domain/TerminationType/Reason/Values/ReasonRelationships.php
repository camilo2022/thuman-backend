<?php

declare(strict_types=1);

namespace App\Domain\TerminationType\Reason\Values;

final class ReasonRelationships
{
    private ?object $termination_type;

    public function __construct(
        ?object $termination_type = null
    ) {
        $this->termination_type = $termination_type;
    }

    public function terminationType(): ?object
    {
        return $this->termination_type;
    }

    public function toArray(): array
    {
        return [
            'termination_type' => $this->termination_type
        ];
    }
}
