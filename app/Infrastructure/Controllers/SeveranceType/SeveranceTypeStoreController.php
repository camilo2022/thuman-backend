<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\SeveranceType;

use App\Application\SeveranceTypeUseCase\SeveranceTypeStoreUseCase;
use App\Http\Requests\SeveranceType\SeveranceTypeStoreRequest;
use App\Http\Resources\SeveranceType\SeveranceTypeResource;
use App\Infrastructure\Repositories\SeveranceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceTypeRepository;

    public function __construct(SeveranceTypeRepository $severanceTypeRepository)
    {
        $this->severanceTypeRepository = $severanceTypeRepository;
    }

    public function __invoke(SeveranceTypeStoreRequest $request)
    {
        try {
            $severanceTypeStoreUseCase = new SeveranceTypeStoreUseCase($this->severanceTypeRepository);
            $severanceType = new SeveranceTypeResource($severanceTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $severanceType,
                'El tipo de cesantías fue registrado exitosamente.',
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
