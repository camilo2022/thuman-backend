<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Severance;

use App\Application\SeveranceUseCase\SeveranceStoreUseCase;
use App\Http\Requests\Severance\SeveranceStoreRequest;
use App\Http\Resources\Severance\SeveranceResource;
use App\Infrastructure\Repositories\SeveranceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceRepository;

    public function __construct(SeveranceRepository $severanceRepository)
    {
        $this->severanceRepository = $severanceRepository;
    }

    public function __invoke(SeveranceStoreRequest $request)
    {
        try {
            $severanceStoreUseCase = new SeveranceStoreUseCase($this->severanceRepository);
            $severance = new SeveranceResource($severanceStoreUseCase->__invoke($request));
            return $this->successResponse(
                $severance,
                'La solicitud de retiro de las cesantías fue registrada exitosamente.',
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
