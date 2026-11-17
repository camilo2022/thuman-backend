<?php

declare(strict_types=1);

namespace App\Domain\Profile\LanguageProficiencie\Values;

final class LanguageProficiencieLanguageId
{
    private $value;

    public function __construct(int $languageId)
    {
        $this->value = $languageId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
