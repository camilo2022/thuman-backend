<?php

declare(strict_types=1);

namespace App\Domain\Termination\Values;

final class TerminationRelationships
{
    private ?object $person;
    private ?object $model;
    private ?object $file;

    public function __construct(
        ?object $person = null,
        ?object $model = null,
        ?object $file = null,
    ) {
        $this->person = $person;
        $this->model = $model;
        $this->file = $file;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function model(): ?object
    {
        return $this->model;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'model' => $this->model,
            'file' => $this->file
        ];
    }
}
