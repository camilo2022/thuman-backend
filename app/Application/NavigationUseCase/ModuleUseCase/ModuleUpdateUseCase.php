<?php

declare(strict_types=1);

namespace App\Application\NavigationUseCase\ModuleUseCase;

use App\Domain\Navigation\Module\Contracts\ModuleContract;
use App\Domain\Navigation\Module\Module;
use App\Domain\Navigation\Module\Values\ModuleRoute;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Domain\Navigation\Module\Values\ModuleName;
use App\Domain\Navigation\Module\Values\ModuleType;
use App\Http\Requests\Navigation\Module\ModuleUpdateRequest;

final class ModuleUpdateUseCase
{
    private $moduleContract;

    public function __construct(ModuleContract $moduleContract)
    {
        $this->moduleContract = $moduleContract;
    }

    public function __invoke(ModuleUpdateRequest $request, ModuleId $moduleId): Module
    {
        return $this->moduleContract->update(
            new Module(
                new ModuleId($moduleId->value()),
                new ModuleName($request->input('name')),
                new ModuleType($request->input('type')),
                new ModuleRoute($request->input('route'))
            ),
            $moduleId
        );
    }
}
