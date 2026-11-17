<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Navigation\Module;

use App\Application\NavigationUseCase\ModuleUseCase\ModuleAllUseCase;
use App\Http\Requests\Navigation\Module\ModuleAllRequest;
use App\Http\Resources\Navigation\Module\ModuleCollection;
use App\Infrastructure\Repositories\ModuleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ModuleAllController
{
    use ApiResponser;
    use ApiMessage;
    private $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function __invoke(ModuleAllRequest $request)
    {
        try {
            $moduleAllUseCase = new ModuleAllUseCase($this->moduleRepository);
            $modules = new ModuleCollection($moduleAllUseCase->__invoke($request));
            return $this->successResponse(
                $modules,
                'Los modulos consultados exitosamente.',
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
