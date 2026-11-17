<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\License;

use App\Application\LicenseUseCase\LicenseFindUseCase;
use App\Domain\License\Values\LicenseId;
use App\Http\Resources\License\LicenseResource;
use App\Infrastructure\Repositories\LicenseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseFindController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseRepository;

    public function __construct(LicenseRepository $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;
    }

    public function __invoke(int $licenseId)
    {
        try {
            $licenseId = new LicenseId($licenseId ?? null);
            $licenseFindUseCase = new LicenseFindUseCase($this->licenseRepository);
            $license = new LicenseResource($licenseFindUseCase->__invoke($licenseId));
            return $this->successResponse(
                $license,
                'La solicitud de licencia fue encontrada exitosamente.',
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
