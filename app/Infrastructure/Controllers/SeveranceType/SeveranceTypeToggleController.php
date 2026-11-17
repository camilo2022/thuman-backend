<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\SeveranceType;

use App\Application\SeveranceTypeUseCase\SeveranceTypeToggleUseCase;
use App\Domain\SeveranceType\Values\SeveranceTypeId;
use App\Http\Requests\SeveranceType\SeveranceTypeToggleRequest;
use App\Http\Resources\SeveranceType\SeveranceTypeResource;
use App\Infrastructure\Repositories\SeveranceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceTypeToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceTypeRepository;

    public function __construct(SeveranceTypeRepository $severanceTypeRepository)
    {
        $this->severanceTypeRepository = $severanceTypeRepository;
    }

    public function __invoke(SeveranceTypeToggleRequest $request, int $severanceTypeId)
    {
        try {
            $severanceTypeId = new SeveranceTypeId($severanceTypeId ?? null);
            $severanceTypeToggleUseCase = new SeveranceTypeToggleUseCase($this->severanceTypeRepository);
            $severanceType = new SeveranceTypeResource($severanceTypeToggleUseCase->__invoke($request, $severanceTypeId));
            return $this->successResponse(
                $severanceType,
                'El tipo de cesantías fue actualizado exitosamente.',
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
