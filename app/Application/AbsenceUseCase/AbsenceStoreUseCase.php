<?php

declare(strict_types=1);

namespace App\Application\AbsenceUseCase;

use App\Domain\Absence\Contracts\AbsenceContract;
use App\Domain\Absence\Absence;
use App\Domain\Absence\File;
use App\Domain\Absence\Values\FileExtension;
use App\Domain\Absence\Values\FileFileSubtypeId;
use App\Domain\Absence\Values\FileFileTypeId;
use App\Domain\Absence\Values\FileId;
use App\Domain\Absence\Values\FileMetadata;
use App\Domain\Absence\Values\FileMime;
use App\Domain\Absence\Values\FileModelId;
use App\Domain\Absence\Values\FileModelType;
use App\Domain\Absence\Values\FileName;
use App\Domain\Absence\Values\FilePath;
use App\Domain\Absence\Values\FileSize;
use App\Domain\Absence\Values\AbsenceAbsenceTypeId;
use App\Domain\Absence\Values\AbsenceCompensation;
use App\Domain\Absence\Values\AbsenceEndDate;
use App\Domain\Absence\Values\AbsenceId;
use App\Domain\Absence\Values\AbsencePersonId;
use App\Domain\Absence\Values\AbsenceReason;
use App\Domain\Absence\Values\AbsenceStartDate;
use App\Domain\Absence\Values\AbsenceStatus;
use App\Models\Absence as AbsenceModel;
use App\Http\Requests\Absence\AbsenceStoreRequest;
use Illuminate\Support\Facades\Storage;

final class AbsenceStoreUseCase
{
    private $absenceContract;

    public function __construct(AbsenceContract $absenceContract)
    {
        $this->absenceContract = $absenceContract;
    }

    public function __invoke(AbsenceStoreRequest $request): Absence
    {
        $absence = $this->absenceContract->store(
            new Absence(
                new AbsenceId(null),
                new AbsencePersonId($request->integer('person_id')),
                new AbsenceAbsenceTypeId($request->integer('absence_type_id')),
                new AbsenceStartDate($request->input('start_date')),
                new AbsenceEndDate($request->input('end_date')),
                new AbsenceReason($request->input('reason')),
                new AbsenceCompensation($request->input('compensation')),
                new AbsenceStatus(AbsenceModel::REVIEW)
            )
        );

        if ($request->hasFile("file.file")) {
            $this->absenceContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($absence->id()->value()),
                    new FileModelType(AbsenceModel::class),
                    new FileFileTypeId($request->integer("file.file_type_id")),
                    new FileFileSubtypeId($request->integer("file.file_subtype_id")),
                    new FileName(pathinfo($request->file("file.file")->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Absence", $request->file("file.file"))),
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
