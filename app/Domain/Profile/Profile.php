<?php

declare(strict_types=1);

namespace App\Domain\Profile;

use App\Domain\Profile\Values\ProfileRelationships;
use App\Domain\Profile\Values\ProfileCreatedAt;
use App\Domain\Profile\Values\ProfileId;
use App\Domain\Profile\Values\ProfileTitle;
use App\Domain\Profile\Values\ProfilePersonId;
use App\Domain\Profile\Values\ProfileDescription;
use App\Domain\Profile\Values\ProfileUpdatedAt;

final class Profile
{
    private $id;
    private $personId;
    private $title;
    private $description;
    private $createdAt;
    private $updatedAt;
    private $relationships;

    public function __construct(
        ?ProfileId $id,
        ProfilePersonId $personId,
        ProfileTitle $title,
        ProfileDescription $description,
        ?ProfileCreatedAt $createdAt = null,
        ?ProfileUpdatedAt $updatedAt = null,
        ?ProfileRelationships $relationships = null,
    )
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?ProfileId
    {
        return $this->id;
    }

    public function personId(): ProfilePersonId
    {
        return $this->personId;
    }

    public function title(): ProfileTitle
    {
        return $this->title;
    }

    public function description(): ProfileDescription
    {
        return $this->description;
    }

    public function createdAt(): ?ProfileCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ProfileUpdatedAt
    {
        return $this->updatedAt;
    }

    public function relationships(): ?ProfileRelationships
    {
        return $this->relationships;
    }
}
