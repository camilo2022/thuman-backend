<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\AcademicQualificationUseCase;

use App\Domain\Profile\AcademicQualification\Contracts\AcademicQualificationContract;
use App\Domain\Profile\AcademicQualification\AcademicQualification;
use App\Domain\Profile\AcademicQualification\File;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicInstitution;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicLevelId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationAcademicTitle;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationCityId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationEndDate;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationFormationStatusId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationProfileId;
use App\Domain\Profile\AcademicQualification\Values\AcademicQualificationStartDate;
use App\Domain\Profile\AcademicQualification\Values\FileExtension;
use App\Domain\Profile\AcademicQualification\Values\FileFileSubtypeId;
use App\Domain\Profile\AcademicQualification\Values\FileFileTypeId;
use App\Domain\Profile\AcademicQualification\Values\FileId;
use App\Domain\Profile\AcademicQualification\Values\FileMetadata;
use App\Domain\Profile\AcademicQualification\Values\FileMime;
use App\Domain\Profile\AcademicQualification\Values\FileModelId;
use App\Domain\Profile\AcademicQualification\Values\FileModelType;
use App\Domain\Profile\AcademicQualification\Values\FileName;
use App\Domain\Profile\AcademicQualification\Values\FilePath;
use App\Domain\Profile\AcademicQualification\Values\FileSize;
use App\Models\AcademicQualification as AcademicQualificationModel;
use App\Http\Requests\Profile\AcademicQualification\AcademicQualificationStoreRequest;
use Illuminate\Support\Facades\Storage;

final class AcademicQualificationStoreUseCase
{
    private $academicQualificationContract;

    public function __construct(AcademicQualificationContract $academicQualificationContract)
    {
        $this->academicQualificationContract = $academicQualificationContract;
    }

    public function __invoke(AcademicQualificationStoreRequest $request): AcademicQualification
    {
        $academicQualification = $this->academicQualificationContract->store(
            new AcademicQualification(
                new AcademicQualificationId(null),
                new AcademicQualificationProfileId($request->integer('profile_id')),
                new AcademicQualificationCityId($request->integer('city_id')),
                new AcademicQualificationAcademicLevelId($request->integer('academic_level_id')),
                new AcademicQualificationAcademicInstitution($request->input('academic_institution')),
                new AcademicQualificationAcademicTitle($request->input('academic_title')),
                new AcademicQualificationFormationStatusId($request->integer('formation_status_id')),
                new AcademicQualificationStartDate($request->input('start_date')),
                new AcademicQualificationEndDate($request->input('end_date'))
            )
        );

        if ($request->hasFile('file.file')) {
            $this->academicQualificationContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($academicQualification->id()->value()),
                    new FileModelType(AcademicQualificationModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Profile/AcademicQualification", $request->file('file.file'))),
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

        return $academicQualification;
    }
}
