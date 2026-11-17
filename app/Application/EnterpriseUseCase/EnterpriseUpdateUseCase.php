<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase;

use App\Domain\Enterprise\Contracts\EnterpriseContract;
use App\Domain\Enterprise\Enterprise;
use App\Domain\Enterprise\Logo;
use App\Domain\Enterprise\Values\EnterpriseDescription;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Domain\Enterprise\Values\EnterpriseName;
use App\Domain\Enterprise\Values\LogoExtension;
use App\Domain\Enterprise\Values\LogoFileSubtypeId;
use App\Domain\Enterprise\Values\LogoFileTypeId;
use App\Domain\Enterprise\Values\LogoId;
use App\Domain\Enterprise\Values\LogoMetadata;
use App\Domain\Enterprise\Values\LogoMime;
use App\Domain\Enterprise\Values\LogoModelId;
use App\Domain\Enterprise\Values\LogoModelType;
use App\Domain\Enterprise\Values\LogoName;
use App\Domain\Enterprise\Values\LogoPath;
use App\Domain\Enterprise\Values\LogoSize;
use App\Http\Requests\Enterprise\EnterpriseUpdateRequest;
use App\Models\Enterprise as EnterpriseModel;
use App\Http\Requests\Enterprise\EnterpriseStoreRequest;
use Illuminate\Support\Facades\Storage;

final class EnterpriseUpdateUseCase
{
    private $enterpriseContract;

    public function __construct(EnterpriseContract $enterpriseContract)
    {
        $this->enterpriseContract = $enterpriseContract;
    }

    public function __invoke(EnterpriseUpdateRequest $request, EnterpriseId $enterpriseId): Enterprise
    {
        $enterprise = $this->enterpriseContract->update(
            new Enterprise(
                new EnterpriseId($enterpriseId->value()),
                new EnterpriseName($request->input('name')),
                new EnterpriseDescription($request->input('description'))
            ),
            $enterpriseId
        );

        if($request->hasFile('logo')) {
            $this->enterpriseContract->logo(
                new Logo(
                    new LogoId(null),
                    new LogoModelId($enterprise->id()->value()),
                    new LogoModelType(EnterpriseModel::class),
                    new LogoFileTypeId($request->integer('file_type_id')),
                    new LogoFileSubtypeId($request->integer('file_subtype_id')),
                    new LogoName(pathinfo($request->file('logo')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new LogoPath(Storage::disk('public')->putFile("Enterprise", $request->file('logo'))),
                    new LogoMime($request->file('logo')->getMimeType()),
                    new LogoExtension($request->file('logo')->getClientOriginalExtension()),
                    new LogoSize($request->file('logo')->getSize()),
                    new LogoMetadata([
                        'client_original_name' => $request->file('logo')->getClientOriginalName(),
                        'client_original_ext' => $request->file('logo')->getClientOriginalExtension(),
                        'client_mime' => $request->file('logo')->getClientMimeType(),
                        'mime' => $request->file('logo')->getMimeType(),
                        'size' => $request->file('logo')->getSize(),
                        'error' => $request->file('logo')->getError(),
                        'tmp_name' => $request->file('logo')->getPathname(),
                        'real_path' => $request->file('logo')->getRealPath() ?: null,
                        'file_exists' => file_exists($request->file('logo')->getPathname()),
                        'is_readable' => is_readable($request->file('logo')->getPathname()),
                        'is_writable' => is_writable($request->file('logo')->getPathname()),
                        'hash_md5' => @md5_file($request->file('logo')->getPathname()),
                        'hash_sha1' => @sha1_file($request->file('logo')->getPathname()),
                        'hash_sha256' => @hash_file('sha256', $request->file('logo')->getPathname()),
                        'image_info' => ($info = @getimagesize($request->file('logo')->getPathname())) ?: null,
                        'width' => $info[0] ?? null,
                        'height' => $info[1] ?? null,
                        'orientation' => (isset($info[0], $info[1]))
                                                    ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                    : null,
                        'stat' => ($request->file('logo')->getPathname() && file_exists($request->file('logo')->getPathname())) ? @stat($request->file('logo')->getPathname()) : null,
                    ])
                )
            );
        }

        return $enterprise;
    }
}
