<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewVideo\Values;

final class InterviewVideoRelationships
{
    private ?object $application;
    private ?object $form;
    private ?object $reviewer;

    public function __construct(
        ?object $application = null,
        ?object $form = null,
        ?object $reviewer = null
    ) {
        $this->application = $application;
        $this->form = $form;
        $this->reviewer = $reviewer;
    }

    public function application(): ?object
    {
        return $this->application;
    }

    public function form(): ?object
    {
        return $this->form;
    }

    public function reviewer(): ?object
    {
        return $this->reviewer;
    }

    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'form' => $this->form,
            'reviewer' => $this->reviewer
        ];
    }
}
