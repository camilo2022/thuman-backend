<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\LicenseType;

use App\Application\LicenseTypeUseCase\LicenseTypeSettingUseCase;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Http\Requests\LicenseType\LicenseTypeSettingRequest;
use App\Http\Resources\LicenseType\LicenseTypeResource;
use App\Infrastructure\Repositories\LicenseTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseTypeSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseTypeRepository;

    public function __construct(LicenseTypeRepository $licenseTypeRepository)
    {
        $this->licenseTypeRepository = $licenseTypeRepository;
    }

    public function __invoke(LicenseTypeSettingRequest $request, int $licenseTypeId)
    {
        try {
            $licenseTypeId = new LicenseTypeId($licenseTypeId ?? null);
            $licenseTypeSettingUseCase = new LicenseTypeSettingUseCase($this->licenseTypeRepository);
            $licenseType = new LicenseTypeResource($licenseTypeSettingUseCase->__invoke($request, $licenseTypeId));
            return $this->successResponse(
                $licenseType,
                'La configuración del tipo de licencia fue realizada exitosamente.',
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
