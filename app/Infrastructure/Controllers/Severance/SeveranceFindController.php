<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Severance;

use App\Application\SeveranceUseCase\SeveranceFindUseCase;
use App\Domain\Severance\Values\SeveranceId;
use App\Http\Resources\Severance\SeveranceResource;
use App\Infrastructure\Repositories\SeveranceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class SeveranceFindController
{
    use ApiResponser;
    use ApiMessage;
    private $severanceRepository;

    public function __construct(SeveranceRepository $severanceRepository)
    {
        $this->severanceRepository = $severanceRepository;
    }

    public function __invoke(int $severanceId)
    {
        try {
            $severanceId = new SeveranceId($severanceId ?? null);
            $severanceFindUseCase = new SeveranceFindUseCase($this->severanceRepository);
            $severance = new SeveranceResource($severanceFindUseCase->__invoke($severanceId));
            return $this->successResponse(
                $severance,
                'La solicitud de retiro de las cesantías fue encontrada exitosamente.',
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
