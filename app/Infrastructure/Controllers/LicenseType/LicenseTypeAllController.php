<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\LicenseType;

use App\Application\LicenseTypeUseCase\LicenseTypeAllUseCase;
use App\Http\Requests\LicenseType\LicenseTypeAllRequest;
use App\Http\Resources\LicenseType\LicenseTypeCollection;
use App\Infrastructure\Repositories\LicenseTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseTypeRepository;

    public function __construct(LicenseTypeRepository $licenseTypeRepository)
    {
        $this->licenseTypeRepository = $licenseTypeRepository;
    }

    public function __invoke(LicenseTypeAllRequest $request)
    {
        try {
            $licenseTypeAllUseCase = new LicenseTypeAllUseCase($this->licenseTypeRepository);
            $licenseTypes = new LicenseTypeCollection($licenseTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $licenseTypes,
                'Los tipos de licencias fueron consultados exitosamente.',
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
