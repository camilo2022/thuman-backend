<?php

declare(strict_types=1);

namespace App\Domain\Profile\LanguageProficiencie\Values;

final class LanguageProficiencieLanguageLevelId
{
    private $value;

    public function __construct(int $languageLevelId)
    {
        $this->value = $languageLevelId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
