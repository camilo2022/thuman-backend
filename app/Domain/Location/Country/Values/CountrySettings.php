<?php

declare(strict_types=1);

namespace App\Domain\Location\Country\Values;

final class CountrySettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function validations(): array
    {
        $validations = $this->value['validations'] ?? [];
        return array_map(function ($validation, $index) {
            $validation = (object) $validation;
            $validation->id = $index + 1;

            return $validation;
        }, $validations, array_keys($validations));
    }
}
