<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\SeveranceType;

use App\Application\SeveranceTypeUseCase\SeveranceTypeAllUseCase;
use App\Http\Requests\SeveranceType\SeveranceTypeAllRequest;
use App\Http\Resources\SeveranceType\SeveranceTypeCollection;
use App\Infrastructure\Repositories\SeveranceTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceTypeRepository;

    public function __construct(SeveranceTypeRepository $severanceTypeRepository)
    {
        $this->severanceTypeRepository = $severanceTypeRepository;
    }

    public function __invoke(SeveranceTypeAllRequest $request)
    {
        try {
            $severanceTypeAllUseCase = new SeveranceTypeAllUseCase($this->severanceTypeRepository);
            $severanceTypes = new SeveranceTypeCollection($severanceTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $severanceTypes,
                'Los tipos de cesantías consultados exitosamente.',
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
