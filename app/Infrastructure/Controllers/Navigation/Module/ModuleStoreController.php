<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Navigation\Module;

use App\Application\NavigationUseCase\ModuleUseCase\ModuleStoreUseCase;
use App\Http\Requests\Navigation\Module\ModuleStoreRequest;
use App\Http\Resources\Navigation\Module\ModuleResource;
use App\Infrastructure\Repositories\ModuleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ModuleStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function __invoke(ModuleStoreRequest $request)
    {
        try {
            $moduleStoreUseCase = new ModuleStoreUseCase($this->moduleRepository);
            $module = new ModuleResource($moduleStoreUseCase->__invoke($request));
            return $this->successResponse(
                $module,
                'El modulo fue registrado exitosamente.',
                201
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
