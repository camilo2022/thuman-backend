<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Navigation\Module\Submodule;

use App\Application\NavigationUseCase\ModuleUseCase\SubmoduleUseCase\SubmoduleUpdateUseCase;
use App\Domain\Navigation\Module\Submodule\Values\SubmoduleId;
use App\Http\Requests\Navigation\Module\Submodule\SubmoduleUpdateRequest;
use App\Http\Resources\Navigation\Module\Submodule\SubmoduleResource;
use App\Infrastructure\Repositories\SubmoduleRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SubmoduleUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $submoduleRepository;

    public function __construct(SubmoduleRepository $submoduleRepository)
    {
        $this->submoduleRepository = $submoduleRepository;
    }

    public function __invoke(SubmoduleUpdateRequest $request, int $submoduleId)
    {
        try {
            $submoduleId = new SubmoduleId($submoduleId ?? null);
            $submoduleUpdateUseCase = new SubmoduleUpdateUseCase($this->submoduleRepository);
            $submodule = new SubmoduleResource($submoduleUpdateUseCase->__invoke($request, $submoduleId));
            return $this->successResponse(
                $submodule,
                'El submodulo fue actualizado exitosamente.',
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
