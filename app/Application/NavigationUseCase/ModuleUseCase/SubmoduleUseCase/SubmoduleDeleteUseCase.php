<?php

declare(strict_types=1);

namespace App\Application\NavigationUseCase\ModuleUseCase\SubmoduleUseCase;

use App\Domain\Navigation\Module\Submodule\Submodule;
use App\Domain\Navigation\Module\Submodule\Contracts\SubmoduleContract;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleId;

final class SubmoduleDeleteUseCase
{
    private $submoduleContract;

    public function __construct(SubmoduleContract $submoduleContract)
    {
        $this->submoduleContract = $submoduleContract;
    }

    public function __invoke(SubmoduleId $submoduleId): ?Submodule
    {
        return $this->submoduleContract->delete($submoduleId);
    }
}
