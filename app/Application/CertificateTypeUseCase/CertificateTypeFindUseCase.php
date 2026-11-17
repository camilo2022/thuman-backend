<?php

declare(strict_types=1);

namespace App\Application\CertificateTypeUseCase;

use App\Domain\CertificateType\CertificateType;
use App\Domain\CertificateType\Contracts\CertificateTypeContract;
use App\Domain\CertificateType\Values\CertificateTypeId;

final class CertificateTypeFindUseCase
{
    private $certificateTypeContract;

    public function __construct(CertificateTypeContract $certificateTypeContract)
    {
        $this->certificateTypeContract = $certificateTypeContract;
    }

    public function __invoke(CertificateTypeId $certificateTypeId): ?CertificateType
    {
        return $this->certificateTypeContract->find($certificateTypeId);
    }
}
