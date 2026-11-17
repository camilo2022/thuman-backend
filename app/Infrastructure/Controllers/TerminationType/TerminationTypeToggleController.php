<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType;

use App\Application\TerminationTypeUseCase\TerminationTypeToggleUseCase;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Requests\TerminationType\TerminationTypeToggleRequest;
use App\Http\Resources\TerminationType\TerminationTypeResource;
use App\Infrastructure\Repositories\TerminationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationTypeToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationTypeRepository;

    public function __construct(TerminationTypeRepository $terminationTypeRepository)
    {
        $this->terminationTypeRepository = $terminationTypeRepository;
    }

    public function __invoke(TerminationTypeToggleRequest $request, int $terminationTypeId)
    {
        try {
            $terminationTypeId = new TerminationTypeId($terminationTypeId ?? null);
            $terminationTypeToggleUseCase = new TerminationTypeToggleUseCase($this->terminationTypeRepository);
            $terminationType = new TerminationTypeResource($terminationTypeToggleUseCase->__invoke($request, $terminationTypeId));
            return $this->successResponse(
                $terminationType,
                'El tipo de terminación de contrato fue actualizado exitosamente.',
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
