<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocumentCertificate;

use App\Application\FiscalDocumentCertificateUseCase\FiscalDocumentCertificateRefreshedUseCase;
use App\Domain\FiscalDocumentCertificate\Values\FiscalDocumentCertificateId;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateRefreshedRequest;
use App\Http\Resources\FiscalDocumentCertificate\FiscalDocumentCertificateResource;
use App\Infrastructure\Repositories\FiscalDocumentCertificateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentCertificateRefreshedController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentCertificateRepository;

    public function __construct(FiscalDocumentCertificateRepository $fiscalDocumentCertificateRepository)
    {
        $this->fiscalDocumentCertificateRepository = $fiscalDocumentCertificateRepository;
    }

    public function __invoke(FiscalDocumentCertificateRefreshedRequest $request, int $fiscalDocumentCertificateId)
    {
        try {
            $fiscalDocumentCertificateId = new FiscalDocumentCertificateId($fiscalDocumentCertificateId ?? null);
            $fiscalDocumentCertificateRefreshedUseCase = new FiscalDocumentCertificateRefreshedUseCase($this->fiscalDocumentCertificateRepository);
            $fiscalDocumentCertificate = new FiscalDocumentCertificateResource($fiscalDocumentCertificateRefreshedUseCase->__invoke($request, $fiscalDocumentCertificateId));
            return $this->successResponse(
                $fiscalDocumentCertificate,
                'La certificación documento fiscal fue actualizada exitosamente a: ACTUALIZAR.',
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
