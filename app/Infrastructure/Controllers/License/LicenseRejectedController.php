<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\License;

use App\Application\LicenseUseCase\LicenseRejectedUseCase;
use App\Domain\License\Values\LicenseId;
use App\Http\Requests\License\LicenseRejectedRequest;
use App\Http\Resources\License\LicenseResource;
use App\Infrastructure\Repositories\LicenseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseRejectedController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseRepository;

    public function __construct(LicenseRepository $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;
    }

    public function __invoke(LicenseRejectedRequest $request, int $licenseId)
    {
        try {
            $licenseId = new LicenseId($licenseId ?? null);
            $licenseRejectedUseCase = new LicenseRejectedUseCase($this->licenseRepository);
            $license = new LicenseResource($licenseRejectedUseCase->__invoke($request, $licenseId));
            return $this->successResponse(
                $license,
                'La solicitud de licencia fue actualizada exitosamente a: RECHAZADO.',
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
