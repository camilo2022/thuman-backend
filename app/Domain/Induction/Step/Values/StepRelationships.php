<?php

declare(strict_types=1);

namespace App\Domain\Induction\Step\Values;

final class StepRelationships
{
    private ?object $induction;
    private ?object $form;
    private ?object $video;

    public function __construct(
        ?object $induction = null,
        ?object $form = null,
        ?object $video = null
    ) {
        $this->induction = $induction;
        $this->form = $form;
        $this->video = $video;
    }

    public function induction(): ?object
    {
        return $this->induction;
    }

    public function form(): ?object
    {
        return $this->form;
    }

    public function video(): ?object
    {
        return $this->video;
    }

    public function toArray(): array
    {
        return [
            'induction' => $this->induction,
            'form' => $this->form,
            'video' => $this->video
        ];
    }
}
