<?php

declare(strict_types=1);

namespace App\Domain\Profile\LanguageProficiencie;

use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieLanguageId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieLanguageLevelId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieCreatedAt;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieProfileId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieRelationships;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieUpdatedAt;

final class LanguageProficiencie
{
    private $id;
    private $profileId;
    private $languageId;
    private $languageLevelId;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?LanguageProficiencieId $id,
        LanguageProficiencieProfileId $profileId,
        LanguageProficiencieLanguageId $languageId,
        LanguageProficiencieLanguageLevelId $languageLevelId,
        ?LanguageProficiencieCreatedAt $createdAt = null,
        ?LanguageProficiencieUpdatedAt $updatedAt = null,
        ?LanguageProficiencieRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->profileId = $profileId;
        $this->languageId = $languageId;
        $this->languageLevelId = $languageLevelId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?LanguageProficiencieId
    {
        return $this->id;
    }

    public function profileId(): LanguageProficiencieProfileId
    {
        return $this->profileId;
    }

    public function languageId(): LanguageProficiencieLanguageId
    {
        return $this->languageId;
    }

    public function languageLevelId(): LanguageProficiencieLanguageLevelId
    {
        return $this->languageLevelId;
    }

    public function createdAt(): ?LanguageProficiencieCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?LanguageProficiencieUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?LanguageProficiencieRelationships
    {
        return $this->relationships;
    }
}
