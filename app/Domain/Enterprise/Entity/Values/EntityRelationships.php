<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Values;

use Illuminate\Support\Collection;

final class EntityRelationships
{
    private ?object $enterprise;
    private ?object $country;
    private ?object $document_type;
    private ?object $entity_type;
    private ?object $person;
    private Collection $images;
    private Collection $people;

    public function __construct(
        ?object $enterprise = null,
        ?object $country = null,
        ?object $document_type = null,
        ?object $entity_type = null,
        ?object $person = null,
        ?Collection $images = null,
        ?Collection $people = null
    ) {
        $this->enterprise = $enterprise;
        $this->country = $country;
        $this->document_type = $document_type;
        $this->entity_type = $entity_type;
        $this->person = $person;
        $this->images = $images ?? collect();
        $this->people = $people ?? collect();
    }

    public function enterprise(): ?object
    {
        return $this->enterprise;
    }

    public function country(): ?object
    {
        return $this->country;
    }

    public function documentType(): ?object
    {
        return $this->document_type;
    }

    public function entityType(): ?object
    {
        return $this->entity_type;
    }

    public function person(): ?object
    {
        return $this->person;
    }

    public function images(): Collection
    {
        return $this->images;
    }

    public function people(): Collection
    {
        return $this->people;
    }

    public function banner(): ?object
    {
        return $this->images->firstWhere('file_subtype_id', 107);
    }

    public function mission(): ?object
    {
        return $this->images->firstWhere('file_subtype_id', 108);
    }

    public function vision(): ?object
    {
        return $this->images->firstWhere('file_subtype_id', 109);
    }

    public function aboutUs(): ?object
    {
        return $this->images->firstWhere('file_subtype_id', 110);
    }

    public function toArray(): array
    {
        return [
            'enterprise' => $this->enterprise,
            'country' => $this->country,
            'document_type' => $this->document_type,
            'entity_type' => $this->entity_type,
            'person' => $this->person,
            'images' => $this->images(),
            'people' => $this->people,
            'banner' => $this->banner(),
            'mission' => $this->mission(),
            'vision' => $this->vision(),
            'about_us' => $this->aboutUs()
        ];
    }
}
