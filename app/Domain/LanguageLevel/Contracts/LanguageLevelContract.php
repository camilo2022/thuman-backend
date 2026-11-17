<?php

declare(strict_types=1);

namespace App\Domain\LanguageLevel\Contracts;

use App\Domain\LanguageLevel\LanguageLevel;
use App\Domain\LanguageLevel\Values\LanguageLevelId;
use Illuminate\Pagination\LengthAwarePaginator;

interface LanguageLevelContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(LanguageLevelId $languageLevelId): ?LanguageLevel;
}
