<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate\Values;

final class FileFileTypeId
{
    private $value;

    public function __construct(int $fileTypeId)
    {
        $this->value = $fileTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
