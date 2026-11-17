<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType\Reason;

use App\Application\TerminationTypeUseCase\ReasonUseCase\ReasonUpdateUseCase;
use App\Domain\TerminationType\Reason\Values\ReasonId;
use App\Http\Requests\TerminationType\Reason\ReasonUpdateRequest;
use App\Http\Resources\TerminationType\Reason\ReasonResource;
use App\Infrastructure\Repositories\ReasonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ReasonUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $reasonRepository;

    public function __construct(ReasonRepository $reasonRepository)
    {
        $this->reasonRepository = $reasonRepository;
    }

    public function __invoke(ReasonUpdateRequest $request, int $reasonId)
    {
        try {
            $reasonId = new ReasonId($reasonId ?? null);
            $reasonUpdateUseCase = new ReasonUpdateUseCase($this->reasonRepository);
            $reason = new ReasonResource($reasonUpdateUseCase->__invoke($request, $reasonId));
            return $this->successResponse(
                $reason,
                'La razón de terminación de contrato fue actualizado exitosamente.',
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
