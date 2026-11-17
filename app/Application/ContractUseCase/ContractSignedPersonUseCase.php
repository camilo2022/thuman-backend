<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase;

use App\Domain\Contract\Contracts\ContractContract;
use App\Domain\Contract\Contract;
use App\Domain\Contract\Image;
use App\Domain\Contract\Values\ContractApplicationId;
use App\Domain\Contract\Values\ContractContractTypeId;
use App\Domain\Contract\Values\ContractEndDate;
use App\Domain\Contract\Values\ContractFileSubtypesId;
use App\Domain\Contract\Values\ContractHeadquarterId;
use App\Domain\Contract\Values\ContractId;
use App\Domain\Contract\Values\ContractLegalRepresentativeSignedAt;
use App\Domain\Contract\Values\ContractLegalRepresentativeStatus;
use App\Domain\Contract\Values\ContractPaymentPeriodId;
use App\Domain\Contract\Values\ContractPersonId;
use App\Domain\Contract\Values\ContractPersonObservation;
use App\Domain\Contract\Values\ContractPersonSignedAt;
use App\Domain\Contract\Values\ContractPersonStatus;
use App\Domain\Contract\Values\ContractPositionId;
use App\Domain\Contract\Values\ContractSalary;
use App\Domain\Contract\Values\ContractSettings;
use App\Domain\Contract\Values\ContractStartDate;
use App\Domain\Contract\Values\ContractTransportAllowance;
use App\Domain\Contract\Values\ContractUuid;
use App\Domain\Contract\Values\ImageExtension;
use App\Domain\Contract\Values\ImageFileSubtypeId;
use App\Domain\Contract\Values\ImageFileTypeId;
use App\Domain\Contract\Values\ImageId;
use App\Domain\Contract\Values\ImageMetadata;
use App\Domain\Contract\Values\ImageMime;
use App\Domain\Contract\Values\ImageModelId;
use App\Domain\Contract\Values\ImageModelType;
use App\Domain\Contract\Values\ImageName;
use App\Domain\Contract\Values\ImagePath;
use App\Domain\Contract\Values\ImageSize;
use App\Models\Contract as ContractModel;
use App\Http\Requests\Contract\ContractSignedPersonRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

final class ContractSignedPersonUseCase
{
    private $contractContract;

    public function __construct(ContractContract $contractContract)
    {
        $this->contractContract = $contractContract;
    }



    public function __invoke(ContractSignedPersonRequest $request, ContractId $contractId): Contract
    {
        $contract = $this->contractContract->update(
            new Contract(
                new ContractId($contractId->value()),
                new ContractUuid(null),
                new ContractApplicationId($request->integer('application_id')),
                new ContractPersonId($request->integer('person_id')),
                new ContractHeadquarterId($request->integer('headquarter_id')),
                new ContractContractTypeId($request->integer('contract_type_id')),
                new ContractPositionId($request->integer('position_id')),
                new ContractSalary((float) $request->get('salary')),
                new ContractTransportAllowance((float) $request->get('transport_allowance')),
                new ContractPaymentPeriodId($request->integer('payment_period_id')),
                new ContractStartDate($request->input('start_date')),
                new ContractEndDate($request->input('end_date')),
                new ContractPersonObservation($request->input('person_observation')),
                new ContractPersonSignedAt(Carbon::now()->format('Y-m-d H:i:s')),
                new ContractPersonStatus(ContractModel::SIGNED),
                new ContractLegalRepresentativeSignedAt($request->input('legal_representative_signed_at')),
                new ContractLegalRepresentativeStatus(ContractModel::APPROVED),
                new ContractSettings($request->input('settings')),
                new ContractFileSubtypesId($request->input('file_subtypes_id'))
            ),
            $contractId
        );

        if($request->hasFile('signature.file')) {
            $this->contractContract->image(
                new Image(
                    new ImageId(null),
                    new ImageModelId($contract->id()->value()),
                    new ImageModelType(ContractModel::class),
                    new ImageFileTypeId($request->integer('signature.file_type_id')),
                    new ImageFileSubtypeId($request->integer('signature.file_subtype_id')),
                    new ImageName(pathinfo($request->file('signature.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new ImagePath(Storage::disk('public')->putFile("Contract", $request->file('signature.file'))),
                    new ImageMime($request->file('signature.file')->getMimeType()),
                    new ImageExtension($request->file('signature.file')->getClientOriginalExtension()),
                    new ImageSize($request->file('signature.file')->getSize()),
                    new ImageMetadata([
                        'client_original_name' => $request->file('signature.file')->getClientOriginalName(),
                        'client_original_ext' => $request->file('signature.file')->getClientOriginalExtension(),
                        'client_mime' => $request->file('signature.file')->getClientMimeType(),
                        'mime' => $request->file('signature.file')->getMimeType(),
                        'size' => $request->file('signature.file')->getSize(),
                        'error' => $request->file('signature.file')->getError(),
                        'tmp_name' => $request->file('signature.file')->getPathname(),
                        'real_path' => $request->file('signature.file')->getRealPath() ?: null,
                        'file_exists' => file_exists($request->file('signature.file')->getPathname()),
                        'is_readable' => is_readable($request->file('signature.file')->getPathname()),
                        'is_writable' => is_writable($request->file('signature.file')->getPathname()),
                        'hash_md5' => @md5_file($request->file('signature.file')->getPathname()),
                        'hash_sha1' => @sha1_file($request->file('signature.file')->getPathname()),
                        'hash_sha256' => @hash_file('sha256', $request->file('signature.file')->getPathname()),
                        'image_info' => ($info = @getimagesize($request->file('signature.file')->getPathname())) ?: null,
                        'width' => $info[0] ?? null,
                        'height' => $info[1] ?? null,
                        'orientation' => (isset($info[0], $info[1]))
                                                    ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                    : null,
                        'stat' => ($request->file('signature.file')->getPathname() && file_exists($request->file('signature.file')->getPathname())) ? @stat($request->file('signature.file')->getPathname()) : null,
                    ])
                )
            );
        }

        return $contract;
    }
}
