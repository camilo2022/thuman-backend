<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Contracts;

use App\Domain\Navigation\Module\Module;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Http\Requests\Navigation\Module\ModuleAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ModuleContract
{
    public function all(string|null $search, int|null $perPage, ModuleAllRequest $request): LengthAwarePaginator;

    public function find(ModuleId $moduleId): ?Module;

    public function store(Module $module): Module;

    public function update(Module $module, ModuleId $moduleId): Module;

    public function delete(ModuleId $moduleId): Module;

    public function restore(ModuleId $moduleId): Module;
}
