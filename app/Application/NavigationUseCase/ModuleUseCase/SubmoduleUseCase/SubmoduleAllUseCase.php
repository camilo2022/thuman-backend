<?php

declare(strict_types=1);

namespace App\Application\NavigationUseCase\ModuleUseCase\SubmoduleUseCase;

use App\Domain\Navigation\Module\Submodule\Contracts\SubmoduleContract;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Http\Requests\Navigation\Module\Submodule\SubmoduleAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class SubmoduleAllUseCase
{
    private $submoduleContract;

    public function __construct(SubmoduleContract $submoduleContract)
    {
        $this->submoduleContract = $submoduleContract;
    }

    public function __invoke(SubmoduleAllRequest $request, ModuleId $moduleId): LengthAwarePaginator
    {
        return $this->submoduleContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request, $moduleId);
    }
}
