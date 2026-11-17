<?php

declare(strict_types=1);

namespace App\Domain\Resignation\Values;

final class ResignationRelationships
{
    private ?object $person;
    private ?object $resignation_type;

    public function __construct(
        ?object $person = null,
        ?object $resignation_type = null,
    ) {
        $this->person = $person;
        $this->resignation_type = $resignation_type;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function resignationType(): ?object
    {
        return $this->resignation_type;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'resignation_type' => $this->resignation_type
        ];
    }
}
