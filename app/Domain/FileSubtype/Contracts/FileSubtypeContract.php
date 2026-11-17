<?php

declare(strict_types=1);

namespace App\Domain\FileSubtype\Contracts;

use App\Domain\FileSubtype\FileSubtype;
use App\Domain\FileSubtype\Values\FileSubtypeId;
use App\Http\Requests\FileSubtype\FileSubtypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface FileSubtypeContract
{
    public function all(string|null $search, int|null $perPage, FileSubtypeAllRequest $request): LengthAwarePaginator;

    public function find(FileSubtypeId $fileSubtypeId): ?FileSubtype;
}
