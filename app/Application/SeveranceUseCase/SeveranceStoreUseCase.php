<?php

declare(strict_types=1);

namespace App\Application\SeveranceUseCase;

use App\Domain\Severance\Contracts\SeveranceContract;
use App\Domain\Severance\Severance;
use App\Domain\Severance\File;
use App\Domain\Severance\Values\FileExtension;
use App\Domain\Severance\Values\FileFileSubtypeId;
use App\Domain\Severance\Values\FileFileTypeId;
use App\Domain\Severance\Values\FileId;
use App\Domain\Severance\Values\FileMetadata;
use App\Domain\Severance\Values\FileMime;
use App\Domain\Severance\Values\FileModelId;
use App\Domain\Severance\Values\FileModelType;
use App\Domain\Severance\Values\FileName;
use App\Domain\Severance\Values\FilePath;
use App\Domain\Severance\Values\FileSize;
use App\Domain\Severance\Values\SeveranceSeveranceTypeId;
use App\Domain\Severance\Values\SeveranceId;
use App\Domain\Severance\Values\SeverancePersonId;
use App\Domain\Severance\Values\SeveranceReason;
use App\Domain\Severance\Values\SeveranceStatus;
use App\Models\Severance as SeveranceModel;
use App\Http\Requests\Severance\SeveranceStoreRequest;
use Illuminate\Support\Facades\Storage;

final class SeveranceStoreUseCase
{
    private $severanceContract;

    public function __construct(SeveranceContract $severanceContract)
    {
        $this->severanceContract = $severanceContract;
    }

    public function __invoke(SeveranceStoreRequest $request): Severance
    {
        $severance = $this->severanceContract->store(
            new Severance(
                new SeveranceId(null),
                new SeverancePersonId($request->integer('person_id')),
                new SeveranceSeveranceTypeId($request->integer('severance_type_id')),
                new SeveranceReason($request->input('reason')),
                new SeveranceStatus(SeveranceModel::REVIEW)
            )
        );

        if ($request->hasFile("file.file")) {
            $this->severanceContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($severance->id()->value()),
                    new FileModelType(SeveranceModel::class),
                    new FileFileTypeId($request->integer("file.file_type_id")),
                    new FileFileSubtypeId($request->integer("file.file_subtype_id")),
                    new FileName(pathinfo($request->file("file.file")->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Severance", $request->file("file.file"))),
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

        return $severance;
    }
}
