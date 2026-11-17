<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

use Illuminate\Support\Collection;

final class PersonRelationships
{
    private ?object $birth_city;
    private ?object $document_issue_city;
    private ?object $city;
    private ?object $dialing_country;
    private ?object $document_type;
    private ?object $sex;
    private ?object $gender;
    private ?object $blood_type;
    private ?object $marital_status;
    private Collection $vacants;
    private Collection $entities;
    private ?object $file;
    private ?object $photo;
    private ?object $signature;
    private ?object $user;
    private ?object $employee;

    public function __construct(
        ?object $birth_city = null,
        ?object $document_issue_city = null,
        ?object $city = null,
        ?object $dialing_country = null,
        ?object $document_type = null,
        ?object $sex = null,
        ?object $gender = null,
        ?object $blood_type = null,
        ?object $marital_status = null,
        ?Collection $vacants = null,
        ?Collection $entities = null,
        ?object $file = null,
        ?object $photo = null,
        ?object $signature = null,
        ?object $user = null,
        ?object $employee = null
    ) {
        $this->birth_city = $birth_city;
        $this->document_issue_city = $document_issue_city;
        $this->city = $city;
        $this->dialing_country = $dialing_country;
        $this->document_type = $document_type;
        $this->sex = $sex;
        $this->gender = $gender;
        $this->blood_type = $blood_type;
        $this->marital_status = $marital_status;
        $this->vacants = $vacants ?? collect();
        $this->entities = $entities ?? collect();
        $this->file = $file;
        $this->photo = $photo;
        $this->signature = $signature;
        $this->user = $user;
        $this->employee = $employee;
    }

    public function birthCity(): ?object
    {
        return $this->birth_city;
    }

    public function documentIssueCity(): ?object
    {
        return $this->document_issue_city;
    }

    public function city(): ?object
    {
        return $this->city;
    }

    public function dialingCountry(): ?object
    {
        return $this->dialing_country;
    }

    public function documentType(): ?object
    {
        return $this->document_type;
    }

    public function sex(): ?object
    {
        return $this->sex;
    }

    public function gender(): ?object
    {
        return $this->gender;
    }

    public function bloodType(): ?object
    {
        return $this->blood_type;
    }

    public function maritalStatus(): ?object
    {
        return $this->marital_status;
    }

    public function vacants(): Collection
    {
        return $this->vacants;
    }

    public function entities(): Collection
    {
        return $this->entities;
    }

    public function file(): ?array
    {
        return $this->file;
    }

    public function photo(): ?array
    {
        return $this->photo;
    }

    public function signature(): ?array
    {
        return $this->signature;
    }

    public function user(): ?array
    {
        return $this->user;
    }

    public function employee(): ?array
    {
        return $this->employee;
    }

    public function toArray(): array
    {
        return [
            'birth_city' => $this->birth_city,
            'document_issue_city' => $this->document_issue_city,
            'city' => $this->city,
            'dialing_country' => $this->dialing_country,
            'document_type' => $this->document_type,
            'sex' => $this->sex,
            'gender' => $this->gender,
            'blood_type' => $this->blood_type,
            'marital_status' => $this->marital_status,
            'vacants' => $this->vacants,
            'entities' => $this->entities,
            'file' => $this->file,
            'photo' => $this->photo,
            'signature' => $this->signature,
            'user' => $this->user,
            'employee' => $this->employee
        ];
    }
}
