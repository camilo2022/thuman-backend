<?php

declare(strict_types=1);

namespace App\Domain\EpidemiologicalProgram\Values;

final class EpidemiologicalProgramDescription
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
