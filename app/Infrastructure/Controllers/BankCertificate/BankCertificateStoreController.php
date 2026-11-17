<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\BankCertificate;

use App\Application\BankCertificateUseCase\BankCertificateStoreUseCase;
use App\Http\Requests\BankCertificate\BankCertificateStoreRequest;
use App\Http\Resources\BankCertificate\BankCertificateResource;
use App\Infrastructure\Repositories\BankCertificateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BankCertificateStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $bankCertificateRepository;

    public function __construct(BankCertificateRepository $bankCertificateRepository)
    {
        $this->bankCertificateRepository = $bankCertificateRepository;
    }

    public function __invoke(BankCertificateStoreRequest $request)
    {
        try {
            $bankCertificateStoreUseCase = new BankCertificateStoreUseCase($this->bankCertificateRepository);
            $bankCertificate = new BankCertificateResource($bankCertificateStoreUseCase->__invoke($request));
            return $this->successResponse(
                $bankCertificate,
                'La certificación bancaria fue registrada exitosamente.',
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
