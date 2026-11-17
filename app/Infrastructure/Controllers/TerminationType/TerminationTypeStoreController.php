<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType;

use App\Application\TerminationTypeUseCase\TerminationTypeStoreUseCase;
use App\Http\Requests\TerminationType\TerminationTypeStoreRequest;
use App\Http\Resources\TerminationType\TerminationTypeResource;
use App\Infrastructure\Repositories\TerminationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationTypeRepository;

    public function __construct(TerminationTypeRepository $terminationTypeRepository)
    {
        $this->terminationTypeRepository = $terminationTypeRepository;
    }

    public function __invoke(TerminationTypeStoreRequest $request)
    {
        try {
            $terminationTypeStoreUseCase = new TerminationTypeStoreUseCase($this->terminationTypeRepository);
            $terminationType = new TerminationTypeResource($terminationTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $terminationType,
                'El tipo de terminación de contrato fue registrado exitosamente.',
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
