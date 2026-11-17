<?php

declare(strict_types=1);

namespace App\Domain\Profile\LanguageProficiencie\Contracts;

use App\Domain\Profile\LanguageProficiencie\File;
use App\Domain\Profile\LanguageProficiencie\LanguageProficiencie;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieId;
use App\Domain\Profile\Values\ProfileId;
use Illuminate\Pagination\LengthAwarePaginator;

interface LanguageProficiencieContract
{
    public function all(string|null $search, int|null $perPage, ProfileId $profileId): LengthAwarePaginator;

    public function find(LanguageProficiencieId $languageProficiencieId): ?LanguageProficiencie;

    public function store(LanguageProficiencie $languageProficiencie): LanguageProficiencie;

    public function update(LanguageProficiencie $languageProficiencie, LanguageProficiencieId $languageProficiencieId): LanguageProficiencie;

    public function delete(LanguageProficiencieId $languageProficiencieId): void;

    public function file(File $file): File;
}
