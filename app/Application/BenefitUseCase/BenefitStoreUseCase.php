<?php

declare(strict_types=1);

namespace App\Application\BenefitUseCase;

use App\Domain\Benefit\Contracts\BenefitContract;
use App\Domain\Benefit\Benefit;
use App\Domain\Benefit\Icon;
use App\Domain\Benefit\Values\BenefitDescription;
use App\Domain\Benefit\Values\BenefitId;
use App\Domain\Benefit\Values\BenefitItemId;
use App\Domain\Benefit\Values\BenefitName;
use App\Domain\Benefit\Values\BenefitSettings;
use App\Domain\Benefit\Values\IconExtension;
use App\Domain\Benefit\Values\IconFileSubtypeId;
use App\Domain\Benefit\Values\IconFileTypeId;
use App\Domain\Benefit\Values\IconId;
use App\Domain\Benefit\Values\IconMetadata;
use App\Domain\Benefit\Values\IconMime;
use App\Domain\Benefit\Values\IconModelId;
use App\Domain\Benefit\Values\IconModelType;
use App\Domain\Benefit\Values\IconName;
use App\Domain\Benefit\Values\IconPath;
use App\Domain\Benefit\Values\IconSize;
use App\Models\Benefit as BenefitModel;
use App\Http\Requests\Benefit\BenefitStoreRequest;
use Illuminate\Support\Facades\Storage;

final class BenefitStoreUseCase
{
    private $benefitContract;

    public function __construct(BenefitContract $benefitContract)
    {
        $this->benefitContract = $benefitContract;
    }

    public function __invoke(BenefitStoreRequest $request): Benefit
    {
        $benefit = $this->benefitContract->store(
            new Benefit(
                new BenefitId(null),
                new BenefitItemId($request->integer('item_id')),
                new BenefitName($request->input('name')),
                new BenefitDescription($request->input('description')),
                new BenefitSettings((object) [])
            )
        );

        $this->benefitContract->icon(
            new Icon(
                new IconId(null),
                new IconModelId($benefit->id()->value()),
                new IconModelType(BenefitModel::class),
                new IconFileTypeId($request->integer('file_type_id')),
                new IconFileSubtypeId($request->integer('file_subtype_id')),
                new IconName(pathinfo($request->file('icon')->getClientOriginalName(), PATHINFO_FILENAME)),
                new IconPath(Storage::disk('public')->putFile("Benefit", $request->file('icon'))),
                new IconMime($request->file('icon')->getMimeType()),
                new IconExtension($request->file('icon')->getClientOriginalExtension()),
                new IconSize($request->file('icon')->getSize()),
                new IconMetadata([
                    'client_original_name' => $request->file('icon')->getClientOriginalName(),
                    'client_original_ext' => $request->file('icon')->getClientOriginalExtension(),
                    'client_mime' => $request->file('icon')->getClientMimeType(),
                    'mime' => $request->file('icon')->getMimeType(),
                    'size' => $request->file('icon')->getSize(),
                    'error' => $request->file('icon')->getError(),
                    'tmp_name' => $request->file('icon')->getPathname(),
                    'real_path' => $request->file('icon')->getRealPath() ?: null,
                    'file_exists' => file_exists($request->file('icon')->getPathname()),
                    'is_readable' => is_readable($request->file('icon')->getPathname()),
                    'is_writable' => is_writable($request->file('icon')->getPathname()),
                    'hash_md5' => @md5_file($request->file('icon')->getPathname()),
                    'hash_sha1' => @sha1_file($request->file('icon')->getPathname()),
                    'hash_sha256' => @hash_file('sha256', $request->file('icon')->getPathname()),
                    'image_info' => ($info = @getimagesize($request->file('icon')->getPathname())) ?: null,
                    'width' => $info[0] ?? null,
                    'height' => $info[1] ?? null,
                    'orientation' => (isset($info[0], $info[1]))
                                                ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                : null,
                    'stat' => ($request->file('icon')->getPathname() && file_exists($request->file('icon')->getPathname())) ? @stat($request->file('icon')->getPathname()) : null,
                ])
            )
        );

        return $benefit;
    }
}
