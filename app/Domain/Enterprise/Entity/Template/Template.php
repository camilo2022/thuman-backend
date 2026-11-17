<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Template;

use App\Domain\Enterprise\Entity\Template\Values\TemplateCreatedAt;
use App\Domain\Enterprise\Entity\Template\Values\TemplateDeletedAt;
use App\Domain\Enterprise\Entity\Template\Values\TemplateExtension;
use App\Domain\Enterprise\Entity\Template\Values\TemplateFileSubtypeId;
use App\Domain\Enterprise\Entity\Template\Values\TemplateFileTypeId;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Domain\Enterprise\Entity\Template\Values\TemplateMetadata;
use App\Domain\Enterprise\Entity\Template\Values\TemplateMime;
use App\Domain\Enterprise\Entity\Template\Values\TemplateModelId;
use App\Domain\Enterprise\Entity\Template\Values\TemplateModelType;
use App\Domain\Enterprise\Entity\Template\Values\TemplateName;
use App\Domain\Enterprise\Entity\Template\Values\TemplatePath;
use App\Domain\Enterprise\Entity\Template\Values\TemplateRelationships;
use App\Domain\Enterprise\Entity\Template\Values\TemplateSettings;
use App\Domain\Enterprise\Entity\Template\Values\TemplateSize;
use App\Domain\Enterprise\Entity\Template\Values\TemplateUpdatedAt;

final class Template
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
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?TemplateId $id,
        TemplateModelId $modelId,
        TemplateModelType $modelType,
        TemplateFileTypeId $fileTypeId,
        TemplateFileSubtypeId $fileSubtypeId,
        TemplateName $name,
        TemplatePath $path,
        TemplateMime $mime,
        TemplateExtension $extension,
        TemplateSize $size,
        TemplateMetadata $metadata,
        ?TemplateSettings $settings = null,
        ?TemplateCreatedAt $createdAt = null,
        ?TemplateUpdatedAt $updatedAt = null,
        ?TemplateDeletedAt $deletedAt = null,
        ?TemplateRelationships $relationships = null
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
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?TemplateId
    {
        return $this->id;
    }

    public function modelId(): TemplateModelId
    {
        return $this->modelId;
    }

    public function modelType(): TemplateModelType
    {
        return $this->modelType;
    }

    public function fileTypeId(): TemplateFileTypeId
    {
        return $this->fileTypeId;
    }

    public function fileSubtypeId(): TemplateFileSubtypeId
    {
        return $this->fileSubtypeId;
    }

    public function name(): TemplateName
    {
        return $this->name;
    }

    public function path(): TemplatePath
    {
        return $this->path;
    }

    public function mime(): TemplateMime
    {
        return $this->mime;
    }

    public function extension(): TemplateExtension
    {
        return $this->extension;
    }

    public function size(): TemplateSize
    {
        return $this->size;
    }

    public function metadata(): TemplateMetadata
    {
        return $this->metadata;
    }

    public function settings(): ?TemplateSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?TemplateCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?TemplateUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?TemplateDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?TemplateRelationships
    {
        return $this->relationships;
    }
}
