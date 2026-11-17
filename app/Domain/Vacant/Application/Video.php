<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application;

use App\Domain\Vacant\Application\Values\VideoCreatedAt;
use App\Domain\Vacant\Application\Values\VideoDeletedAt;
use App\Domain\Vacant\Application\Values\VideoExtension;
use App\Domain\Vacant\Application\Values\VideoFileSubtypeId;
use App\Domain\Vacant\Application\Values\VideoFileTypeId;
use App\Domain\Vacant\Application\Values\VideoId;
use App\Domain\Vacant\Application\Values\VideoMetadata;
use App\Domain\Vacant\Application\Values\VideoMime;
use App\Domain\Vacant\Application\Values\VideoModelId;
use App\Domain\Vacant\Application\Values\VideoModelType;
use App\Domain\Vacant\Application\Values\VideoName;
use App\Domain\Vacant\Application\Values\VideoPath;
use App\Domain\Vacant\Application\Values\VideoSize;
use App\Domain\Vacant\Application\Values\VideoUpdatedAt;

final class Video
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
        ?VideoId $id,
        VideoModelId $modelId,
        VideoModelType $modelType,
        VideoFileTypeId $fileTypeId,
        VideoFileSubtypeId $fileSubtypeId,
        VideoName $name,
        VideoPath $path,
        VideoMime $mime,
        VideoExtension $extension,
        VideoSize $size,
        VideoMetadata $metadata,
        ?VideoCreatedAt $createdAt = null,
        ?VideoUpdatedAt $updatedAt = null,
        ?VideoDeletedAt $deletedAt = null
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

    public function id(): ?VideoId
    {
        return $this->id;
    }

    public function modelId(): VideoModelId
    {
        return $this->modelId;
    }

    public function modelType(): VideoModelType
    {
        return $this->modelType;
    }

    public function fileTypeId(): VideoFileTypeId
    {
        return $this->fileTypeId;
    }

    public function fileSubtypeId(): VideoFileSubtypeId
    {
        return $this->fileSubtypeId;
    }

    public function name(): VideoName
    {
        return $this->name;
    }

    public function path(): VideoPath
    {
        return $this->path;
    }

    public function mime(): VideoMime
    {
        return $this->mime;
    }

    public function extension(): VideoExtension
    {
        return $this->extension;
    }

    public function size(): VideoSize
    {
        return $this->size;
    }

    public function metadata(): VideoMetadata
    {
        return $this->metadata;
    }

    public function createdAt(): ?VideoCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?VideoUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?VideoDeletedAt
    {
        return $this->deletedAt;
    }
}
