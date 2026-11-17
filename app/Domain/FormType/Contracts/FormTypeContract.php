<?php

declare(strict_types=1);

namespace App\Domain\FormType\Contracts;

use App\Domain\FormType\FormType;
use App\Domain\FormType\Values\FormTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface FormTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(FormTypeId $formTypeId): ?FormType;
}
