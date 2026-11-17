<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\GalleryUseCase;

use App\Domain\Enterprise\Entity\Gallery\Contracts\GalleryContract;
use App\Domain\Enterprise\Entity\Gallery\Gallery;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryExtension;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryFileSubtypeId;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryFileTypeId;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryId;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryMetadata;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryMime;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryModelId;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryModelType;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryName;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryPath;
use App\Domain\Enterprise\Entity\Gallery\Values\GallerySize;
use App\Http\Requests\Enterprise\Entity\Gallery\GalleryStoreRequest;
use App\Models\Entity as EntityModel;
use Illuminate\Support\Facades\Storage;

final class GalleryStoreUseCase
{
    private $galleryContract;

    public function __construct(GalleryContract $galleryContract)
    {
        $this->galleryContract = $galleryContract;
    }

    public function __invoke(GalleryStoreRequest $request): Gallery
    {
        return $this->galleryContract->store(
            new Gallery(
                new GalleryId(null),
                new GalleryModelId($request->integer('entity_id')),
                new GalleryModelType(EntityModel::class),
                new GalleryFileTypeId($request->integer('file_type_id')),
                new GalleryFileSubtypeId($request->integer('file_subtype_id')),
                new GalleryName(pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME)),
                new GalleryPath(Storage::disk('public')->putFile("Enterprise/Entity/Gallery", $request->file('file'))),
                new GalleryMime($request->file('file')->getMimeType()),
                new GalleryExtension($request->file('file')->getClientOriginalExtension()),
                new GallerySize($request->file('file')->getSize()),
                new GalleryMetadata([
                    'client_original_name' => $request->file('file')->getClientOriginalName(),
                    'client_original_ext' => $request->file('file')->getClientOriginalExtension(),
                    'client_mime' => $request->file('file')->getClientMimeType(),
                    'mime' => $request->file('file')->getMimeType(),
                    'size' => $request->file('file')->getSize(),
                    'error' => $request->file('file')->getError(),
                    'tmp_name' => $request->file('file')->getPathname(),
                    'real_path' => $request->file('file')->getRealPath() ?: null,
                    'file_exists' => file_exists($request->file('file')->getPathname()),
                    'is_readable' => is_readable($request->file('file')->getPathname()),
                    'is_writable' => is_writable($request->file('file')->getPathname()),
                    'hash_md5' => @md5_file($request->file('file')->getPathname()),
                    'hash_sha1' => @sha1_file($request->file('file')->getPathname()),
                    'hash_sha256' => @hash_file('sha256', $request->file('file')->getPathname()),
                    'image_info' => ($info = @getimagesize($request->file('file')->getPathname())) ?: null,
                    'width' => $info[0] ?? null,
                    'height' => $info[1] ?? null,
                    'orientation' => (isset($info[0], $info[1]))
                                                ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                : null,
                    'stat' => ($request->file('file')->getPathname() && file_exists($request->file('file')->getPathname())) ? @stat($request->file('file')->getPathname()) : null,
                ])
            )
        );
    }
}
