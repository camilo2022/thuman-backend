<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\LicenseType;

use App\Application\LicenseTypeUseCase\LicenseTypeRestoreUseCase;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Http\Resources\LicenseType\LicenseTypeResource;
use App\Infrastructure\Repositories\LicenseTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseTypeRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseTypeRepository;

    public function __construct(LicenseTypeRepository $licenseTypeRepository)
    {
        $this->licenseTypeRepository = $licenseTypeRepository;
    }

    public function __invoke(int $licenseTypeId)
    {
        try {
            $licenseTypeId = new LicenseTypeId($licenseTypeId);
            $licenseTypeRestoreUseCase = new LicenseTypeRestoreUseCase($this->licenseTypeRepository);
            $licenseType = new LicenseTypeResource($licenseTypeRestoreUseCase->__invoke($licenseTypeId));
            return $this->successResponse(
                $licenseType,
                'El tipo de licencia fue activado exitosamente.',
                204
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
