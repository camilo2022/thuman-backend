<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\ProfessionalExperienceUseCase;

use App\Domain\Profile\ProfessionalExperience\Contracts\ProfessionalExperienceContract;
use App\Domain\Profile\ProfessionalExperience\File;
use App\Domain\Profile\ProfessionalExperience\ProfessionalExperience;
use App\Domain\Profile\ProfessionalExperience\Values\FileExtension;
use App\Domain\Profile\ProfessionalExperience\Values\FileFileSubtypeId;
use App\Domain\Profile\ProfessionalExperience\Values\FileFileTypeId;
use App\Domain\Profile\ProfessionalExperience\Values\FileId;
use App\Domain\Profile\ProfessionalExperience\Values\FileMetadata;
use App\Domain\Profile\ProfessionalExperience\Values\FileMime;
use App\Domain\Profile\ProfessionalExperience\Values\FileModelId;
use App\Domain\Profile\ProfessionalExperience\Values\FileModelType;
use App\Domain\Profile\ProfessionalExperience\Values\FileName;
use App\Domain\Profile\ProfessionalExperience\Values\FilePath;
use App\Domain\Profile\ProfessionalExperience\Values\FileSize;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCityId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCompanyAddress;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceCompanyName;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceEndDate;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceWorkArrangementId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperiencePosition;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperiencePositionDescription;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceProfileId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceStartDate;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorDialingCountryId;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorName;
use App\Domain\Profile\ProfessionalExperience\Values\ProfessionalExperienceSupervisorPhoneNumber;
use App\Models\ProfessionalExperience as ProfessionalExperienceModel;
use App\Http\Requests\Profile\ProfessionalExperience\ProfessionalExperienceStoreRequest;
use Illuminate\Support\Facades\Storage;

final class ProfessionalExperienceStoreUseCase
{
    private $professionalExperienceContract;

    public function __construct(ProfessionalExperienceContract $professionalExperienceContract)
    {
        $this->professionalExperienceContract = $professionalExperienceContract;
    }

    public function __invoke(ProfessionalExperienceStoreRequest $request): ProfessionalExperience
    {
        $professionalExperience = $this->professionalExperienceContract->store(
            new ProfessionalExperience(
                new ProfessionalExperienceId(null),
                new ProfessionalExperienceProfileId($request->integer('profile_id')),
                new ProfessionalExperienceCompanyName($request->input('company_name')),
                new ProfessionalExperienceSupervisorName($request->input('supervisor_name')),
                new ProfessionalExperienceSupervisorDialingCountryId($request->integer('supervisor_dialing_country_id')),
                new ProfessionalExperienceSupervisorPhoneNumber($request->input('supervisor_phone_number')),
                new ProfessionalExperienceCityId($request->integer('city_id')),
                new ProfessionalExperienceCompanyAddress($request->input('company_address')),
                new ProfessionalExperienceStartDate($request->input('start_date')),
                new ProfessionalExperienceEndDate($request->input('end_date')),
                new ProfessionalExperiencePosition($request->input('position')),
                new ProfessionalExperienceWorkArrangementId($request->integer('work_arrangement_id')),
                new ProfessionalExperiencePositionDescription($request->input('position_description'))
            )
        );

        if ($request->hasFile('file.file')) {
            $this->professionalExperienceContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($professionalExperience->id()->value()),
                    new FileModelType(ProfessionalExperienceModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Profile/ProfessionalExperience", $request->file('file.file'))),
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

        return $professionalExperience;
    }
}
