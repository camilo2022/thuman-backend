<?php

declare(strict_types=1);

namespace App\Domain\FileType\Contracts;

use App\Domain\FileType\FileType;
use App\Domain\FileType\Values\FileTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface FileTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(FileTypeId $fileTypeId): ?FileType;
}
