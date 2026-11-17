<?php

declare(strict_types=1);

namespace App\Domain\PensionFundAffiliation\Values;

final class FileMime
{
    private $value;

    public function __construct(string $mime)
    {
        $this->value = $mime;
    }

    public function value(): string
    {
        return $this->value;
    }
}
