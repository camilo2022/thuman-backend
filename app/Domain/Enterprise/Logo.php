<?php

declare(strict_types=1);

namespace App\Domain\Enterprise;

use App\Domain\Enterprise\Values\LogoCreatedAt;
use App\Domain\Enterprise\Values\LogoDeletedAt;
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
use App\Domain\Enterprise\Values\LogoUpdatedAt;

final class Logo
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
        ?LogoId $id,
        LogoModelId $modelId,
        LogoModelType $modelType,
        LogoFileTypeId $fileTypeId,
        LogoFileSubtypeId $fileSubtypeId,
        LogoName $name,
        LogoPath $path,
        LogoMime $mime,
        LogoExtension $extension,
        LogoSize $size,
        LogoMetadata $metadata,
        ?LogoCreatedAt $createdAt = null,
        ?LogoUpdatedAt $updatedAt = null,
        ?LogoDeletedAt $deletedAt = null
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

    public function id(): ?LogoId
    {
        return $this->id;
    }

    public function modelId(): LogoModelId
    {
        return $this->modelId;
    }

    public function modelType(): LogoModelType
    {
        return $this->modelType;
    }

    public function fileTypeId(): LogoFileTypeId
    {
        return $this->fileTypeId;
    }

    public function fileSubtypeId(): LogoFileSubtypeId
    {
        return $this->fileSubtypeId;
    }

    public function name(): LogoName
    {
        return $this->name;
    }

    public function path(): LogoPath
    {
        return $this->path;
    }

    public function mime(): LogoMime
    {
        return $this->mime;
    }

    public function extension(): LogoExtension
    {
        return $this->extension;
    }

    public function size(): LogoSize
    {
        return $this->size;
    }

    public function metadata(): LogoMetadata
    {
        return $this->metadata;
    }

    public function createdAt(): ?LogoCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?LogoUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?LogoDeletedAt
    {
        return $this->deletedAt;
    }
}
