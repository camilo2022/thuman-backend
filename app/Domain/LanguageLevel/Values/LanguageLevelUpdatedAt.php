<?php

declare(strict_types=1);

namespace App\Domain\LanguageLevel\Values;

final class LanguageLevelUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
