<?php

declare(strict_types=1);

namespace App\Domain\Profile\Contracts;

use App\Domain\Profile\File;
use App\Domain\Profile\Profile;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\ProfileFindRequest;

interface ProfileContract
{
    public function find(ProfileFindRequest $request, ProfileId $profileId): ?Profile;

    public function store(Profile $profile): Profile;

    public function update(Profile $profile, ProfileId $profileId): Profile;

    public function file(File $file): File;
}