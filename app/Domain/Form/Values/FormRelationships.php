<?php

declare(strict_types=1);

namespace App\Domain\Form\Values;

final class FormRelationships
{
    private ?object $form_type;
    private ?object $assignable;
    private ?object $model;

    public function __construct(
        ?object $form_type = null,
        ?object $assignable = null,
        ?object $model = null
    ) {
        $this->form_type = $form_type;
        $this->assignable = $assignable;
        $this->model = $model;
    }

    public function formType(): ?object
    {
        return $this->form_type;
    }

    public function toArray(): array
    {
        return [
            'form_type' => $this->form_type,
            'assignable' => $this->assignable,
            'model' => $this->model
        ];
    }
}
