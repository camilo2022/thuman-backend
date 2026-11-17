<?php

declare(strict_types=1);

namespace App\Domain\AbsenceType\Values;

use App\Models\Country;

final class AbsenceTypeSettings
{
    private $value;

    public function __construct(object|array|null $settings)
    {
        if (is_null($settings)) {
            $settings = [];
        }

        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function compensationEnabled(): bool
    {
        return $this->value['compensation_enabled'] ?? false;
    }

    public function fileRequired(): bool
    {
        return $this->value['file_required'] ?? true;
    }

    public function message(): string
    {
        return $this->value['message'] ?? '';
    }

    public function validations(bool $withCountry = true): array
    {
        $validations = $this->value['validations'] ?? [];
        return array_map(function ($validation, $index) use ($withCountry) {
            $validation = (object) $validation;
            $validation->id = $index + 1;
            if (isset($validation->country_id)) {
                $validation->country = Country::find($validation->country_id);
            }

            if(!$withCountry) unset($validation->country);

            return $validation;
        }, $validations, array_keys($validations));
    }
}
