<?php

declare(strict_types=1);

namespace App\Application\NavigationUseCase\ModuleUseCase;

use App\Domain\Navigation\Module\Module;
use App\Domain\Navigation\Module\Contracts\ModuleContract;
use App\Domain\Navigation\Module\Values\ModuleId;

final class ModuleDeleteUseCase
{
    private $moduleContract;

    public function __construct(ModuleContract $moduleContract)
    {
        $this->moduleContract = $moduleContract;
    }

    public function __invoke(ModuleId $moduleId): ?Module
    {
        return $this->moduleContract->delete($moduleId);
    }
}
