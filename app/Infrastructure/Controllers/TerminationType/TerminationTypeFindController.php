<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType;

use App\Application\TerminationTypeUseCase\TerminationTypeFindUseCase;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Resources\TerminationType\TerminationTypeResource;
use App\Infrastructure\Repositories\TerminationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationTypeRepository;

    public function __construct(TerminationTypeRepository $terminationTypeRepository)
    {
        $this->terminationTypeRepository = $terminationTypeRepository;
    }

    public function __invoke(int $terminationTypeId)
    {
        try {
            $terminationTypeId = new TerminationTypeId($terminationTypeId ?? null);
            $terminationTypeFindUseCase = new TerminationTypeFindUseCase($this->terminationTypeRepository);
            $terminationType = new TerminationTypeResource($terminationTypeFindUseCase->__invoke($terminationTypeId));
            return $this->successResponse(
                $terminationType,
                'El tipo de terminación de contrato fue encontrada exitosamente.',
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
