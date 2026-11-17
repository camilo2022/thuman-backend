<?php

declare(strict_types=1);

namespace App\Application\DisabilityUseCase;

use App\Domain\Disability\Contracts\DisabilityContract;
use App\Domain\Disability\Disability;
use App\Domain\Disability\File;
use App\Domain\Disability\Values\DisabilityCIECodeId;
use App\Domain\Disability\Values\FileExtension;
use App\Domain\Disability\Values\FileFileSubtypeId;
use App\Domain\Disability\Values\FileFileTypeId;
use App\Domain\Disability\Values\FileId;
use App\Domain\Disability\Values\FileMetadata;
use App\Domain\Disability\Values\FileMime;
use App\Domain\Disability\Values\FileModelId;
use App\Domain\Disability\Values\FileModelType;
use App\Domain\Disability\Values\FileName;
use App\Domain\Disability\Values\FilePath;
use App\Domain\Disability\Values\FileSize;
use App\Domain\Disability\Values\DisabilityDisabilityTypeId;
use App\Domain\Disability\Values\DisabilityEndDate;
use App\Domain\Disability\Values\DisabilityId;
use App\Domain\Disability\Values\DisabilityPersonId;
use App\Domain\Disability\Values\DisabilityReason;
use App\Domain\Disability\Values\DisabilityStartDate;
use App\Domain\Disability\Values\DisabilityStatus;
use App\Models\Disability as DisabilityModel;
use App\Http\Requests\Disability\DisabilityStoreRequest;
use Illuminate\Support\Facades\Storage;

final class DisabilityStoreUseCase
{
    private $absenceContract;

    public function __construct(DisabilityContract $absenceContract)
    {
        $this->absenceContract = $absenceContract;
    }

    public function __invoke(DisabilityStoreRequest $request): Disability
    {
        $absence = $this->absenceContract->store(
            new Disability(
                new DisabilityId(null),
                new DisabilityPersonId($request->integer('person_id')),
                new DisabilityDisabilityTypeId($request->integer('disability_type_id')),
                new DisabilityCIECodeId($request->integer('cie_code_id')),
                new DisabilityStartDate($request->input('start_date')),
                new DisabilityEndDate($request->input('end_date')),
                new DisabilityReason($request->input('reason')),
                new DisabilityStatus(DisabilityModel::REVIEW)
            )
        );

        if ($request->hasFile("file.file")) {
            $this->absenceContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($absence->id()->value()),
                    new FileModelType(DisabilityModel::class),
                    new FileFileTypeId($request->integer("file.file_type_id")),
                    new FileFileSubtypeId($request->integer("file.file_subtype_id")),
                    new FileName(pathinfo($request->file("file.file")->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Disability", $request->file("file.file"))),
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

        return $absence;
    }
}
