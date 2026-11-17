<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase;

use App\Domain\Enterprise\Entity\Contracts\EntityContract;
use App\Domain\Enterprise\Entity\Entity;
use App\Domain\Enterprise\Entity\Image;
use App\Domain\Enterprise\Entity\Values\EntityAboutUs;
use App\Domain\Enterprise\Entity\Values\EntityEntityTypeId;
use App\Domain\Enterprise\Entity\Values\EntityCountryId;
use App\Domain\Enterprise\Entity\Values\EntityDocumentNumber;
use App\Domain\Enterprise\Entity\Values\EntityDocumentTypeId;
use App\Domain\Enterprise\Entity\Values\EntityEnterpriseId;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Domain\Enterprise\Entity\Values\EntityMission;
use App\Domain\Enterprise\Entity\Values\EntityName;
use App\Domain\Enterprise\Entity\Values\EntityPersonId;
use App\Domain\Enterprise\Entity\Values\EntitySettings;
use App\Domain\Enterprise\Entity\Values\EntityVision;
use App\Domain\Enterprise\Entity\Values\ImageExtension;
use App\Domain\Enterprise\Entity\Values\ImageFileSubtypeId;
use App\Domain\Enterprise\Entity\Values\ImageFileTypeId;
use App\Domain\Enterprise\Entity\Values\ImageId;
use App\Domain\Enterprise\Entity\Values\ImageMetadata;
use App\Domain\Enterprise\Entity\Values\ImageMime;
use App\Domain\Enterprise\Entity\Values\ImageModelId;
use App\Domain\Enterprise\Entity\Values\ImageModelType;
use App\Domain\Enterprise\Entity\Values\ImageName;
use App\Domain\Enterprise\Entity\Values\ImagePath;
use App\Domain\Enterprise\Entity\Values\ImageSize;
use App\Models\Entity as EntityModel;
use App\Http\Requests\Enterprise\Entity\EntityProfileRequest;
use Illuminate\Support\Facades\Storage;

final class EntityProfileUseCase
{
    private $entityContract;

    public function __construct(EntityContract $entityContract)
    {
        $this->entityContract = $entityContract;
    }

    public function __invoke(EntityProfileRequest $request, EntityId $entityId): Entity
    {
        $entity = $this->entityContract->update(
            new Entity(
                new EntityId($entityId->value()),
                new EntityEnterpriseId($request->integer('enterprise_id')),
                new EntityEntityTypeId($request->integer('entity_type_id')),
                new EntityName($request->input('name')),
                new EntityCountryId($request->integer('country_id')),
                new EntityDocumentTypeId($request->integer('document_type_id')),
                new EntityDocumentNumber($request->input('document_number')),
                new EntityPersonId($request->input('person_id')),
                new EntityAboutUs($request->input('about_us.text')),
                new EntityMission($request->input('mission.text')),
                new EntityVision($request->input('vision.text')),
                new EntitySettings($request->input('settings'))
            ),
            $entityId
        );

        foreach (['banner', 'about_us', 'mission', 'vision'] as $key) {
            if ($request->hasFile("$key.image")) {
                $this->entityContract->image(
                    new Image(
                        new ImageId(null),
                        new ImageModelId($entity->id()->value()),
                        new ImageModelType(EntityModel::class),
                        new ImageFileTypeId($request->integer("$key.file_type_id")),
                        new ImageFileSubtypeId($request->integer("$key.file_subtype_id")),
                        new ImageName(pathinfo($request->file("$key.image")->getClientOriginalName(), PATHINFO_FILENAME)),
                        new ImagePath(Storage::disk('public')->putFile("Enterprise/Entity", $request->file("$key.image"))),
                        new ImageMime($request->file("$key.image")->getMimeType()),
                        new ImageExtension($request->file("$key.image")->getClientOriginalExtension()),
                        new ImageSize($request->file("$key.image")->getSize()),
                        new ImageMetadata([
                            'client_original_name' => $request->file("$key.image")->getClientOriginalName(),
                            'client_original_ext' => $request->file("$key.image")->getClientOriginalExtension(),
                            'client_mime' => $request->file("$key.image")->getClientMimeType(),
                            'mime' => $request->file("$key.image")->getMimeType(),
                            'size' => $request->file("$key.image")->getSize(),
                            'error' => $request->file("$key.image")->getError(),
                            'tmp_name' => $request->file("$key.image")->getPathname(),
                            'real_path' => $request->file("$key.image")->getRealPath() ?: null,
                            'file_exists' => file_exists($request->file("$key.image")->getPathname()),
                            'is_readable' => is_readable($request->file("$key.image")->getPathname()),
                            'is_writable' => is_writable($request->file("$key.image")->getPathname()),
                            'hash_md5' => @md5_file($request->file("$key.image")->getPathname()),
                            'hash_sha1' => @sha1_file($request->file("$key.image")->getPathname()),
                            'hash_sha256' => @hash_file('sha256', $request->file("$key.image")->getPathname()),
                            'image_info' => ($info = @getimagesize($request->file("$key.image")->getPathname())) ?: null,
                            'width' => $info[0] ?? null,
                            'height' => $info[1] ?? null,
                            'orientation' => (isset($info[0], $info[1]))
                                                        ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                        : null,
                            'stat' => ($request->file("$key.image")->getPathname() && file_exists($request->file("$key.image")->getPathname())) ? @stat($request->file("$key.image")->getPathname()) : null,
                        ])
                    )
                );
            }
        }

        return $entity;
    }
}
