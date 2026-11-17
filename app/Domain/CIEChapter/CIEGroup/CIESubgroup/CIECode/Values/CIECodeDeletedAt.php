<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values;

final class CIECodeDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
