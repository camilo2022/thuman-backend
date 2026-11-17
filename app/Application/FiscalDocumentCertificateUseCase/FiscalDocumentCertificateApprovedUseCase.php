<?php

declare(strict_types=1);

namespace App\Application\FiscalDocumentCertificateUseCase;

use App\Domain\FiscalDocumentCertificate\Contracts\FiscalDocumentCertificateContract;
use App\Domain\FiscalDocumentCertificate\FiscalDocumentCertificate;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateCountryId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateFiscalDocumentId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateObservation;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificatePersonId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateStatus;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateApprovedRequest;
use App\Models\FiscalDocumentCertificate as FiscalDocumentCertificateModel;

final class FiscalDocumentCertificateApprovedUseCase
{
    private $fiscalDocumentCertificateContract;

    public function __construct(FiscalDocumentCertificateContract $fiscalDocumentCertificateContract)
    {
        $this->fiscalDocumentCertificateContract = $fiscalDocumentCertificateContract;
    }

    public function __invoke(FiscalDocumentCertificateApprovedRequest $request, FiscalDocumentCertificateId $fiscalDocumentCertificateId): FiscalDocumentCertificate
    {
        return $this->fiscalDocumentCertificateContract->update(
            new FiscalDocumentCertificate(
                new FiscalDocumentCertificateId($fiscalDocumentCertificateId->value()),
                new FiscalDocumentCertificatePersonId($request->integer('person_id')),
                new FiscalDocumentCertificateCountryId($request->integer('country_id')),
                new FiscalDocumentCertificateFiscalDocumentId($request->integer('fisca_document_id')),
                new FiscalDocumentCertificateObservation($request->input('observation')),
                new FiscalDocumentCertificateStatus(FiscalDocumentCertificateModel::APPROVED)
            ),
            $fiscalDocumentCertificateId
        );
    }
}
