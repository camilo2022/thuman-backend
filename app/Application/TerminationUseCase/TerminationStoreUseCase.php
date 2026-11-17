<?php

declare(strict_types=1);

namespace App\Application\TerminationUseCase;

use App\Domain\Termination\Contracts\TerminationContract;
use App\Domain\Termination\Termination;
use App\Domain\Termination\File;
use App\Domain\Termination\Values\FileExtension;
use App\Domain\Termination\Values\FileFileSubtypeId;
use App\Domain\Termination\Values\FileFileTypeId;
use App\Domain\Termination\Values\FileId;
use App\Domain\Termination\Values\FileMetadata;
use App\Domain\Termination\Values\FileMime;
use App\Domain\Termination\Values\FileModelId;
use App\Domain\Termination\Values\FileModelType;
use App\Domain\Termination\Values\FileName;
use App\Domain\Termination\Values\FilePath;
use App\Domain\Termination\Values\FileSize;
use App\Domain\Termination\Values\TerminationDate;
use App\Domain\Termination\Values\TerminationDecision;
use App\Domain\Termination\Values\TerminationId;
use App\Domain\Termination\Values\TerminationModelId;
use App\Domain\Termination\Values\TerminationModelType;
use App\Domain\Termination\Values\TerminationPersonId;
use App\Domain\Termination\Values\TerminationReason;
use App\Domain\Termination\Values\TerminationStatus;
use App\Models\Termination as TerminationModel;
use App\Http\Requests\Termination\TerminationStoreRequest;
use Illuminate\Support\Facades\Storage;

final class TerminationStoreUseCase
{
    private $terminationContract;

    public function __construct(TerminationContract $terminationContract)
    {
        $this->terminationContract = $terminationContract;
    }

    public function __invoke(TerminationStoreRequest $request): Termination
    {
        $termination = $this->terminationContract->store(
            new Termination(
                new TerminationId(null),
                new TerminationPersonId($request->integer('person_id')),
                new TerminationModelId($request->integer('model_id')),
                new TerminationModelType($request->input('model_type')),
                new TerminationReason($request->input('reason')),
                new TerminationDecision($request->input('decision')),
                new TerminationDate($request->input('date')),
                new TerminationStatus(TerminationModel::REVIEW)
            )
        );

        if ($request->hasFile("file.file")) {
            $this->terminationContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($termination->id()->value()),
                    new FileModelType(TerminationModel::class),
                    new FileFileTypeId($request->integer("file.file_type_id")),
                    new FileFileSubtypeId($request->integer("file.file_subtype_id")),
                    new FileName(pathinfo($request->file("file.file")->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Termination", $request->file("file.file"))),
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

        return $termination;
    }
}
