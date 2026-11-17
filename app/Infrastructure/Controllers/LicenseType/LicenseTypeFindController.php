<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\LicenseType;

use App\Application\LicenseTypeUseCase\LicenseTypeFindUseCase;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Http\Resources\LicenseType\LicenseTypeResource;
use App\Infrastructure\Repositories\LicenseTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseTypeFindController
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
            $licenseTypeId = new LicenseTypeId($licenseTypeId ?? null);
            $licenseTypeFindUseCase = new LicenseTypeFindUseCase($this->licenseTypeRepository);
            $licenseType = new LicenseTypeResource($licenseTypeFindUseCase->__invoke($licenseTypeId));
            return $this->successResponse(
                $licenseType,
                'El tipo de licencia fue encontrado exitosamente.',
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
