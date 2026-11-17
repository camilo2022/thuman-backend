<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\BankCertificate;

use App\Application\BankCertificateUseCase\BankCertificateFindUseCase;
use App\Domain\BankCertificate\Values\BankCertificateId;
use App\Http\Requests\BankCertificate\BankCertificateFindRequest;
use App\Http\Resources\BankCertificate\BankCertificateResource;
use App\Infrastructure\Repositories\BankCertificateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BankCertificateFindController
{
    use ApiResponser;
    use ApiMessage;
    private $bankCertificateRepository;

    public function __construct(BankCertificateRepository $bankCertificateRepository)
    {
        $this->bankCertificateRepository = $bankCertificateRepository;
    }

    public function __invoke(BankCertificateFindRequest $request, int|null $bankCertificateId = null)
    {
        try {
            $bankCertificateId = new BankCertificateId($bankCertificateId);
            $bankCertificateFindUseCase = new BankCertificateFindUseCase($this->bankCertificateRepository);
            $bankCertificate = new BankCertificateResource($bankCertificateFindUseCase->__invoke($request, $bankCertificateId));
            return $this->successResponse(
                $bankCertificate,
                'La certificación bancaria fue encontrada exitosamente.',
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
