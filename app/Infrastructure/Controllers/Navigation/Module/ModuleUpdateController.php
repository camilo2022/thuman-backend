<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Navigation\Module;

use App\Application\NavigationUseCase\ModuleUseCase\ModuleUpdateUseCase;
use App\Domain\Navigation\Module\Values\ModuleId;
use App\Http\Requests\Navigation\Module\ModuleUpdateRequest;
use App\Http\Resources\Navigation\Module\ModuleResource;
use App\Infrastructure\Repositories\ModuleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ModuleUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function __invoke(ModuleUpdateRequest $request, int $moduleId)
    {
        try {
            $moduleId = new ModuleId($moduleId ?? null);
            $moduleUpdateUseCase = new ModuleUpdateUseCase($this->moduleRepository);
            $module = new ModuleResource($moduleUpdateUseCase->__invoke($request, $moduleId));
            return $this->successResponse(
                $module,
                'El modulo fue actualizado exitosamente.',
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
