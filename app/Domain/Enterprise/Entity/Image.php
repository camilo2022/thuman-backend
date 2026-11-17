<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity;

use App\Domain\Enterprise\Entity\Values\ImageCreatedAt;
use App\Domain\Enterprise\Entity\Values\ImageDeletedAt;
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
use App\Domain\Enterprise\Entity\Values\ImageUpdatedAt;

final class Image
{
    private $id;
    private $modelId;
    private $modelType;
    private $fileTypeId;
    private $fileSubtypeId;
    private $name;
    private $path;
    private $mime;
    private $extension;
    private $size;
    private $metadata;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function __construct(
        ?ImageId $id,
        ImageModelId $modelId,
        ImageModelType $modelType,
        ImageFileTypeId $fileTypeId,
        ImageFileSubtypeId $fileSubtypeId,
        ImageName $name,
        ImagePath $path,
        ImageMime $mime,
        ImageExtension $extension,
        ImageSize $size,
        ImageMetadata $metadata,
        ?ImageCreatedAt $createdAt = null,
        ?ImageUpdatedAt $updatedAt = null,
        ?ImageDeletedAt $deletedAt = null
    )
    {
        $this->id = $id;
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->fileTypeId = $fileTypeId;
        $this->fileSubtypeId = $fileSubtypeId;
        $this->name = $name;
        $this->path = $path;
        $this->mime = $mime;
        $this->extension = $extension;
        $this->size = $size;
        $this->metadata = $metadata;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function id(): ?ImageId
    {
        return $this->id;
    }

    public function modelId(): ImageModelId
    {
        return $this->modelId;
    }

    public function modelType(): ImageModelType
    {
        return $this->modelType;
    }

    public function fileTypeId(): ImageFileTypeId
    {
        return $this->fileTypeId;
    }

    public function fileSubtypeId(): ImageFileSubtypeId
    {
        return $this->fileSubtypeId;
    }

    public function name(): ImageName
    {
        return $this->name;
    }

    public function path(): ImagePath
    {
        return $this->path;
    }

    public function mime(): ImageMime
    {
        return $this->mime;
    }

    public function extension(): ImageExtension
    {
        return $this->extension;
    }

    public function size(): ImageSize
    {
        return $this->size;
    }

    public function metadata(): ImageMetadata
    {
        return $this->metadata;
    }

    public function createdAt(): ?ImageCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ImageUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ImageDeletedAt
    {
        return $this->deletedAt;
    }
}
