<?php

declare(strict_types=1);

namespace App\Application\PensionFundAffiliationUseCase;

use App\Domain\PensionFundAffiliation\Contracts\PensionFundAffiliationContract;
use App\Domain\PensionFundAffiliation\File;
use App\Domain\PensionFundAffiliation\PensionFundAffiliation;
use App\Domain\PensionFundAffiliation\Values\FileExtension;
use App\Domain\PensionFundAffiliation\Values\FileFileSubtypeId;
use App\Domain\PensionFundAffiliation\Values\FileFileTypeId;
use App\Domain\PensionFundAffiliation\Values\FileId;
use App\Domain\PensionFundAffiliation\Values\FileMetadata;
use App\Domain\PensionFundAffiliation\Values\FileMime;
use App\Domain\PensionFundAffiliation\Values\FileModelId;
use App\Domain\PensionFundAffiliation\Values\FileModelType;
use App\Domain\PensionFundAffiliation\Values\FileName;
use App\Domain\PensionFundAffiliation\Values\FilePath;
use App\Domain\PensionFundAffiliation\Values\FileSize;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationCountryId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationPensionFundId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationObservation;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationPersonId;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationStatus;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationStoreRequest;
use App\Models\PensionFundAffiliation as PensionFundAffiliationModel;
use Illuminate\Support\Facades\Storage;

final class PensionFundAffiliationStoreUseCase
{
    private $pensionFundAffiliationContract;

    public function __construct(PensionFundAffiliationContract $pensionFundAffiliationContract)
    {
        $this->pensionFundAffiliationContract = $pensionFundAffiliationContract;
    }

    public function __invoke(PensionFundAffiliationStoreRequest $request): PensionFundAffiliation
    {
        $pensionFundAffiliation = $this->pensionFundAffiliationContract->store(
            new PensionFundAffiliation(
                new PensionFundAffiliationId(null),
                new PensionFundAffiliationPersonId($request->integer('person_id')),
                new PensionFundAffiliationCountryId($request->integer('country_id')),
                new PensionFundAffiliationPensionFundId($request->integer('pension_fund_id')),
                new PensionFundAffiliationObservation($request->input('observation')),
                new PensionFundAffiliationStatus(PensionFundAffiliationModel::PENDING)
            )
        );

        if ($request->hasFile('file.file')) {
            $this->pensionFundAffiliationContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($pensionFundAffiliation->id()->value()),
                    new FileModelType(PensionFundAffiliationModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("PensionFundAffiliation", $request->file('file.file'))),
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

        return $pensionFundAffiliation;
    }
}
