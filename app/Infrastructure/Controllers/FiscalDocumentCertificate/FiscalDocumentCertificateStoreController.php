<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocumentCertificate;

use App\Application\FiscalDocumentCertificateUseCase\FiscalDocumentCertificateStoreUseCase;
use App\Http\Requests\FiscalDocumentCertificate\FiscalDocumentCertificateStoreRequest;
use App\Http\Resources\FiscalDocumentCertificate\FiscalDocumentCertificateResource;
use App\Infrastructure\Repositories\FiscalDocumentCertificateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentCertificateStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentCertificateRepository;

    public function __construct(FiscalDocumentCertificateRepository $fiscalDocumentCertificateRepository)
    {
        $this->fiscalDocumentCertificateRepository = $fiscalDocumentCertificateRepository;
    }

    public function __invoke(FiscalDocumentCertificateStoreRequest $request)
    {
        try {
            $fiscalDocumentCertificateStoreUseCase = new FiscalDocumentCertificateStoreUseCase($this->fiscalDocumentCertificateRepository);
            $fiscalDocumentCertificate = new FiscalDocumentCertificateResource($fiscalDocumentCertificateStoreUseCase->__invoke($request));
            return $this->successResponse(
                $fiscalDocumentCertificate,
                'La certificación documento fiscal fue registrada exitosamente.',
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
