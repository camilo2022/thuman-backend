<?php

declare(strict_types=1);

namespace App\Domain\Benefit;

use App\Domain\Benefit\Values\IconCreatedAt;
use App\Domain\Benefit\Values\IconDeletedAt;
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
use App\Domain\Benefit\Values\IconUpdatedAt;

final class Icon
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
        ?IconId $id,
        IconModelId $modelId,
        IconModelType $modelType,
        IconFileTypeId $fileTypeId,
        IconFileSubtypeId $fileSubtypeId,
        IconName $name,
        IconPath $path,
        IconMime $mime,
        IconExtension $extension,
        IconSize $size,
        IconMetadata $metadata,
        ?IconCreatedAt $createdAt = null,
        ?IconUpdatedAt $updatedAt = null,
        ?IconDeletedAt $deletedAt = null
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

    public function id(): ?IconId
    {
        return $this->id;
    }

    public function modelId(): IconModelId
    {
        return $this->modelId;
    }

    public function modelType(): IconModelType
    {
        return $this->modelType;
    }

    public function fileTypeId(): IconFileTypeId
    {
        return $this->fileTypeId;
    }

    public function fileSubtypeId(): IconFileSubtypeId
    {
        return $this->fileSubtypeId;
    }

    public function name(): IconName
    {
        return $this->name;
    }

    public function path(): IconPath
    {
        return $this->path;
    }

    public function mime(): IconMime
    {
        return $this->mime;
    }

    public function extension(): IconExtension
    {
        return $this->extension;
    }

    public function size(): IconSize
    {
        return $this->size;
    }

    public function metadata(): IconMetadata
    {
        return $this->metadata;
    }

    public function createdAt(): ?IconCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?IconUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?IconDeletedAt
    {
        return $this->deletedAt;
    }
}
