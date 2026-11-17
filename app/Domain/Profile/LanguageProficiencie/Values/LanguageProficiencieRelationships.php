<?php

declare(strict_types=1);

namespace App\Domain\Profile\LanguageProficiencie\Values;

final class LanguageProficiencieRelationships
{
    private ?object $profile;
    private ?object $language;
    private ?object $file;

    public function __construct(
        ?object $profile = null,
        ?object $language = null,
        ?object $file = null
    ) {
        $this->profile = $profile;
        $this->language = $language;
        $this->file = $file;
    }

    public function profile(): ?object
    {
        return $this->profile;
    }

    public function language(): ?object
    {
        return $this->language;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'profile' => $this->profile,
            'language' => $this->language,
            'file' => $this->file
        ];
    }
}
