<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\ExamMedicalUseCase;

use App\Domain\Vacant\Application\ExamMedical\Contracts\ExamMedicalContract;
use App\Domain\Vacant\Application\ExamMedical\ExamMedical;
use App\Domain\Vacant\Application\ExamMedical\File;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalAddress;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalApplicationId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalCityId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalDateTime;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalDescription;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalEpidemiologicalProgramId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalHealthEntityId;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalIndication;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalNeighborhood;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalResult;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalStatus;
use App\Domain\Vacant\Application\ExamMedical\Values\FileExtension;
use App\Domain\Vacant\Application\ExamMedical\Values\FileFileSubtypeId;
use App\Domain\Vacant\Application\ExamMedical\Values\FileFileTypeId;
use App\Domain\Vacant\Application\ExamMedical\Values\FileId;
use App\Domain\Vacant\Application\ExamMedical\Values\FileMetadata;
use App\Domain\Vacant\Application\ExamMedical\Values\FileMime;
use App\Domain\Vacant\Application\ExamMedical\Values\FileModelId;
use App\Domain\Vacant\Application\ExamMedical\Values\FileModelType;
use App\Domain\Vacant\Application\ExamMedical\Values\FileName;
use App\Domain\Vacant\Application\ExamMedical\Values\FilePath;
use App\Domain\Vacant\Application\ExamMedical\Values\FileSize;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalReviewerRequest;
use App\Models\ExamMedical as ExamMedicalModel;
use Illuminate\Support\Facades\Storage;

final class ExamMedicalReviewerUseCase
{
    private $examMedicalContract;

    public function __construct(ExamMedicalContract $examMedicalContract)
    {
        $this->examMedicalContract = $examMedicalContract;
    }

    public function __invoke(ExamMedicalReviewerRequest $request, ExamMedicalId $examMedicalId): ExamMedical
    {
        $examMedical = $this->examMedicalContract->update(
            new ExamMedical(
                new ExamMedicalId(null),
                new ExamMedicalApplicationId($request->integer('application_id')),
                new ExamMedicalDateTime($request->input('date_time')),
                new ExamMedicalCityId($request->integer('city_id')),
                new ExamMedicalHealthEntityId($request->integer('health_entity_id')),
                new ExamMedicalAddress($request->input('address')),
                new ExamMedicalNeighborhood($request->input('neighborhood')),
                new ExamMedicalResult($request->input('result')),
                new ExamMedicalEpidemiologicalProgramId($request->integer('epidemiological_program_id')),
                new ExamMedicalDescription($request->input('description')),
                new ExamMedicalIndication($request->input('indication')),
                new ExamMedicalStatus(ExamMedicalModel::COMPLETED)
            ),
            $examMedicalId
        );

        if ($request->hasFile('file.file')) {
            $this->examMedicalContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($examMedical->id()->value()),
                    new FileModelType(ExamMedicalModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Vacant/Application/ExamMedical", $request->file('file.file'))),
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

        return $examMedical;
    }
}
