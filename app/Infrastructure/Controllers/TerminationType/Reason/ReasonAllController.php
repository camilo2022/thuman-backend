<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType\Reason;

use App\Application\TerminationTypeUseCase\ReasonUseCase\ReasonAllUseCase;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Requests\TerminationType\Reason\ReasonAllRequest;
use App\Http\Resources\TerminationType\Reason\ReasonCollection;
use App\Infrastructure\Repositories\ReasonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ReasonAllController
{
    use ApiResponser;
    use ApiMessage;
    private $reasonRepository;

    public function __construct(ReasonRepository $reasonRepository)
    {
        $this->reasonRepository = $reasonRepository;
    }

    public function __invoke(ReasonAllRequest $request, int $terminationTypeId)
    {
        try {
            $terminationTypeId = new TerminationTypeId($terminationTypeId ?? null);
            $reasonAllUseCase = new ReasonAllUseCase($this->reasonRepository);
            $reasons = new ReasonCollection($reasonAllUseCase->__invoke($request, $terminationTypeId));
            return $this->successResponse(
                $reasons,
                'Las razones de terminación de contrato consultados exitosamente.',
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
