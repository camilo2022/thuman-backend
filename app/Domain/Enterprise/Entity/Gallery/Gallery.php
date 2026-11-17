<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Gallery;

use App\Domain\Enterprise\Entity\Gallery\Values\GalleryCreatedAt;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryDeletedAt;
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
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryUpdatedAt;

final class Gallery
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
        ?GalleryId $id,
        GalleryModelId $modelId,
        GalleryModelType $modelType,
        GalleryFileTypeId $fileTypeId,
        GalleryFileSubtypeId $fileSubtypeId,
        GalleryName $name,
        GalleryPath $path,
        GalleryMime $mime,
        GalleryExtension $extension,
        GallerySize $size,
        GalleryMetadata $metadata,
        ?GalleryCreatedAt $createdAt = null,
        ?GalleryUpdatedAt $updatedAt = null,
        ?GalleryDeletedAt $deletedAt = null
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

    public function id(): ?GalleryId
    {
        return $this->id;
    }

    public function modelId(): GalleryModelId
    {
        return $this->modelId;
    }

    public function modelType(): GalleryModelType
    {
        return $this->modelType;
    }

    public function fileTypeId(): GalleryFileTypeId
    {
        return $this->fileTypeId;
    }

    public function fileSubtypeId(): GalleryFileSubtypeId
    {
        return $this->fileSubtypeId;
    }

    public function name(): GalleryName
    {
        return $this->name;
    }

    public function path(): GalleryPath
    {
        return $this->path;
    }

    public function mime(): GalleryMime
    {
        return $this->mime;
    }

    public function extension(): GalleryExtension
    {
        return $this->extension;
    }

    public function size(): GallerySize
    {
        return $this->size;
    }

    public function metadata(): GalleryMetadata
    {
        return $this->metadata;
    }

    public function createdAt(): ?GalleryCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?GalleryUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?GalleryDeletedAt
    {
        return $this->deletedAt;
    }
}
