<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\CriminalRecordUseCase;

use App\Domain\Vacant\Application\CriminalRecord\Contracts\CriminalRecordContract;
use App\Domain\Vacant\Application\CriminalRecord\CriminalRecord;
use App\Domain\Vacant\Application\CriminalRecord\File;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordId;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordApplicationId;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordObservation;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordStatus;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileExtension;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileFileSubtypeId;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileFileTypeId;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileId;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileMetadata;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileMime;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileModelId;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileModelType;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileName;
use App\Domain\Vacant\Application\CriminalRecord\Values\FilePath;
use App\Domain\Vacant\Application\CriminalRecord\Values\FileSize;
use App\Http\Requests\Vacant\Application\CriminalRecord\CriminalRecordStoreRequest;
use App\Models\CriminalRecord as CriminalRecordModel;
use Illuminate\Support\Facades\Storage;

final class CriminalRecordStoreUseCase
{
    private $criminalRecordContract;

    public function __construct(CriminalRecordContract $criminalRecordContract)
    {
        $this->criminalRecordContract = $criminalRecordContract;
    }

    public function __invoke(CriminalRecordStoreRequest $request): CriminalRecord
    {
        $criminalRecord = $this->criminalRecordContract->store(
            new CriminalRecord(
                new CriminalRecordId(null),
                new CriminalRecordApplicationId($request->integer('application_id')),
                new CriminalRecordObservation($request->input('observation')),
                new CriminalRecordStatus($request->input('status'))
            )
        );

        if ($request->hasFile('file.file')) {
            $this->criminalRecordContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($criminalRecord->id()->value()),
                    new FileModelType(CriminalRecordModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Vacant/Application/CriminalRecord", $request->file('file.file'))),
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

        return $criminalRecord;
    }
}
