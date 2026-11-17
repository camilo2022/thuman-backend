<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\Values;

final class CIEGroupName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
