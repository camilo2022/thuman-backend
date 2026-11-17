<?php

declare(strict_types=1);

namespace App\Domain\Contract\Values;

final class ContractSettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        if (is_null($settings)) {
            $settings = [];
        }

        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): object|array
    {
        return $this->value;
    }

    public function acceptTerms(): bool
    {
        return $this->value['accept_terms'] ?? false;
    }

    public function acceptPrivacyPolicy(): bool
    {
        return $this->value['accept_privacy_policy'] ?? false;
    }

    public function documents(): array
    {
        return $this->value['documents'] ?? [];
    }
}
