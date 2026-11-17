<?php

declare(strict_types=1);

namespace App\Application\BankCertificateUseCase;

use App\Domain\BankCertificate\Contracts\BankCertificateContract;
use App\Domain\BankCertificate\BankCertificate;
use App\Domain\BankCertificate\Values\BankCertificateAccountNumber;
use App\Domain\BankCertificate\Values\BankCertificateBankAccountTypeId;
use App\Domain\BankCertificate\Values\BankCertificateCountryId;
use App\Domain\BankCertificate\Values\BankCertificateBankId;
use App\Domain\BankCertificate\Values\BankCertificateId;
use App\Domain\BankCertificate\Values\BankCertificateObservation;
use App\Domain\BankCertificate\Values\BankCertificatePersonId;
use App\Domain\BankCertificate\Values\BankCertificateStatus;
use App\Http\Requests\BankCertificate\BankCertificateRefreshedRequest;
use App\Models\BankCertificate as BankCertificateModel;

final class BankCertificateRefreshedUseCase
{
    private $bankCertificateContract;

    public function __construct(BankCertificateContract $bankCertificateContract)
    {
        $this->bankCertificateContract = $bankCertificateContract;
    }

    public function __invoke(BankCertificateRefreshedRequest $request, BankCertificateId $bankCertificateId): BankCertificate
    {
        return $this->bankCertificateContract->update(
            new BankCertificate(
                new BankCertificateId($bankCertificateId->value()),
                new BankCertificatePersonId($request->integer('person_id')),
                new BankCertificateCountryId($request->integer('country_id')),
                new BankCertificateBankId($request->integer('bank_id')),
                new BankCertificateBankAccountTypeId($request->integer('bank_account_type_id')),
                new BankCertificateAccountNumber($request->input('account_number')),
                new BankCertificateObservation($request->input('observation')),
                new BankCertificateStatus(BankCertificateModel::REFRESHED)
            ),
            $bankCertificateId
        );
    }
}
