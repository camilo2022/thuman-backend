<?php

declare(strict_types=1);

namespace App\Domain\Navigation\Module\Submodule\Contracts;

use App\Domain\Navigation\Module\Submodule\Submodule;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleId;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Http\Requests\Navigation\Module\Submodule\SubmoduleAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface SubmoduleContract
{
    public function all(string|null $search, int|null $perPage, SubmoduleAllRequest $request, ModuleId $moduleId): LengthAwarePaginator;

    public function find(SubmoduleId $submoduleId): ?Submodule;

    public function store(Submodule $submodule): Submodule;

    public function update(Submodule $submodule, SubmoduleId $submoduleId): Submodule;

    public function delete(SubmoduleId $submoduleId): Submodule;

    public function restore(SubmoduleId $submoduleId): Submodule;
}
