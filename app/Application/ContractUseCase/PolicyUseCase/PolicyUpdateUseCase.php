<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase\PolicyUseCase;

use App\Domain\Contract\Policy\Contracts\PolicyContract;
use App\Domain\Contract\Policy\File;
use App\Domain\Contract\Policy\Policy;
use App\Domain\Contract\Policy\Values\FileExtension;
use App\Domain\Contract\Policy\Values\FileFileSubtypeId;
use App\Domain\Contract\Policy\Values\FileFileTypeId;
use App\Domain\Contract\Policy\Values\FileId;
use App\Domain\Contract\Policy\Values\FileMetadata;
use App\Domain\Contract\Policy\Values\FileMime;
use App\Domain\Contract\Policy\Values\FileModelId;
use App\Domain\Contract\Policy\Values\FileModelType;
use App\Domain\Contract\Policy\Values\FileName;
use App\Domain\Contract\Policy\Values\FilePath;
use App\Domain\Contract\Policy\Values\FileSize;
use App\Domain\Contract\Policy\Values\PolicyCode;
use App\Domain\Contract\Policy\Values\PolicyEndDate;
use App\Domain\Contract\Policy\Values\PolicyId;
use App\Domain\Contract\Policy\Values\PolicyInsuredValue;
use App\Domain\Contract\Policy\Values\PolicyModelId;
use App\Domain\Contract\Policy\Values\PolicyModelType;
use App\Domain\Contract\Policy\Values\PolicyPolicyTypeId;
use App\Domain\Contract\Policy\Values\PolicyRiskManagerId;
use App\Domain\Contract\Policy\Values\PolicyStartDate;
use App\Domain\Contract\Policy\Values\PolicyStatus;
use App\Domain\Contract\Policy\Values\PolicyUuid;
use App\Domain\Contract\Policy\Values\PolicyValue;
use App\Models\Policy as PolicyModel;
use App\Http\Requests\Contract\Policy\PolicyUpdateRequest;
use Illuminate\Support\Facades\Storage;

final class PolicyUpdateUseCase
{
    private $policyContract;

    public function __construct(PolicyContract $policyContract)
    {
        $this->policyContract = $policyContract;
    }

    public function __invoke(PolicyUpdateRequest $request, PolicyId $policyId): Policy
    {
        $policy = $this->policyContract->update(
            new Policy(
                new PolicyId($policyId->value()),
                new PolicyUuid(null),
                new PolicyModelId($request->integer('model_id')),
                new PolicyModelType($request->input('model_type')),
                new PolicyRiskManagerId($request->integer('risk_manager_id')),
                new PolicyPolicyTypeId($request->integer('policy_type_id')),
                new PolicyCode($request->input('code')),
                new PolicyValue((float) $request->get('value')),
                new PolicyInsuredValue((float) $request->get('insured_value')),
                new PolicyStartDate($request->input('start_date')),
                new PolicyEndDate($request->input('end_date')),
                new PolicyStatus(PolicyModel::REVIEW)
            ),
            $policyId
        );

        if($request->hasFile('file.file')) {
            $this->policyContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($policy->id()->value()),
                    new FileModelType(PolicyModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Contract/Policy", $request->file('file.file'))),
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

        return $policy;
    }
}
