<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial\Values;

final class InterviewInitialRelationships
{
    private ?object $application;
    private ?object $form;
    private ?object $reviewer;
    private ?object $leader;

    public function __construct(
        ?object $application = null,
        ?object $form = null,
        ?object $reviewer = null,
        ?object $leader = null
    ) {
        $this->application = $application;
        $this->form = $application;
        $this->reviewer = $reviewer;
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

    public function reviewer(): ?object
    {
        return $this->reviewer;
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
            'reviewer' => $this->reviewer,
            'leader' => $this->leader
        ];
    }
}
