<?php

declare(strict_types=1);

namespace App\Domain\Employee\Values;

use App\Models\Size;

final class EmployeeSettings
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

    public function endowment(bool $withSize = true): array
    {
        $endowment = $this->value['endowment'] ?? [];
        foreach ($endowment as $key => $item) {
            $item = (object) ($item ?? ['size_id' => null]);
            if ($withSize && $item->size_id) $item->size = Size::find($item->size_id);
            if (!$withSize) unset($item->size);
            $endowment[$key] = $item;
        }
        return $endowment;
    }
}
