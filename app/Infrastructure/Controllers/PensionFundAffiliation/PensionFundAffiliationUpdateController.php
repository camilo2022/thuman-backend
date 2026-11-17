<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFundAffiliation;

use App\Application\PensionFundAffiliationUseCase\PensionFundAffiliationUpdateUseCase;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationUpdateRequest;
use App\Http\Resources\PensionFundAffiliation\PensionFundAffiliationResource;
use App\Infrastructure\Repositories\PensionFundAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundAffiliationUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $pensionFundAffiliationRepository;

    public function __construct(PensionFundAffiliationRepository $pensionFundAffiliationRepository)
    {
        $this->pensionFundAffiliationRepository = $pensionFundAffiliationRepository;
    }

    public function __invoke(PensionFundAffiliationUpdateRequest $request, int $pensionFundAffiliationId)
    {
        try {
            $pensionFundAffiliationId = new PensionFundAffiliationId($pensionFundAffiliationId ?? null);
            $pensionFundAffiliationUpdateUseCase = new PensionFundAffiliationUpdateUseCase($this->pensionFundAffiliationRepository);
            $pensionFundAffiliation = new PensionFundAffiliationResource($pensionFundAffiliationUpdateUseCase->__invoke($request, $pensionFundAffiliationId));
            return $this->successResponse(
                $pensionFundAffiliation,
                'La afiliación al fondo de pensión fue actualizado exitosamente.',
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
