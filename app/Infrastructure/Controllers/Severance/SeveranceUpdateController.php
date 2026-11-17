<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Severance;

use App\Application\SeveranceUseCase\SeveranceUpdateUseCase;
use App\Domain\Severance\Values\SeveranceId;
use App\Http\Requests\Severance\SeveranceUpdateRequest;
use App\Http\Resources\Severance\SeveranceResource;
use App\Infrastructure\Repositories\SeveranceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceRepository;

    public function __construct(SeveranceRepository $severanceRepository)
    {
        $this->severanceRepository = $severanceRepository;
    }

    public function __invoke(SeveranceUpdateRequest $request, int $severanceId)
    {
        try {
            $severanceId = new SeveranceId($severanceId ?? null);
            $severanceUpdateUseCase = new SeveranceUpdateUseCase($this->severanceRepository);
            $severance = new SeveranceResource($severanceUpdateUseCase->__invoke($request, $severanceId));
            return $this->successResponse(
                $severance,
                'La solicitud de retiro de las cesantías fue actualizada exitosamente.',
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
