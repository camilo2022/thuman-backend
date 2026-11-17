<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFundAffiliation;

use App\Application\PensionFundAffiliationUseCase\PensionFundAffiliationApprovedUseCase;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationApprovedRequest;
use App\Http\Resources\PensionFundAffiliation\PensionFundAffiliationResource;
use App\Infrastructure\Repositories\PensionFundAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundAffiliationApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $pensionFundAffiliationRepository;

    public function __construct(PensionFundAffiliationRepository $pensionFundAffiliationRepository)
    {
        $this->pensionFundAffiliationRepository = $pensionFundAffiliationRepository;
    }

    public function __invoke(PensionFundAffiliationApprovedRequest $request, int $pensionFundAffiliationId)
    {
        try {
            $pensionFundAffiliationId = new PensionFundAffiliationId($pensionFundAffiliationId ?? null);
            $pensionFundAffiliationApprovedUseCase = new PensionFundAffiliationApprovedUseCase($this->pensionFundAffiliationRepository);
            $pensionFundAffiliation = new PensionFundAffiliationResource($pensionFundAffiliationApprovedUseCase->__invoke($request, $pensionFundAffiliationId));
            return $this->successResponse(
                $pensionFundAffiliation,
                'La afiliación al fondo de pensión fue actualizado exitosamente a: APROBADO.',
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
