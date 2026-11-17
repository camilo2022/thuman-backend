<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFund;

use App\Application\PensionFundUseCase\PensionFundToggleUseCase;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Http\Requests\PensionFund\PensionFundToggleRequest;
use App\Http\Resources\PensionFund\PensionFundResource;
use App\Infrastructure\Repositories\PensionFundRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $pensionFundRepository;

    public function __construct(PensionFundRepository $pensionFundRepository)
    {
        $this->pensionFundRepository = $pensionFundRepository;
    }

    public function __invoke(PensionFundToggleRequest $request, int $pensionFundId)
    {
        try {
            $pensionFundId = new PensionFundId($pensionFundId ?? null);
            $pensionFundToggleUseCase = new PensionFundToggleUseCase($this->pensionFundRepository);
            $pensionFund = new PensionFundResource($pensionFundToggleUseCase->__invoke($request, $pensionFundId));
            return $this->successResponse(
                $pensionFund,
                'El país fue asignado/removido al fondo de pensión exitosamente.',
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
