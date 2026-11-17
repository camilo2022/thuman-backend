<?php

declare(strict_types=1);

namespace App\Application\CompensationFundAffiliationUseCase;

use App\Domain\CompensationFundAffiliation\Contracts\CompensationFundAffiliationContract;
use App\Domain\CompensationFundAffiliation\File;
use App\Domain\CompensationFundAffiliation\CompensationFundAffiliation;
use App\Domain\CompensationFundAffiliation\Values\FileExtension;
use App\Domain\CompensationFundAffiliation\Values\FileFileSubtypeId;
use App\Domain\CompensationFundAffiliation\Values\FileFileTypeId;
use App\Domain\CompensationFundAffiliation\Values\FileId;
use App\Domain\CompensationFundAffiliation\Values\FileMetadata;
use App\Domain\CompensationFundAffiliation\Values\FileMime;
use App\Domain\CompensationFundAffiliation\Values\FileModelId;
use App\Domain\CompensationFundAffiliation\Values\FileModelType;
use App\Domain\CompensationFundAffiliation\Values\FileName;
use App\Domain\CompensationFundAffiliation\Values\FilePath;
use App\Domain\CompensationFundAffiliation\Values\FileSize;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCountryId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationCompensationFundId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationObservation;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationPersonId;
use App\Domain\CompensationFundAffiliation\Values\CompensationFundAffiliationStatus;
use App\Http\Requests\CompensationFundAffiliation\CompensationFundAffiliationStoreRequest;
use App\Models\CompensationFundAffiliation as CompensationFundAffiliationModel;
use Illuminate\Support\Facades\Storage;

final class CompensationFundAffiliationStoreUseCase
{
    private $compensationFundAffiliationContract;

    public function __construct(CompensationFundAffiliationContract $compensationFundAffiliationContract)
    {
        $this->compensationFundAffiliationContract = $compensationFundAffiliationContract;
    }

    public function __invoke(CompensationFundAffiliationStoreRequest $request): CompensationFundAffiliation
    {
        $compensationFundAffiliation = $this->compensationFundAffiliationContract->store(
            new CompensationFundAffiliation(
                new CompensationFundAffiliationId(null),
                new CompensationFundAffiliationPersonId($request->integer('person_id')),
                new CompensationFundAffiliationCountryId($request->integer('country_id')),
                new CompensationFundAffiliationCompensationFundId($request->integer('compensation_fund_id')),
                new CompensationFundAffiliationObservation($request->input('observation')),
                new CompensationFundAffiliationStatus(CompensationFundAffiliationModel::PENDING)
            )
        );

        if ($request->hasFile('file.file')) {
            $this->compensationFundAffiliationContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($compensationFundAffiliation->id()->value()),
                    new FileModelType(CompensationFundAffiliationModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("CompensationFundAffiliation", $request->file('file.file'))),
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

        return $compensationFundAffiliation;
    }
}
