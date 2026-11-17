<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\SeveranceType;

use App\Application\SeveranceTypeUseCase\SeveranceTypeRestoreUseCase;
use App\Domain\SeveranceType\Values\SeveranceTypeId;
use App\Http\Resources\SeveranceType\SeveranceTypeResource;
use App\Infrastructure\Repositories\SeveranceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceTypeRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceTypeRepository;

    public function __construct(SeveranceTypeRepository $severanceTypeRepository)
    {
        $this->severanceTypeRepository = $severanceTypeRepository;
    }

    public function __invoke(int $severanceTypeId)
    {
        try {
            $severanceTypeId = new SeveranceTypeId($severanceTypeId);
            $severanceTypeRestoreUseCase = new SeveranceTypeRestoreUseCase($this->severanceTypeRepository);
            $severanceType = new SeveranceTypeResource($severanceTypeRestoreUseCase->__invoke($severanceTypeId));
            return $this->successResponse(
                $severanceType,
                'El tipo de cesantías fue activada exitosamente.',
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
