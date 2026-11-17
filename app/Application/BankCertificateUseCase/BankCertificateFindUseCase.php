<?php

declare(strict_types=1);

namespace App\Application\BankCertificateUseCase;

use App\Domain\BankCertificate\BankCertificate;
use App\Domain\BankCertificate\Contracts\BankCertificateContract;
use App\Domain\BankCertificate\Values\BankCertificateId;
use App\Http\Requests\BankCertificate\BankCertificateFindRequest;

final class BankCertificateFindUseCase
{
    private $bankCertificateContract;

    public function __construct(BankCertificateContract $bankCertificateContract)
    {
        $this->bankCertificateContract = $bankCertificateContract;
    }

    public function __invoke(BankCertificateFindRequest $request, BankCertificateId $bankCertificateId): ?BankCertificate
    {
        return $this->bankCertificateContract->find($request, $bankCertificateId);
    }
}
