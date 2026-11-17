<?php

declare(strict_types=1);

namespace App\Application\NavigationUseCase\ModuleUseCase\SubmoduleUseCase;

use App\Domain\Navigation\Module\Submodule\Contracts\SubmoduleContract;
use App\Domain\Navigation\Module\Submodule\Submodule;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleIcon;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleRoute;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleModelId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleModelType;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleName;
use App\Domain\Navigation\Module\Submodule\Values\SubmodulePermissionId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleRoleId;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleType;
use App\Http\Requests\Navigation\Module\Submodule\SubmoduleUpdateRequest;

final class SubmoduleUpdateUseCase
{
    private $submoduleContract;

    public function __construct(SubmoduleContract $submoduleContract)
    {
        $this->submoduleContract = $submoduleContract;
    }

    public function __invoke(SubmoduleUpdateRequest $request, SubmoduleId $submoduleId): Submodule
    {
        return $this->submoduleContract->update(
            new Submodule(
                new SubmoduleId($submoduleId->value()),
                new SubmoduleModelId($request->integer('model_id')),
                new SubmoduleModelType($request->input('model_type')),
                new SubmoduleRoleId($request->integer('role_id')),
                new SubmodulePermissionId($request->integer('permission_id')),
                new SubmoduleType($request->input('type')),
                new SubmoduleName($request->input('name')),
                new SubmoduleRoute($request->input('route')),
                new SubmoduleIcon($request->input('icon'))
            ),
            $submoduleId
        );
    }
}
