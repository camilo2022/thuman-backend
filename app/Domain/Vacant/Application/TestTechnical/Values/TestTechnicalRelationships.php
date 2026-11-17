<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Values;

final class TestTechnicalRelationships
{
    private ?object $application;
    private ?object $form;
    private ?object $leader;

    public function __construct(
        ?object $application = null,
        ?object $form = null,
        ?object $leader = null
    ) {
        $this->application = $application;
        $this->form = $form;
        $this->leader = $leader;
    }

    public function application(): ?object
    {
        return $this->application;
    }

    public function form(): ?object
    {
        return $this->form;
    }

    public function leader(): ?object
    {
        return $this->leader;
    }

    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'form' => $this->form,
            'leader' => $this->leader
        ];
    }
}
