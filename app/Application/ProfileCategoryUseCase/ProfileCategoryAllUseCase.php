<?php

declare(strict_types=1);

namespace App\Application\ProfileCategoryUseCase;

use App\Domain\ProfileCategory\Contracts\ProfileCategoryContract;
use App\Http\Requests\ProfileCategory\ProfileCategoryAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProfileCategoryAllUseCase
{
    private $profileCategoryContract;

    public function __construct(ProfileCategoryContract $profileCategoryContract)
    {
        $this->profileCategoryContract = $profileCategoryContract;
    }

    public function __invoke(ProfileCategoryAllRequest $request): LengthAwarePaginator
    {
        return $this->profileCategoryContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
