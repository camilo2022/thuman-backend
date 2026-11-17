<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFundAffiliation;

use App\Application\PensionFundAffiliationUseCase\PensionFundAffiliationFindUseCase;
use App\Domain\PensionFundAffiliation\Values\PensionFundAffiliationId;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationFindRequest;
use App\Http\Resources\PensionFundAffiliation\PensionFundAffiliationResource;
use App\Infrastructure\Repositories\PensionFundAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundAffiliationFindController
{
    use ApiResponser;
    use ApiMessage;
    private $pensionFundAffiliationRepository;

    public function __construct(PensionFundAffiliationRepository $pensionFundAffiliationRepository)
    {
        $this->pensionFundAffiliationRepository = $pensionFundAffiliationRepository;
    }

    public function __invoke(PensionFundAffiliationFindRequest $request, int|null $pensionFundAffiliationId = null)
    {
        try {
            $pensionFundAffiliationId = new PensionFundAffiliationId($pensionFundAffiliationId);
            $pensionFundAffiliationFindUseCase = new PensionFundAffiliationFindUseCase($this->pensionFundAffiliationRepository);
            $pensionFundAffiliation = new PensionFundAffiliationResource($pensionFundAffiliationFindUseCase->__invoke($request, $pensionFundAffiliationId));
            return $this->successResponse(
                $pensionFundAffiliation,
                'La afiliación al fondo de pensión fue encontrado exitosamente.',
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
