<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase;

use App\Domain\Profile\Contracts\ProfileContract;
use App\Domain\Profile\Profile;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\ProfileFindRequest;

final class ProfileFindUseCase
{
    private $profileContract;

    public function __construct(ProfileContract $profileContract)
    {
        $this->profileContract = $profileContract;
    }

    public function __invoke(ProfileFindRequest $request, ProfileId $profileId): ?Profile
    {
        return $this->profileContract->find($request, $profileId);
    }
}
