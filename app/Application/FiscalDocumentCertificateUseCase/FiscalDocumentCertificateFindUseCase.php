<?php

declare(strict_types=1);

namespace App\Application\FiscalDocumentCertificateUseCase;

use App\Domain\FiscalDocumentCertificate\FiscalDocumentCertificate;
use App\Domain\FiscalDocumentCertificate\Contracts\FiscalDocumentCertificateContract;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateFindRequest;

final class FiscalDocumentCertificateFindUseCase
{
    private $fiscalDocumentCertificateContract;

    public function __construct(FiscalDocumentCertificateContract $fiscalDocumentCertificateContract)
    {
        $this->fiscalDocumentCertificateContract = $fiscalDocumentCertificateContract;
    }

    public function __invoke(FiscalDocumentCertificateFindRequest $request, FiscalDocumentCertificateId $fiscalDocumentCertificateId): ?FiscalDocumentCertificate
    {
        return $this->fiscalDocumentCertificateContract->find($request, $fiscalDocumentCertificateId);
    }
}
