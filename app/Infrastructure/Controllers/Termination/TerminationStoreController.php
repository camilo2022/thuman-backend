<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Termination;

use App\Application\TerminationUseCase\TerminationStoreUseCase;
use App\Http\Requests\Termination\TerminationStoreRequest;
use App\Http\Resources\Termination\TerminationResource;
use App\Infrastructure\Repositories\TerminationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationRepository;

    public function __construct(TerminationRepository $terminationRepository)
    {
        $this->terminationRepository = $terminationRepository;
    }

    public function __invoke(TerminationStoreRequest $request)
    {
        try {
            $terminationStoreUseCase = new TerminationStoreUseCase($this->terminationRepository);
            $termination = new TerminationResource($terminationStoreUseCase->__invoke($request));
            return $this->successResponse(
                $termination,
                'La terminación de contrato registrado exitosamente.',
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
