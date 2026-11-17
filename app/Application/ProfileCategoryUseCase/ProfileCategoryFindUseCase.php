<?php

declare(strict_types=1);

namespace App\Application\ProfileCategoryUseCase;

use App\Domain\ProfileCategory\ProfileCategory;
use App\Domain\ProfileCategory\Contracts\ProfileCategoryContract;
use App\Domain\ProfileCategory\Values\ProfileCategoryId;

final class ProfileCategoryFindUseCase
{
    private $profileCategoryContract;

    public function __construct(ProfileCategoryContract $profileCategoryContract)
    {
        $this->profileCategoryContract = $profileCategoryContract;
    }

    public function __invoke(ProfileCategoryId $profileCategoryId): ?ProfileCategory
    {
        return $this->profileCategoryContract->find($profileCategoryId);
    }
}
