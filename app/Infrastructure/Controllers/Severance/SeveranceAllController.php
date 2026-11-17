<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Severance;

use App\Application\SeveranceUseCase\SeveranceAllUseCase;
use App\Http\Requests\Severance\SeveranceAllRequest;
use App\Http\Resources\Severance\SeveranceCollection;
use App\Infrastructure\Repositories\SeveranceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceAllController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceRepository;

    public function __construct(SeveranceRepository $severanceRepository)
    {
        $this->severanceRepository = $severanceRepository;
    }

    public function __invoke(SeveranceAllRequest $request)
    {
        try {
            $severanceAllUseCase = new SeveranceAllUseCase($this->severanceRepository);
            $severances = new SeveranceCollection($severanceAllUseCase->__invoke($request));
            return $this->successResponse(
                $severances,
                'Las solicitudes de retiro de las cesantías fueron consultadas exitosamente.',
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
