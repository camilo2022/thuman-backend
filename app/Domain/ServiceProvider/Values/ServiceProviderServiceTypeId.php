<?php

declare(strict_types=1);

namespace App\Domain\ServiceProvider\Values;

final class ServiceProviderServiceTypeId
{
    private $value;

    public function __construct(int|null $serviceTypeId = null)
    {
        $this->value = $serviceTypeId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
