<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\LicenseType;

use App\Application\LicenseTypeUseCase\LicenseTypeStoreUseCase;
use App\Http\Requests\LicenseType\LicenseTypeStoreRequest;
use App\Http\Resources\LicenseType\LicenseTypeResource;
use App\Infrastructure\Repositories\LicenseTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseTypeStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseTypeRepository;

    public function __construct(LicenseTypeRepository $licenseTypeRepository)
    {
        $this->licenseTypeRepository = $licenseTypeRepository;
    }

    public function __invoke(LicenseTypeStoreRequest $request)
    {
        try {
            $licenseTypeStoreUseCase = new LicenseTypeStoreUseCase($this->licenseTypeRepository);
            $licenseType = new LicenseTypeResource($licenseTypeStoreUseCase->__invoke($request));
            return $this->successResponse(
                $licenseType,
                'El tipo de licencia fue registrado exitosamente.',
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
