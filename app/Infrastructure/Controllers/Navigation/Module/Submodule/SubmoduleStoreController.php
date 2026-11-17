<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Navigation\Module\Submodule;

use App\Application\NavigationUseCase\ModuleUseCase\SubmoduleUseCase\SubmoduleStoreUseCase;
use App\Http\Requests\Navigation\Module\Submodule\SubmoduleStoreRequest;
use App\Http\Resources\Navigation\Module\Submodule\SubmoduleResource;
use App\Infrastructure\Repositories\SubmoduleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SubmoduleStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $submoduleRepository;

    public function __construct(SubmoduleRepository $submoduleRepository)
    {
        $this->submoduleRepository = $submoduleRepository;
    }

    public function __invoke(SubmoduleStoreRequest $request)
    {
        try {
            $submoduleStoreUseCase = new SubmoduleStoreUseCase($this->submoduleRepository);
            $submodule = new SubmoduleResource($submoduleStoreUseCase->__invoke($request));
            return $this->successResponse(
                $submodule,
                'El submodulo fue registrado exitosamente.',
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
