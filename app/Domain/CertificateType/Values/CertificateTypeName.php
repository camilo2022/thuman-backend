<?php

declare(strict_types=1);

namespace App\Domain\CertificateType\Values;

final class CertificateTypeName
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
