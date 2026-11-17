<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\License;

use App\Application\LicenseUseCase\LicenseUpdateUseCase;
use App\Domain\License\Values\LicenseId;
use App\Http\Requests\License\LicenseUpdateRequest;
use App\Http\Resources\License\LicenseResource;
use App\Infrastructure\Repositories\LicenseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseRepository;

    public function __construct(LicenseRepository $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;
    }

    public function __invoke(LicenseUpdateRequest $request, int $licenseId)
    {
        try {
            $licenseId = new LicenseId($licenseId ?? null);
            $licenseUpdateUseCase = new LicenseUpdateUseCase($this->licenseRepository);
            $license = new LicenseResource($licenseUpdateUseCase->__invoke($request, $licenseId));
            return $this->successResponse(
                $license,
                'La solicitud de licencia fue actualizado exitosamente.',
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
