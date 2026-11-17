<?php

declare(strict_types=1);

namespace App\Domain\ServiceProvider\Values;

final class ServiceProviderDescription
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
