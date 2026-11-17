<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType\Reason;

use App\Application\TerminationTypeUseCase\ReasonUseCase\ReasonRestoreUseCase;
use App\Domain\TerminationType\Reason\Values\ReasonId;
use App\Http\Resources\TerminationType\Reason\ReasonResource;
use App\Infrastructure\Repositories\ReasonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ReasonRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $reasonRepository;

    public function __construct(ReasonRepository $reasonRepository)
    {
        $this->reasonRepository = $reasonRepository;
    }

    public function __invoke(int $reasonId)
    {
        try {
            $reasonId = new ReasonId($reasonId);
            $reasonRestoreUseCase = new ReasonRestoreUseCase($this->reasonRepository);
            $reason = new ReasonResource($reasonRestoreUseCase->__invoke($reasonId));
            return $this->successResponse(
                $reason,
                'La razón de terminación de contrato fue activada exitosamente.',
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
