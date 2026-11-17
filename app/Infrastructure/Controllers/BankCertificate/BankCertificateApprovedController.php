<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\BankCertificate;

use App\Application\BankCertificateUseCase\BankCertificateApprovedUseCase;
use App\Domain\BankCertificate\Values\BankCertificateId;
use App\Http\Requests\BankCertificate\BankCertificateApprovedRequest;
use App\Http\Resources\BankCertificate\BankCertificateResource;
use App\Infrastructure\Repositories\BankCertificateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class BankCertificateApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $bankCertificateRepository;

    public function __construct(BankCertificateRepository $bankCertificateRepository)
    {
        $this->bankCertificateRepository = $bankCertificateRepository;
    }

    public function __invoke(BankCertificateApprovedRequest $request, int $bankCertificateId)
    {
        try {
            $bankCertificateId = new BankCertificateId($bankCertificateId ?? null);
            $bankCertificateApprovedUseCase = new BankCertificateApprovedUseCase($this->bankCertificateRepository);
            $bankCertificate = new BankCertificateResource($bankCertificateApprovedUseCase->__invoke($request, $bankCertificateId));
            return $this->successResponse(
                $bankCertificate,
                'La certificación bancaria fue actualizada exitosamente a: APROBADO.',
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
