<?php

declare(strict_types=1);

namespace App\Application\LicenseUseCase;

use App\Domain\License\Contracts\LicenseContract;
use App\Domain\License\License;
use App\Domain\License\File;
use App\Domain\License\Values\FileExtension;
use App\Domain\License\Values\FileFileSubtypeId;
use App\Domain\License\Values\FileFileTypeId;
use App\Domain\License\Values\FileId;
use App\Domain\License\Values\FileMetadata;
use App\Domain\License\Values\FileMime;
use App\Domain\License\Values\FileModelId;
use App\Domain\License\Values\FileModelType;
use App\Domain\License\Values\FileName;
use App\Domain\License\Values\FilePath;
use App\Domain\License\Values\FileSize;
use App\Domain\License\Values\LicenseLicenseTypeId;
use App\Domain\License\Values\LicenseEndDate;
use App\Domain\License\Values\LicenseId;
use App\Domain\License\Values\LicensePersonId;
use App\Domain\License\Values\LicenseReason;
use App\Domain\License\Values\LicenseStartDate;
use App\Domain\License\Values\LicenseStatus;
use App\Models\License as LicenseModel;
use App\Http\Requests\License\LicenseStoreRequest;
use Illuminate\Support\Facades\Storage;

final class LicenseStoreUseCase
{
    private $licenseContract;

    public function __construct(LicenseContract $licenseContract)
    {
        $this->licenseContract = $licenseContract;
    }

    public function __invoke(LicenseStoreRequest $request): License
    {
        $license = $this->licenseContract->store(
            new License(
                new LicenseId(null),
                new LicensePersonId($request->integer('person_id')),
                new LicenseLicenseTypeId($request->integer('license_type_id')),
                new LicenseStartDate($request->input('start_date')),
                new LicenseEndDate($request->input('end_date')),
                new LicenseReason($request->input('reason')),
                new LicenseStatus(LicenseModel::REVIEW)
            )
        );

        if ($request->hasFile("file.file")) {
            $this->licenseContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($license->id()->value()),
                    new FileModelType(LicenseModel::class),
                    new FileFileTypeId($request->integer("file.file_type_id")),
                    new FileFileSubtypeId($request->integer("file.file_subtype_id")),
                    new FileName(pathinfo($request->file("file.file")->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("License", $request->file("file.file"))),
                    new FileMime($request->file("file.file")->getMimeType()),
                    new FileExtension($request->file("file.file")->getClientOriginalExtension()),
                    new FileSize($request->file("file.file")->getSize()),
                    new FileMetadata([
                        'client_original_name' => $request->file("file.file")->getClientOriginalName(),
                        'client_original_ext' => $request->file("file.file")->getClientOriginalExtension(),
                        'client_mime' => $request->file("file.file")->getClientMimeType(),
                        'mime' => $request->file("file.file")->getMimeType(),
                        'size' => $request->file("file.file")->getSize(),
                        'error' => $request->file("file.file")->getError(),
                        'tmp_name' => $request->file("file.file")->getPathname(),
                        'real_path' => $request->file("file.file")->getRealPath() ?: null,
                        'file_exists' => file_exists($request->file("file.file")->getPathname()),
                        'is_readable' => is_readable($request->file("file.file")->getPathname()),
                        'is_writable' => is_writable($request->file("file.file")->getPathname()),
                        'hash_md5' => @md5_file($request->file("file.file")->getPathname()),
                        'hash_sha1' => @sha1_file($request->file("file.file")->getPathname()),
                        'hash_sha256' => @hash_file('sha256', $request->file("file.file")->getPathname()),
                        'image_info' => ($info = @getimagesize($request->file("file.file")->getPathname())) ?: null,
                        'width' => $info[0] ?? null,
                        'height' => $info[1] ?? null,
                        'orientation' => (isset($info[0], $info[1]))
                                                    ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                    : null,
                        'stat' => ($request->file("file.file")->getPathname() && file_exists($request->file("file.file")->getPathname())) ? @stat($request->file("file.file")->getPathname()) : null,
                    ])
                )
            );
        }

        return $license;
    }
}
