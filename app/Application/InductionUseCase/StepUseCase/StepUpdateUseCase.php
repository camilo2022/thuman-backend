<?php

declare(strict_types=1);

namespace App\Application\InductionUseCase\StepUseCase;

use App\Domain\Induction\Step\Contracts\StepContract;
use App\Domain\Induction\Step\File;
use App\Domain\Induction\Step\Step;
use App\Domain\Induction\Step\Values\FileExtension;
use App\Domain\Induction\Step\Values\FileFileSubtypeId;
use App\Domain\Induction\Step\Values\FileFileTypeId;
use App\Domain\Induction\Step\Values\FileId;
use App\Domain\Induction\Step\Values\FileMetadata;
use App\Domain\Induction\Step\Values\FileMime;
use App\Domain\Induction\Step\Values\FileModelId;
use App\Domain\Induction\Step\Values\FileModelType;
use App\Domain\Induction\Step\Values\FileName;
use App\Domain\Induction\Step\Values\FilePath;
use App\Domain\Induction\Step\Values\FileSize;
use App\Domain\Induction\Step\Values\StepDescription;
use App\Domain\Induction\Step\Values\StepFormId;
use App\Domain\Induction\Step\Values\StepId;
use App\Domain\Induction\Step\Values\StepInductionId;
use App\Domain\Induction\Step\Values\StepName;
use App\Models\Step as StepModel;
use App\Http\Requests\Induction\Step\StepUpdateRequest;
use Illuminate\Support\Facades\Storage;

final class StepUpdateUseCase
{
    private $stepContract;

    public function __construct(StepContract $stepContract)
    {
        $this->stepContract = $stepContract;
    }

    public function __invoke(StepUpdateRequest $request, StepId $stepId): Step
    {
        $step = $this->stepContract->update(
            new Step(
                new StepId($stepId->value()),
                new StepInductionId($request->integer('induction_id')),
                new StepName($request->input('name')),
                new StepDescription($request->input('description')),
                new StepFormId($request->integer('form_id'))
            ),
            $stepId
        );

        if ($request->hasFile("video.file")) {
            $this->stepContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($step->id()->value()),
                    new FileModelType(StepModel::class),
                    new FileFileTypeId($request->integer("video.file_type_id")),
                    new FileFileSubtypeId($request->integer("video.file_subtype_id")),
                    new FileName(pathinfo($request->file("video.file")->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Induction/Step", $request->file("video.file"))),
                    new FileMime($request->file("video.file")->getMimeType()),
                    new FileExtension($request->file("video.file")->getClientOriginalExtension()),
                    new FileSize($request->file("video.file")->getSize()),
                    new FileMetadata([
                        'client_original_name' => $request->file("video.file")->getClientOriginalName(),
                        'client_original_ext' => $request->file("video.file")->getClientOriginalExtension(),
                        'client_mime' => $request->file("video.file")->getClientMimeType(),
                        'mime' => $request->file("video.file")->getMimeType(),
                        'size' => $request->file("video.file")->getSize(),
                        'error' => $request->file("video.file")->getError(),
                        'tmp_name' => $request->file("video.file")->getPathname(),
                        'real_path' => $request->file("video.file")->getRealPath() ?: null,
                        'file_exists' => file_exists($request->file("video.file")->getPathname()),
                        'is_readable' => is_readable($request->file("video.file")->getPathname()),
                        'is_writable' => is_writable($request->file("video.file")->getPathname()),
                        'hash_md5' => @md5_file($request->file("video.file")->getPathname()),
                        'hash_sha1' => @sha1_file($request->file("video.file")->getPathname()),
                        'hash_sha256' => @hash_file('sha256', $request->file("video.file")->getPathname()),
                        'image_info' => ($info = @getimagesize($request->file("video.file")->getPathname())) ?: null,
                        'width' => $info[0] ?? null,
                        'height' => $info[1] ?? null,
                        'orientation' => (isset($info[0], $info[1]))
                                                    ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                    : null,
                        'stat' => ($request->file("video.file")->getPathname() && file_exists($request->file("video.file")->getPathname())) ? @stat($request->file("video.file")->getPathname()) : null,
                    ])
                )
            );
        }

        return $step;
    }
}
