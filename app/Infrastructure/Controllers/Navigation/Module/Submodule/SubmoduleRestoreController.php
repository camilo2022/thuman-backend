<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Navigation\Module\Submodule;

use App\Application\NavigationUseCase\ModuleUseCase\SubmoduleUseCase\SubmoduleRestoreUseCase;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleId;
use App\Http\Resources\Navigation\Module\Submodule\SubmoduleResource;
use App\Infrastructure\Repositories\SubmoduleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SubmoduleRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $submoduleRepository;

    public function __construct(SubmoduleRepository $submoduleRepository)
    {
        $this->submoduleRepository = $submoduleRepository;
    }

    public function __invoke(int $submoduleId)
    {
        try {
            $submoduleId = new SubmoduleId($submoduleId);
            $submoduleRestoreUseCase = new SubmoduleRestoreUseCase($this->submoduleRepository);
            $submodule = new SubmoduleResource($submoduleRestoreUseCase->__invoke($submoduleId));
            return $this->successResponse(
                $submodule,
                'El submodulo fue restaurado exitosamente.',
                204
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
