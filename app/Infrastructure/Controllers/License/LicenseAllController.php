<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\License;

use App\Application\LicenseUseCase\LicenseAllUseCase;
use App\Http\Requests\License\LicenseAllRequest;
use App\Http\Resources\License\LicenseCollection;
use App\Infrastructure\Repositories\LicenseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseAllController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseRepository;

    public function __construct(LicenseRepository $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;
    }

    public function __invoke(LicenseAllRequest $request)
    {
        try {
            $licenseAllUseCase = new LicenseAllUseCase($this->licenseRepository);
            $licenses = new LicenseCollection($licenseAllUseCase->__invoke($request));
            return $this->successResponse(
                $licenses,
                'Las solicitudes de licencias consultadas exitosamente.',
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
