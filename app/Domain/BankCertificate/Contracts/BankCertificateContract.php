<?php

declare(strict_types=1);

namespace App\Domain\BankCertificate\Contracts;

use App\Domain\BankCertificate\File;
use App\Domain\BankCertificate\BankCertificate;
use App\Domain\BankCertificate\Values\BankCertificateId;
use App\Http\Requests\BankCertificate\BankCertificateFindRequest;

interface BankCertificateContract
{
    public function find(BankCertificateFindRequest $request, BankCertificateId $bankCertificateId): ?BankCertificate;

    public function store(BankCertificate $bankCertificate): BankCertificate;

    public function update(BankCertificate $bankCertificate, BankCertificateId $bankCertificateId): BankCertificate;

    public function file(File $file): File;
}
