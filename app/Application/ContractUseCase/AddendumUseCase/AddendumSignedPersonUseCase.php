<?php

declare(strict_types=1);

namespace App\Application\ContractUseCase\AddendumUseCase;

use App\Domain\Contract\Addendum\Contracts\AddendumContract;
use App\Domain\Contract\Addendum\Addendum;
use App\Domain\Contract\Addendum\Image;
use App\Domain\Contract\Addendum\Values\AddendumAddendumTypeId;
use App\Domain\Contract\Addendum\Values\AddendumContractId;
use App\Domain\Contract\Addendum\Values\AddendumContractTypeId;
use App\Domain\Contract\Addendum\Values\AddendumEndDate;
use App\Domain\Contract\Addendum\Values\AddendumFileSubtypesId;
use App\Domain\Contract\Addendum\Values\AddendumHeadquarterId;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Domain\Contract\Addendum\Values\AddendumLegalRepresentativeSignedAt;
use App\Domain\Contract\Addendum\Values\AddendumLegalRepresentativeStatus;
use App\Domain\Contract\Addendum\Values\AddendumPaymentPeriodId;
use App\Domain\Contract\Addendum\Values\AddendumPersonObservation;
use App\Domain\Contract\Addendum\Values\AddendumPersonSignedAt;
use App\Domain\Contract\Addendum\Values\AddendumPersonStatus;
use App\Domain\Contract\Addendum\Values\AddendumPositionId;
use App\Domain\Contract\Addendum\Values\AddendumSalary;
use App\Domain\Contract\Addendum\Values\AddendumSettings;
use App\Domain\Contract\Addendum\Values\AddendumStartDate;
use App\Domain\Contract\Addendum\Values\AddendumUuid;
use App\Domain\Contract\Addendum\Values\ImageExtension;
use App\Domain\Contract\Addendum\Values\ImageFileSubtypeId;
use App\Domain\Contract\Addendum\Values\ImageFileTypeId;
use App\Domain\Contract\Addendum\Values\ImageId;
use App\Domain\Contract\Addendum\Values\ImageMetadata;
use App\Domain\Contract\Addendum\Values\ImageMime;
use App\Domain\Contract\Addendum\Values\ImageModelId;
use App\Domain\Contract\Addendum\Values\ImageModelType;
use App\Domain\Contract\Addendum\Values\ImageName;
use App\Domain\Contract\Addendum\Values\ImagePath;
use App\Domain\Contract\Addendum\Values\ImageSize;
use App\Models\Addendum as AddendumModel;
use App\Http\Requests\Contract\Addendum\AddendumSignedPersonRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

final class ContractSignedPersonUseCase
{
    private $addendumContract;

    public function __construct(AddendumContract $addendumContract)
    {
        $this->addendumContract = $addendumContract;
    }



    public function __invoke(AddendumSignedPersonRequest $request, AddendumId $addendumId): Addendum
    {
        $addendum = $this->addendumContract->update(
            new Addendum(
                new AddendumId($addendumId->value()),
                new AddendumUuid(null),
                new AddendumContractId($request->integer('contract_id')),
                new AddendumAddendumTypeId($request->integer('addendum_type_id')),
                new AddendumHeadquarterId($request->integer('headquarter_id')),
                new AddendumContractTypeId($request->integer('contract_type_id')),
                new AddendumPositionId($request->integer('position_id')),
                new AddendumSalary((float) $request->get('salary')),
                new AddendumPaymentPeriodId($request->integer('payment_period_id')),
                new AddendumStartDate($request->input('start_date')),
                new AddendumEndDate($request->input('end_date')),
                new AddendumPersonObservation($request->input('person_observation')),
                new AddendumPersonSignedAt(Carbon::now()->format('Y-m-d H:i:s')),
                new AddendumPersonStatus(AddendumModel::SIGNED),
                new AddendumLegalRepresentativeSignedAt($request->input('legal_representative_signed_at')),
                new AddendumLegalRepresentativeStatus(AddendumModel::APPROVED),
                new AddendumSettings($request->input('settings')),
                new AddendumFileSubtypesId($request->input('file_subtypes_id'))
            ),
            $addendumId
        );

        if($request->hasFile('signature.file')) {
            $this->addendumContract->image(
                new Image(
                    new ImageId(null),
                    new ImageModelId($addendum->id()->value()),
                    new ImageModelType(AddendumModel::class),
                    new ImageFileTypeId($request->integer('signature.file_type_id')),
                    new ImageFileSubtypeId($request->integer('signature.file_subtype_id')),
                    new ImageName(pathinfo($request->file('signature.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new ImagePath(Storage::disk('public')->putFile("Contract/Addendum", $request->file('signature.file'))),
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

        return $addendum;
    }
}
