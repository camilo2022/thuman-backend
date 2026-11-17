<?php

declare(strict_types=1);

namespace App\Domain\CertificateType\Contracts;

use App\Domain\CertificateType\CertificateType;
use App\Domain\CertificateType\Values\CertificateTypeId;
use Illuminate\Pagination\LengthAwarePaginator;

interface CertificateTypeContract
{
    public function all(string|null $search, int|null $perPage): LengthAwarePaginator;

    public function find(CertificateTypeId $certificateTypeId): ?CertificateType;
}
