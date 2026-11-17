<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical\Values;

final class TestPsychotechnicalRelationships
{
    private ?object $application;
    private ?object $form;
    private ?object $psychologist;

    public function __construct(
        ?object $application = null,
        ?object $form = null,
        ?object $psychologist = null
    ) {
        $this->application = $application;
        $this->form = $form;
        $this->psychologist = $psychologist;
    }

    public function application(): ?object
    {
        return $this->application;
    }

    public function form(): ?object
    {
        return $this->form;
    }

    public function psychologist(): ?object
    {
        return $this->psychologist;
    }

    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'form' => $this->form,
            'psychologist' => $this->psychologist
        ];
    }
}
