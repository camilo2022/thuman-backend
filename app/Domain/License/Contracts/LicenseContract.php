<?php

declare(strict_types=1);

namespace App\Domain\License\Contracts;

use App\Domain\License\License;
use App\Domain\License\File;
use App\Domain\License\Values\LicenseId;
use App\Http\Requests\License\LicenseAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface LicenseContract
{
    public function all(string|null $search, int|null $perPage, LicenseAllRequest $request): LengthAwarePaginator;

    public function find(LicenseId $licenseId): ?License;

    public function store(License $license): License;

    public function update(License $license, LicenseId $licenseId): License;

    public function file(File $file): File;
}
