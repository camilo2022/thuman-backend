<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Termination;

use App\Application\TerminationUseCase\TerminationFindUseCase;
use App\Domain\Termination\Values\TerminationId;
use App\Http\Resources\Termination\TerminationResource;
use App\Infrastructure\Repositories\TerminationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationFindController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationRepository;

    public function __construct(TerminationRepository $terminationRepository)
    {
        $this->terminationRepository = $terminationRepository;
    }

    public function __invoke(int $terminationId)
    {
        try {
            $terminationId = new TerminationId($terminationId ?? null);
            $terminationFindUseCase = new TerminationFindUseCase($this->terminationRepository);
            $termination = new TerminationResource($terminationFindUseCase->__invoke($terminationId));
            return $this->successResponse(
                $termination,
                'La terminación de contrato fue encontrada exitosamente.',
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
