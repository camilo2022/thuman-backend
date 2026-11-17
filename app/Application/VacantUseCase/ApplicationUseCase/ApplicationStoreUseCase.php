<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase;

use App\Domain\Vacant\Application\Contracts\ApplicationContract;
use App\Domain\Vacant\Application\Application;
use App\Domain\Vacant\Application\Values\ApplicationAnswers;
use App\Domain\Vacant\Application\Values\ApplicationFormId;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Domain\Vacant\Application\Values\ApplicationObservation;
use App\Domain\Vacant\Application\Values\ApplicationPersonId;
use App\Domain\Vacant\Application\Values\ApplicationQuestions;
use App\Domain\Vacant\Application\Values\ApplicationSalary;
use App\Domain\Vacant\Application\Values\ApplicationStatus;
use App\Domain\Vacant\Application\Values\ApplicationVacantId;
use App\Domain\Vacant\Application\Values\VideoExtension;
use App\Domain\Vacant\Application\Values\VideoFileSubtypeId;
use App\Domain\Vacant\Application\Values\VideoFileTypeId;
use App\Domain\Vacant\Application\Values\VideoId;
use App\Domain\Vacant\Application\Values\VideoMetadata;
use App\Domain\Vacant\Application\Values\VideoMime;
use App\Domain\Vacant\Application\Values\VideoModelId;
use App\Domain\Vacant\Application\Values\VideoModelType;
use App\Domain\Vacant\Application\Values\VideoName;
use App\Domain\Vacant\Application\Values\VideoPath;
use App\Domain\Vacant\Application\Values\VideoSize;
use App\Domain\Vacant\Application\Video;
use App\Models\Application as ApplicationModel;
use App\Http\Requests\Vacant\Application\ApplicationStoreRequest;
use Illuminate\Support\Facades\Storage;

final class ApplicationStoreUseCase
{
    private $applicationContract;

    public function __construct(ApplicationContract $applicationContract)
    {
        $this->applicationContract = $applicationContract;
    }

    public function __invoke(ApplicationStoreRequest $request): Application
    {
        $application = $this->applicationContract->store(
            new Application(
                new ApplicationId(null),
                new ApplicationPersonId($request->integer('person_id')),
                new ApplicationVacantId($request->integer('vacant_id')),
                new ApplicationFormId($request->integer('form_id')),
                new ApplicationQuestions($request->input('questions')),
                new ApplicationAnswers($request->input('answers')),
                new ApplicationSalary((float) $request->get('salary')),
                new ApplicationObservation($request->input('observation')),
                new ApplicationStatus(ApplicationModel::PENDING)
            )
        );

        if ($request->hasFile('video.file')) {
            $this->applicationContract->video(
                new Video(
                    new VideoId(null),
                    new VideoModelId($application->id()->value()),
                    new VideoModelType(ApplicationModel::class),
                    new VideoFileTypeId($request->integer('video.file_type_id')),
                    new VideoFileSubtypeId($request->integer('video.file_subtype_id')),
                    new VideoName(pathinfo($request->file('video.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new VideoPath(Storage::disk('public')->putFile("Vacant/Application", $request->file('video.file'))),
                    new VideoMime($request->file('video.file')->getMimeType()),
                    new VideoExtension($request->file('video.file')->getClientOriginalExtension()),
                    new VideoSize($request->file('video.file')->getSize()),
                    new VideoMetadata([
                        'client_original_name' => $request->file('video.file')->getClientOriginalName(),
                        'client_original_ext' => $request->file('video.file')->getClientOriginalExtension(),
                        'client_mime' => $request->file('video.file')->getClientMimeType(),
                        'mime' => $request->file('video.file')->getMimeType(),
                        'size' => $request->file('video.file')->getSize(),
                        'error' => $request->file('video.file')->getError(),
                        'tmp_name' => $request->file('video.file')->getPathname(),
                        'real_path' => $request->file('video.file')->getRealPath() ?: null,
                        'file_exists' => file_exists($request->file('video.file')->getPathname()),
                        'is_readable' => is_readable($request->file('video.file')->getPathname()),
                        'is_writable' => is_writable($request->file('video.file')->getPathname()),
                        'hash_md5' => @md5_file($request->file('video.file')->getPathname()),
                        'hash_sha1' => @sha1_file($request->file('video.file')->getPathname()),
                        'hash_sha256' => @hash_file('sha256', $request->file('video.file')->getPathname()),
                        'stat' => ($request->file('video.file')->getPathname() && file_exists($request->file('video.file')->getPathname())) ? @stat($request->file('video.file')->getPathname()) : null,
                    ])
                )
            );
        }

        return $application;
    }
}
