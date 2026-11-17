<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumSettings
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

    public function templates(): array
    {
        return $this->value['templates'] ?? [];
    }
}
