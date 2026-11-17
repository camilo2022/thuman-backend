<?php

declare(strict_types=1);

namespace App\Domain\ProfileCategory\Contracts;

use App\Domain\ProfileCategory\ProfileCategory;
use App\Domain\ProfileCategory\Values\ProfileCategoryId;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProfileCategoryContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(ProfileCategoryId $profileCategoryId): ?ProfileCategory;
}
