<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CertificateType;

use App\Application\CertificateTypeUseCase\CertificateTypeAllUseCase;
use App\Http\Requests\CertificateType\CertificateTypeAllRequest;
use App\Http\Resources\CertificateType\CertificateTypeCollection;
use App\Infrastructure\Repositories\CertificateTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CertificateTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $certificateTypeRepository;

    public function __construct(CertificateTypeRepository $certificateTypeRepository)
    {
        $this->certificateTypeRepository = $certificateTypeRepository;
    }

    public function __invoke(CertificateTypeAllRequest $request)
    {
        try {
            $certificateTypeAllUseCase = new CertificateTypeAllUseCase($this->certificateTypeRepository);
            $certificateTypes = new CertificateTypeCollection($certificateTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $certificateTypes,
                'Los tipos de certificados consultados exitosamente.',
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
