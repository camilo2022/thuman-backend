<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Termination;

use App\Application\TerminationUseCase\TerminationAllUseCase;
use App\Http\Requests\Termination\TerminationAllRequest;
use App\Http\Resources\Termination\TerminationCollection;
use App\Infrastructure\Repositories\TerminationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationAllController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationRepository;

    public function __construct(TerminationRepository $terminationRepository)
    {
        $this->terminationRepository = $terminationRepository;
    }

    public function __invoke(TerminationAllRequest $request)
    {
        try {
            $terminationAllUseCase = new TerminationAllUseCase($this->terminationRepository);
            $terminations = new TerminationCollection($terminationAllUseCase->__invoke($request));
            return $this->successResponse(
                $terminations,
                'Las terminaciones de contrato consultadas exitosamente.',
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
