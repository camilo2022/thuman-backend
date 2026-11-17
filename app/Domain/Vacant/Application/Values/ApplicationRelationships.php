<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class ApplicationRelationships
{
    private ?object $person;
    private ?object $vacant;
    private ?object $form;
    private ?object $video_interview;

    public function __construct(
        ?object $person = null,
        ?object $vacant = null,
        ?object $form = null,
        ?object $video_interview = null
    ) {
        $this->person = $person;
        $this->vacant = $vacant;
        $this->form = $form;
        $this->video_interview = $video_interview;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function vacant(): ?object
    {
        return $this->vacant;
    }

    public function form(): ?object
    {
        return $this->form;
    }

    public function videoInterview(): ?object
    {
        return $this->video_interview;
    }

    public function toArray(): array
    {
        return [
            'person' => $this->person,
            'vacant' => $this->vacant,
            'form' => $this->form,
            'video_interview' => $this->video_interview
        ];
    }
}
