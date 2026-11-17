<?php

declare(strict_types=1);

namespace App\Domain\License;

use App\Domain\License\Values\FileCreatedAt;
use App\Domain\License\Values\FileDeletedAt;
use App\Domain\License\Values\FileExtension;
use App\Domain\License\Values\FileFileSubtypeId;
use App\Domain\License\Values\FileFileTypeId;
use App\Domain\License\Values\FileId;
use App\Domain\License\Values\FileMetadata;
use App\Domain\License\Values\FileMime;
use App\Domain\License\Values\FileModelId;
use App\Domain\License\Values\FileModelType;
use App\Domain\License\Values\FileName;
use App\Domain\License\Values\FilePath;
use App\Domain\License\Values\FileSize;
use App\Domain\License\Values\FileUpdatedAt;

final class File
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
        ?FileId $id,
        FileModelId $modelId,
        FileModelType $modelType,
        FileFileTypeId $fileTypeId,
        FileFileSubtypeId $fileSubtypeId,
        FileName $name,
        FilePath $path,
        FileMime $mime,
        FileExtension $extension,
        FileSize $size,
        FileMetadata $metadata,
        ?FileCreatedAt $createdAt = null,
        ?FileUpdatedAt $updatedAt = null,
        ?FileDeletedAt $deletedAt = null
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

    public function id(): ?FileId
    {
        return $this->id;
    }

    public function modelId(): FileModelId
    {
        return $this->modelId;
    }

    public function modelType(): FileModelType
    {
        return $this->modelType;
    }

    public function fileTypeId(): FileFileTypeId
    {
        return $this->fileTypeId;
    }

    public function fileSubtypeId(): FileFileSubtypeId
    {
        return $this->fileSubtypeId;
    }

    public function name(): FileName
    {
        return $this->name;
    }

    public function path(): FilePath
    {
        return $this->path;
    }

    public function mime(): FileMime
    {
        return $this->mime;
    }

    public function extension(): FileExtension
    {
        return $this->extension;
    }

    public function size(): FileSize
    {
        return $this->size;
    }

    public function metadata(): FileMetadata
    {
        return $this->metadata;
    }

    public function createdAt(): ?FileCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?FileUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?FileDeletedAt
    {
        return $this->deletedAt;
    }
}
