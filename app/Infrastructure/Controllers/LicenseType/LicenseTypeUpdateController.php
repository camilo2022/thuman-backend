<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\LicenseType;

use App\Application\LicenseTypeUseCase\LicenseTypeUpdateUseCase;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Http\Requests\LicenseType\LicenseTypeUpdateRequest;
use App\Http\Resources\LicenseType\LicenseTypeResource;
use App\Infrastructure\Repositories\LicenseTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseTypeUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseTypeRepository;

    public function __construct(LicenseTypeRepository $licenseTypeRepository)
    {
        $this->licenseTypeRepository = $licenseTypeRepository;
    }

    public function __invoke(LicenseTypeUpdateRequest $request, int $licenseTypeId)
    {
        try {
            $licenseTypeId = new LicenseTypeId($licenseTypeId ?? null);
            $licenseTypeUpdateUseCase = new LicenseTypeUpdateUseCase($this->licenseTypeRepository);
            $licenseType = new LicenseTypeResource($licenseTypeUpdateUseCase->__invoke($request, $licenseTypeId));
            return $this->successResponse(
                $licenseType,
                'El tipo de licencia fue actualizado exitosamente.',
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
