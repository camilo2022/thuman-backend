<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CertificateType;

use App\Application\CertificateTypeUseCase\CertificateTypeFindUseCase;
use App\Domain\CertificateType\Values\CertificateTypeId;
use App\Http\Resources\CertificateType\CertificateTypeResource;
use App\Infrastructure\Repositories\CertificateTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CertificateTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $certificateTypeRepository;

    public function __construct(CertificateTypeRepository $certificateTypeRepository)
    {
        $this->certificateTypeRepository = $certificateTypeRepository;
    }

    public function __invoke(int $certificateTypeId)
    {
        try {
            $certificateTypeId = new CertificateTypeId($certificateTypeId ?? null);
            $certificateTypeFindUseCase = new CertificateTypeFindUseCase($this->certificateTypeRepository);
            $certificateType = new CertificateTypeResource($certificateTypeFindUseCase->__invoke($certificateTypeId));
            return $this->successResponse(
                $certificateType,
                'El tipo de certificado fue encontrada exitosamente.',
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
