<?php

declare(strict_types=1);

namespace App\Domain\Progress\Values;

final class ProgressRelationships
{
    private ?object $person;
    private ?object $step;

    public function __construct(
        ?object $person = null,
        ?object $step = null
    ) {
        $this->person = $person;
        $this->step = $step;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function step(): ?object
    {
        return $this->step;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'step' => $this->step
        ];
    }
}
