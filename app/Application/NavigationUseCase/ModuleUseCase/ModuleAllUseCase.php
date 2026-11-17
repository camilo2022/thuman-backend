<?php

declare(strict_types=1);

namespace App\Application\NavigationUseCase\ModuleUseCase;

use App\Domain\Navigation\Module\Contracts\ModuleContract;
use App\Http\Requests\Navigation\Module\ModuleAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class ModuleAllUseCase
{
    private $moduleContract;

    public function __construct(ModuleContract $moduleContract)
    {
        $this->moduleContract = $moduleContract;
    }

    public function __invoke(ModuleAllRequest $request): LengthAwarePaginator
    {
        return $this->moduleContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
