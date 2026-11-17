<?php

declare(strict_types=1);

namespace App\Application\HealthEntityAffiliationUseCase;

use App\Domain\HealthEntityAffiliation\Contracts\HealthEntityAffiliationContract;
use App\Domain\HealthEntityAffiliation\File;
use App\Domain\HealthEntityAffiliation\HealthEntityAffiliation;
use App\Domain\HealthEntityAffiliation\Values\FileExtension;
use App\Domain\HealthEntityAffiliation\Values\FileFileSubtypeId;
use App\Domain\HealthEntityAffiliation\Values\FileFileTypeId;
use App\Domain\HealthEntityAffiliation\Values\FileId;
use App\Domain\HealthEntityAffiliation\Values\FileMetadata;
use App\Domain\HealthEntityAffiliation\Values\FileMime;
use App\Domain\HealthEntityAffiliation\Values\FileModelId;
use App\Domain\HealthEntityAffiliation\Values\FileModelType;
use App\Domain\HealthEntityAffiliation\Values\FileName;
use App\Domain\HealthEntityAffiliation\Values\FilePath;
use App\Domain\HealthEntityAffiliation\Values\FileSize;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationCountryId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationHealthEntityId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationObservation;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationPersonId;
use App\Domain\HealthEntityAffiliation\Values\HealthEntityAffiliationStatus;
use App\Models\HealthEntityAffiliation as HealthEntityAffiliationModel;
use App\Http\Requests\HealthEntityAffiliation\HealthEntityAffiliationStoreRequest;
use Illuminate\Support\Facades\Storage;

final class HealthEntityAffiliationStoreUseCase
{
    private $healthEntityAffiliationContract;

    public function __construct(HealthEntityAffiliationContract $healthEntityAffiliationContract)
    {
        $this->healthEntityAffiliationContract = $healthEntityAffiliationContract;
    }

    public function __invoke(HealthEntityAffiliationStoreRequest $request): HealthEntityAffiliation
    {
        $healthEntityAffiliation = $this->healthEntityAffiliationContract->store(
            new HealthEntityAffiliation(
                new HealthEntityAffiliationId(null),
                new HealthEntityAffiliationPersonId($request->integer('person_id')),
                new HealthEntityAffiliationCountryId($request->integer('country_id')),
                new HealthEntityAffiliationHealthEntityId($request->integer('health_entity_id')),
                new HealthEntityAffiliationObservation($request->input('observation')),
                new HealthEntityAffiliationStatus(HealthEntityAffiliationModel::PENDING)
            )
        );

        if ($request->hasFile('file.file')) {
            $this->healthEntityAffiliationContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($healthEntityAffiliation->id()->value()),
                    new FileModelType(HealthEntityAffiliationModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("HealthEntityAffiliation", $request->file('file.file'))),
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

        return $healthEntityAffiliation;
    }
}
