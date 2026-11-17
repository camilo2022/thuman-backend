<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Template\Values;

final class TemplateRelationships
{
    private ?object $model;
    private ?object $file_type;
    private ?object $file_subtype;

    public function __construct(
        ?object $model = null,
        ?object $file_type = null,
        ?object $file_subtype = null
    ) {
        $this->model = $model;
        $this->file_type = $file_type;
        $this->file_subtype = $file_subtype;
    }

    public function model(): ?object
    {
        return $this->model;
    }

    public function file_type(): ?object
    {
        return $this->file_type;
    }

    public function file_subtype(): ?object
    {
        return $this->file_subtype;
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'file_type' => $this->file_type,
            'file_subtype' => $this->file_subtype
        ];
    }
}
