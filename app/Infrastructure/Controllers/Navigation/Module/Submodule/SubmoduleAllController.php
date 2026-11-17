<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Navigation\Module\Submodule;

use App\Application\NavigationUseCase\ModuleUseCase\SubmoduleUseCase\SubmoduleAllUseCase;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Http\Requests\Navigation\Module\Submodule\SubmoduleAllRequest;
use App\Http\Resources\Navigation\Module\Submodule\SubmoduleCollection;
use App\Infrastructure\Repositories\SubmoduleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SubmoduleAllController
{
    use ApiResponser;
    use ApiMessage;
    private $submoduleRepository;

    public function __construct(SubmoduleRepository $submoduleRepository)
    {
        $this->submoduleRepository = $submoduleRepository;
    }

    public function __invoke(SubmoduleAllRequest $request, int $moduleId)
    {
        try {
            $moduleId = new ModuleId($moduleId ?? null);
            $submoduleAllUseCase = new SubmoduleAllUseCase($this->submoduleRepository);
            $submodules = new SubmoduleCollection($submoduleAllUseCase->__invoke($request, $moduleId));
            return $this->successResponse(
                $submodules,
                'Los submodulos consultados exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
