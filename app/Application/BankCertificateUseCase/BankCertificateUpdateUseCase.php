<?php

declare(strict_types=1);

namespace App\Application\BankCertificateUseCase;

use App\Domain\BankCertificate\Contracts\BankCertificateContract;
use App\Domain\BankCertificate\BankCertificate;
use App\Domain\BankCertificate\File;
use App\Domain\BankCertificate\Values\BankCertificateAccountNumber;
use App\Domain\BankCertificate\Values\BankCertificateBankAccountTypeId;
use App\Domain\BankCertificate\Values\BankCertificateCountryId;
use App\Domain\BankCertificate\Values\BankCertificateBankId;
use App\Domain\BankCertificate\Values\BankCertificateId;
use App\Domain\BankCertificate\Values\BankCertificateObservation;
use App\Domain\BankCertificate\Values\BankCertificatePersonId;
use App\Domain\BankCertificate\Values\BankCertificateStatus;
use App\Domain\BankCertificate\Values\FileExtension;
use App\Domain\BankCertificate\Values\FileFileSubtypeId;
use App\Domain\BankCertificate\Values\FileFileTypeId;
use App\Domain\BankCertificate\Values\FileId;
use App\Domain\BankCertificate\Values\FileMetadata;
use App\Domain\BankCertificate\Values\FileMime;
use App\Domain\BankCertificate\Values\FileModelId;
use App\Domain\BankCertificate\Values\FileModelType;
use App\Domain\BankCertificate\Values\FileName;
use App\Domain\BankCertificate\Values\FilePath;
use App\Domain\BankCertificate\Values\FileSize;
use App\Http\Requests\BankCertificate\BankCertificateUpdateRequest;
use App\Models\BankCertificate as BankCertificateModel;
use Illuminate\Support\Facades\Storage;

final class BankCertificateUpdateUseCase
{
    private $bankCertificateContract;

    public function __construct(BankCertificateContract $bankCertificateContract)
    {
        $this->bankCertificateContract = $bankCertificateContract;
    }

    public function __invoke(BankCertificateUpdateRequest $request, BankCertificateId $bankCertificateId): BankCertificate
    {
        $bankCertificate = $this->bankCertificateContract->update(
            new BankCertificate(
                new BankCertificateId($bankCertificateId->value()),
                new BankCertificatePersonId($request->integer('person_id')),
                new BankCertificateCountryId($request->integer('country_id')),
                new BankCertificateBankId($request->integer('bank_id')),
                new BankCertificateBankAccountTypeId($request->integer('bank_account_type_id')),
                new BankCertificateAccountNumber($request->input('account_number')),
                new BankCertificateObservation($request->input('observation')),
                new BankCertificateStatus(BankCertificateModel::PENDING)
            ),
            $bankCertificateId
        );

        if ($request->hasFile('file.file')) {
            $this->bankCertificateContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($bankCertificate->id()->value()),
                    new FileModelType(BankCertificateModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("BankCertificate", $request->file('file.file'))),
                    new FileMime($request->file('file.file')->getMimeType()),
                    new FileExtension($request->file('file.file')->getClientOriginalExtension()),
                    new FileSize($request->file('file.file')->getSize()),
                    new FileMetadata([
                        'client_original_name' => $request->file('file.file')->getClientOriginalName(),
                        'client_original_ext' => $request->file('file.file')->getClientOriginalExtension(),
                        'client_mime' => $request->file('file.file')->getClientMimeType(),
                        'mime' => $request->file('file.file')->getMimeType(),
                        'size' => $request->file('file.file')->getSize(),
                        'error' => $request->file('file.file')->getError(),
                        'tmp_name' => $request->file('file.file')->getPathname(),
                        'real_path' => $request->file('file.file')->getRealPath() ?: null,
                        'file_exists' => file_exists($request->file('file.file')->getPathname()),
                        'is_readable' => is_readable($request->file('file.file')->getPathname()),
                        'is_writable' => is_writable($request->file('file.file')->getPathname()),
                        'hash_md5' => @md5_file($request->file('file.file')->getPathname()),
                        'hash_sha1' => @sha1_file($request->file('file.file')->getPathname()),
                        'hash_sha256' => @hash_file('sha256', $request->file('file.file')->getPathname()),
                        'image_info' => ($info = @getimagesize($request->file('file.file')->getPathname())) ?: null,
                        'width' => $info[0] ?? null,
                        'height' => $info[1] ?? null,
                        'orientation' => (isset($info[0], $info[1]))
                                                    ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                    : null,
                        'stat' => ($request->file('file.file')->getPathname() && file_exists($request->file('file.file')->getPathname())) ? @stat($request->file('file.file')->getPathname()) : null,
                    ])
                )
            );
        }

        return $bankCertificate;
    }
}
