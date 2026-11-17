<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\CriminalRecord\Values;

final class CriminalRecordRelationships
{
    private ?object $application;
    private ?object $file;

    public function __construct(
        ?object $application = null,
        ?object $file = null
    ) {
        $this->application = $application;
        $this->file = $file;
    }

    public function application(): ?object
    {
        return $this->application;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'file' => $this->file
        ];
    }
}
