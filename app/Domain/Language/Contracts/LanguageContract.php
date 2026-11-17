<?php

declare(strict_types=1);

namespace App\Domain\Language\Contracts;

use App\Domain\Language\Language;
use App\Domain\Language\Values\LanguageId;
use Illuminate\Pagination\LengthAwarePaginator;

interface LanguageContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(LanguageId $languageId): ?Language;
}
