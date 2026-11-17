<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\TerminationType;

use App\Application\TerminationTypeUseCase\TerminationTypeSettingUseCase;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Http\Requests\TerminationType\TerminationTypeSettingRequest;
use App\Http\Resources\TerminationType\TerminationTypeResource;
use App\Infrastructure\Repositories\TerminationTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class TerminationTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $terminationTypeRepository;

    public function __construct(TerminationTypeRepository $terminationTypeRepository)
    {
        $this->terminationTypeRepository = $terminationTypeRepository;
    }

    public function __invoke(TerminationTypeSettingRequest $request, int $terminationTypeId)
    {
        try {
            $terminationTypeId = new TerminationTypeId($terminationTypeId ?? null);
            $terminationTypeSettingUseCase = new TerminationTypeSettingUseCase($this->terminationTypeRepository);
            $terminationType = new TerminationTypeResource($terminationTypeSettingUseCase->__invoke($request, $terminationTypeId));
            return $this->successResponse(
                $terminationType,
                'La configuración del tipo de terminación de contrato fue realizada exitosamente.',
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
