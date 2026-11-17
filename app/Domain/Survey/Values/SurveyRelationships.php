<?php

declare(strict_types=1);

namespace App\Domain\Survey\Values;

final class SurveyRelationships
{
    private ?object $form;
    private ?object $person;

    public function __construct(
        ?object $form = null,
        ?object $person = null
    ) {
        $this->form = $form;
        $this->person = $person;
    }

    public function form(): ?object
    {
        return $this->form;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function toArray(): array
    {
        return [
            'form' => $this->form,
            'person' => $this->person
        ];
    }
}
