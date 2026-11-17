<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType\Reason;

use App\Application\TerminationTypeUseCase\ReasonUseCase\ReasonStoreUseCase;
use App\Http\Requests\TerminationType\Reason\ReasonStoreRequest;
use App\Http\Resources\TerminationType\Reason\ReasonResource;
use App\Infrastructure\Repositories\ReasonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ReasonStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $reasonRepository;

    public function __construct(ReasonRepository $reasonRepository)
    {
        $this->reasonRepository = $reasonRepository;
    }

    public function __invoke(ReasonStoreRequest $request)
    {
        try {
            $reasonStoreUseCase = new ReasonStoreUseCase($this->reasonRepository);
            $reason = new ReasonResource($reasonStoreUseCase->__invoke($request));
            return $this->successResponse(
                $reason,
                'La razón de terminación de contrato fue registrado exitosamente.',
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
