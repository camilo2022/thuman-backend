<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\Values;

final class CIEChapterCreatedAt
{
    private $value;

    public function __construct(string|null $createdAt)
    {
        $this->value = $createdAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
