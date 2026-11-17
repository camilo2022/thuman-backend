<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\License;

use App\Application\LicenseUseCase\LicenseStoreUseCase;
use App\Http\Requests\License\LicenseStoreRequest;
use App\Http\Resources\License\LicenseResource;
use App\Infrastructure\Repositories\LicenseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseRepository;

    public function __construct(LicenseRepository $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;
    }

    public function __invoke(LicenseStoreRequest $request)
    {
        try {
            $licenseStoreUseCase = new LicenseStoreUseCase($this->licenseRepository);
            $license = new LicenseResource($licenseStoreUseCase->__invoke($request));
            return $this->successResponse(
                $license,
                'La solicitud de licencia registrado exitosamente.',
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
