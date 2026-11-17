<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter\CIEGroup\Values;

final class CIEGroupCIEChapterId
{
    private $value;

    public function __construct(int|null $cieChapterId)
    {
        $this->value = $cieChapterId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
