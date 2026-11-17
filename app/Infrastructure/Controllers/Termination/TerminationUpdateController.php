<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Termination;

use App\Application\TerminationUseCase\TerminationUpdateUseCase;
use App\Domain\Termination\Values\TerminationId;
use App\Http\Requests\Termination\TerminationUpdateRequest;
use App\Http\Resources\Termination\TerminationResource;
use App\Infrastructure\Repositories\TerminationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationRepository;

    public function __construct(TerminationRepository $terminationRepository)
    {
        $this->terminationRepository = $terminationRepository;
    }

    public function __invoke(TerminationUpdateRequest $request, int $terminationId)
    {
        try {
            $terminationId = new TerminationId($terminationId ?? null);
            $terminationUpdateUseCase = new TerminationUpdateUseCase($this->terminationRepository);
            $termination = new TerminationResource($terminationUpdateUseCase->__invoke($request, $terminationId));
            return $this->successResponse(
                $termination,
                'La terminación de contrato fue actualizado exitosamente.',
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
