<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\Values;

final class CIEChapterDescription
{
    private $value;

    public function __construct(string $description)
    {
        $this->value = $description;
    }

    public function value(): string
    {
        return $this->value;
    }
}
