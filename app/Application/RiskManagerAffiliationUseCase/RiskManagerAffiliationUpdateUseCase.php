<?php

declare(strict_types=1);

namespace App\Application\RiskManagerAffiliationUseCase;

use App\Domain\RiskManagerAffiliation\Contracts\RiskManagerAffiliationContract;
use App\Domain\RiskManagerAffiliation\File;
use App\Domain\RiskManagerAffiliation\RiskManagerAffiliation;
use App\Domain\RiskManagerAffiliation\Values\FileExtension;
use App\Domain\RiskManagerAffiliation\Values\FileFileSubtypeId;
use App\Domain\RiskManagerAffiliation\Values\FileFileTypeId;
use App\Domain\RiskManagerAffiliation\Values\FileId;
use App\Domain\RiskManagerAffiliation\Values\FileMetadata;
use App\Domain\RiskManagerAffiliation\Values\FileMime;
use App\Domain\RiskManagerAffiliation\Values\FileModelId;
use App\Domain\RiskManagerAffiliation\Values\FileModelType;
use App\Domain\RiskManagerAffiliation\Values\FileName;
use App\Domain\RiskManagerAffiliation\Values\FilePath;
use App\Domain\RiskManagerAffiliation\Values\FileSize;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationCountryId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationRiskManagerId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationObservation;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationPersonId;
use App\Domain\RiskManagerAffiliation\Values\RiskManagerAffiliationStatus;
use App\Http\Requests\RiskManagerAffiliation\RiskManagerAffiliationUpdateRequest;
use App\Models\RiskManagerAffiliation as RiskManagerAffiliationModel;
use Illuminate\Support\Facades\Storage;

final class RiskManagerAffiliationUpdateUseCase
{
    private $riskManagerAffiliationContract;

    public function __construct(RiskManagerAffiliationContract $riskManagerAffiliationContract)
    {
        $this->riskManagerAffiliationContract = $riskManagerAffiliationContract;
    }

    public function __invoke(RiskManagerAffiliationUpdateRequest $request, RiskManagerAffiliationId $riskManagerAffiliationId): RiskManagerAffiliation
    {
        $riskManagerAffiliation = $this->riskManagerAffiliationContract->update(
            new RiskManagerAffiliation(
                new RiskManagerAffiliationId($riskManagerAffiliationId->value()),
                new RiskManagerAffiliationPersonId($request->integer('person_id')),
                new RiskManagerAffiliationCountryId($request->integer('country_id')),
                new RiskManagerAffiliationRiskManagerId($request->integer('risk_manager_id')),
                new RiskManagerAffiliationObservation($request->input('observation')),
                new RiskManagerAffiliationStatus(RiskManagerAffiliationModel::PENDING)
            ),
            $riskManagerAffiliationId
        );

        if ($request->hasFile('file.file')) {
            $this->riskManagerAffiliationContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($riskManagerAffiliation->id()->value()),
                    new FileModelType(RiskManagerAffiliationModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("RiskManagerAffiliation", $request->file('file.file'))),
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

        return $riskManagerAffiliation;
    }
}
