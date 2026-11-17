<?php

declare(strict_types=1);

namespace App\Domain\Induction\Values;

final class InductionRelationships
{
    private ?object $assignable;
    private ?object $model;

    public function __construct(
        ?object $assignable = null,
        ?object $model = null
    ) {
        $this->assignable = $assignable;
        $this->model = $model;
    }

    public function assignable(): ?object
    {
        return $this->assignable;
    }

    public function model(): ?object
    {
        return $this->model;
    }

    public function toArray(): array
    {
        return [
            'assignable' => $this->assignable,
            'model' => $this->model
        ];
    }
}
