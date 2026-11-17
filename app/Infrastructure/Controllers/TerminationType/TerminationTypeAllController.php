<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType;

use App\Application\TerminationTypeUseCase\TerminationTypeAllUseCase;
use App\Http\Requests\TerminationType\TerminationTypeAllRequest;
use App\Http\Resources\TerminationType\TerminationTypeCollection;
use App\Infrastructure\Repositories\TerminationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationTypeRepository;

    public function __construct(TerminationTypeRepository $terminationTypeRepository)
    {
        $this->terminationTypeRepository = $terminationTypeRepository;
    }

    public function __invoke(TerminationTypeAllRequest $request)
    {
        try {
            $terminationTypeAllUseCase = new TerminationTypeAllUseCase($this->terminationTypeRepository);
            $terminationTypes = new TerminationTypeCollection($terminationTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $terminationTypes,
                'Los tipos de terminación de contratos consultados exitosamente.',
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
