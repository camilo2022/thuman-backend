<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocumentCertificate\Contracts;

use App\Domain\FiscalDocumentCertificate\File;
use App\Domain\FiscalDocumentCertificate\FiscalDocumentCertificate;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateFindRequest;

interface FiscalDocumentCertificateContract
{
    public function find(FiscalDocumentCertificateFindRequest $request, FiscalDocumentCertificateId $fiscalDocumentCertificateId): ?FiscalDocumentCertificate;

    public function store(FiscalDocumentCertificate $fiscalDocumentCertificate): FiscalDocumentCertificate;

    public function update(FiscalDocumentCertificate $fiscalDocumentCertificate, FiscalDocumentCertificateId $fiscalDocumentCertificateId): FiscalDocumentCertificate;

    public function file(File $file): File;
}
