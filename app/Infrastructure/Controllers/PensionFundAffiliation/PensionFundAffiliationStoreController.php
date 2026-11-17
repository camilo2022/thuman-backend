<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\PensionFundAffiliation;

use App\Application\PensionFundAffiliationUseCase\PensionFundAffiliationStoreUseCase;
use App\Http\Requests\PensionFundAffiliation\PensionFundAffiliationStoreRequest;
use App\Http\Resources\PensionFundAffiliation\PensionFundAffiliationResource;
use App\Infrastructure\Repositories\PensionFundAffiliationRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class PensionFundAffiliationStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $pensionFundAffiliationRepository;

    public function __construct(PensionFundAffiliationRepository $pensionFundAffiliationRepository)
    {
        $this->pensionFundAffiliationRepository = $pensionFundAffiliationRepository;
    }

    public function __invoke(PensionFundAffiliationStoreRequest $request)
    {
        try {
            $pensionFundAffiliationStoreUseCase = new PensionFundAffiliationStoreUseCase($this->pensionFundAffiliationRepository);
            $pensionFundAffiliation = new PensionFundAffiliationResource($pensionFundAffiliationStoreUseCase->__invoke($request));
            return $this->successResponse(
                $pensionFundAffiliation,
                'La afiliación al fondo de pensión fue registrado exitosamente.',
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
