<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocumentCertificate;

use App\Application\FiscalDocumentCertificateUseCase\FiscalDocumentCertificateApprovedUseCase;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateApprovedRequest;
use App\Http\Resources\FiscalDocumentCertificate\FiscalDocumentCertificateResource;
use App\Infrastructure\Repositories\FiscalDocumentCertificateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentCertificateApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentCertificateRepository;

    public function __construct(FiscalDocumentCertificateRepository $fiscalDocumentCertificateRepository)
    {
        $this->fiscalDocumentCertificateRepository = $fiscalDocumentCertificateRepository;
    }

    public function __invoke(FiscalDocumentCertificateApprovedRequest $request, int $fiscalDocumentCertificateId)
    {
        try {
            $fiscalDocumentCertificateId = new FiscalDocumentCertificateId($fiscalDocumentCertificateId ?? null);
            $fiscalDocumentCertificateApprovedUseCase = new FiscalDocumentCertificateApprovedUseCase($this->fiscalDocumentCertificateRepository);
            $fiscalDocumentCertificate = new FiscalDocumentCertificateResource($fiscalDocumentCertificateApprovedUseCase->__invoke($request, $fiscalDocumentCertificateId));
            return $this->successResponse(
                $fiscalDocumentCertificate,
                'La certificación documento fiscal fue actualizada exitosamente a: APROBADO.',
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
