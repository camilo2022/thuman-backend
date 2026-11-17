<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocumentCertificate;

use App\Application\FiscalDocumentCertificateUseCase\FiscalDocumentCertificateUpdateUseCase;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateUpdateRequest;
use App\Http\Resources\FiscalDocumentCertificate\FiscalDocumentCertificateResource;
use App\Infrastructure\Repositories\FiscalDocumentCertificateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentCertificateUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentCertificateRepository;

    public function __construct(FiscalDocumentCertificateRepository $fiscalDocumentCertificateRepository)
    {
        $this->fiscalDocumentCertificateRepository = $fiscalDocumentCertificateRepository;
    }

    public function __invoke(FiscalDocumentCertificateUpdateRequest $request, int $fiscalDocumentCertificateId)
    {
        try {
            $fiscalDocumentCertificateId = new FiscalDocumentCertificateId($fiscalDocumentCertificateId ?? null);
            $fiscalDocumentCertificateUpdateUseCase = new FiscalDocumentCertificateUpdateUseCase($this->fiscalDocumentCertificateRepository);
            $fiscalDocumentCertificate = new FiscalDocumentCertificateResource($fiscalDocumentCertificateUpdateUseCase->__invoke($request, $fiscalDocumentCertificateId));
            return $this->successResponse(
                $fiscalDocumentCertificate,
                'La certificación documento fiscal fue actualizada exitosamente.',
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
