<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType;

use App\Application\TerminationTypeUseCase\TerminationTypeUpdateUseCase;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Requests\TerminationType\TerminationTypeUpdateRequest;
use App\Http\Resources\TerminationType\TerminationTypeResource;
use App\Infrastructure\Repositories\TerminationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationTypeUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationTypeRepository;

    public function __construct(TerminationTypeRepository $terminationTypeRepository)
    {
        $this->terminationTypeRepository = $terminationTypeRepository;
    }

    public function __invoke(TerminationTypeUpdateRequest $request, int $terminationTypeId)
    {
        try {
            $terminationTypeId = new TerminationTypeId($terminationTypeId ?? null);
            $terminationTypeUpdateUseCase = new TerminationTypeUpdateUseCase($this->terminationTypeRepository);
            $terminationType = new TerminationTypeResource($terminationTypeUpdateUseCase->__invoke($request, $terminationTypeId));
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
