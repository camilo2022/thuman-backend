<?php

declare(strict_types=1);

namespace App\Application\FiscalDocumentCertificateUseCase;

use App\Domain\FiscalDocumentCertificate\Contracts\FiscalDocumentCertificateContract;
use App\Domain\FiscalDocumentCertificate\File;
use App\Domain\FiscalDocumentCertificate\FiscalDocumentCertificate;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateCountryId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateFiscalDocumentId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificatePersonId;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateStatus;
use App\Domain\FiscalDocumentCertificate\Values\FileExtension;
use App\Domain\FiscalDocumentCertificate\Values\FileFileSubtypeId;
use App\Domain\FiscalDocumentCertificate\Values\FileFileTypeId;
use App\Domain\FiscalDocumentCertificate\Values\FileId;
use App\Domain\FiscalDocumentCertificate\Values\FileMetadata;
use App\Domain\FiscalDocumentCertificate\Values\FileMime;
use App\Domain\FiscalDocumentCertificate\Values\FileModelId;
use App\Domain\FiscalDocumentCertificate\Values\FileModelType;
use App\Domain\FiscalDocumentCertificate\Values\FileName;
use App\Domain\FiscalDocumentCertificate\Values\FilePath;
use App\Domain\FiscalDocumentCertificate\Values\FileSize;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateObservation;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateStoreRequest;
use App\Models\FiscalDocumentCertificate as FiscalDocumentCertificateModel;
use Illuminate\Support\Facades\Storage;

final class FiscalDocumentCertificateStoreUseCase
{
    private $fiscalDocumentCertificateContract;

    public function __construct(FiscalDocumentCertificateContract $fiscalDocumentCertificateContract)
    {
        $this->fiscalDocumentCertificateContract = $fiscalDocumentCertificateContract;
    }

    public function __invoke(FiscalDocumentCertificateStoreRequest $request): FiscalDocumentCertificate
    {
        $fiscalDocumentCertificate = $this->fiscalDocumentCertificateContract->store(
            new FiscalDocumentCertificate(
                new FiscalDocumentCertificateId(null),
                new FiscalDocumentCertificatePersonId($request->integer('person_id')),
                new FiscalDocumentCertificateCountryId($request->integer('country_id')),
                new FiscalDocumentCertificateFiscalDocumentId($request->integer('fiscal_document_id')),
                new FiscalDocumentCertificateObservation($request->input('observation')),
                new FiscalDocumentCertificateStatus(FiscalDocumentCertificateModel::PENDING)
            )
        );

        if ($request->hasFile('file.file')) {
            $this->fiscalDocumentCertificateContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($fiscalDocumentCertificate->id()->value()),
                    new FileModelType(FiscalDocumentCertificateModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("FiscalDocumentCertificate", $request->file('file.file'))),
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

        return $fiscalDocumentCertificate;
    }
}
