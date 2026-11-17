<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocumentCertificate;

use App\Application\FiscalDocumentCertificateUseCase\FiscalDocumentCertificateFindUseCase;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateFindRequest;
use App\Http\Resources\FiscalDocumentCertificate\FiscalDocumentCertificateResource;
use App\Infrastructure\Repositories\FiscalDocumentCertificateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentCertificateFindController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentCertificateRepository;

    public function __construct(FiscalDocumentCertificateRepository $fiscalDocumentCertificateRepository)
    {
        $this->fiscalDocumentCertificateRepository = $fiscalDocumentCertificateRepository;
    }

    public function __invoke(FiscalDocumentCertificateFindRequest $request, int|null $fiscalDocumentCertificateId = null)
    {
        try {
            $fiscalDocumentCertificateId = new FiscalDocumentCertificateId($fiscalDocumentCertificateId);
            $fiscalDocumentCertificateFindUseCase = new FiscalDocumentCertificateFindUseCase($this->fiscalDocumentCertificateRepository);
            $fiscalDocumentCertificate = new FiscalDocumentCertificateResource($fiscalDocumentCertificateFindUseCase->__invoke($request, $fiscalDocumentCertificateId));
            return $this->successResponse(
                $fiscalDocumentCertificate,
                'La certificación documento fiscal fue encontrada exitosamente.',
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
