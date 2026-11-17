<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\DisabilityType;

use App\Application\DisabilityTypeUseCase\DisabilityTypeAllUseCase;
use App\Http\Requests\DisabilityType\DisabilityTypeAllRequest;
use App\Http\Resources\DisabilityType\DisabilityTypeCollection;
use App\Infrastructure\Repositories\DisabilityTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class DisabilityTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $disabilityTypeRepository;

    public function __construct(DisabilityTypeRepository $disabilityTypeRepository)
    {
        $this->disabilityTypeRepository = $disabilityTypeRepository;
    }

    public function __invoke(DisabilityTypeAllRequest $request)
    {
        try {
            $disabilityTypeAllUseCase = new DisabilityTypeAllUseCase($this->disabilityTypeRepository);
            $disabilityTypes = new DisabilityTypeCollection($disabilityTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $disabilityTypes,
                'Los tipos de incapacidades consultados exitosamente.',
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
