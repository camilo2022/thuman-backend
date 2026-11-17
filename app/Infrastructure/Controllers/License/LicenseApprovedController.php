<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\License;

use App\Application\LicenseUseCase\LicenseApprovedUseCase;
use App\Domain\License\Values\LicenseId;
use App\Http\Requests\License\LicenseApprovedRequest;
use App\Http\Resources\License\LicenseResource;
use App\Infrastructure\Repositories\LicenseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class LicenseApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $licenseRepository;

    public function __construct(LicenseRepository $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;
    }

    public function __invoke(LicenseApprovedRequest $request, int $licenseId)
    {
        try {
            $licenseId = new LicenseId($licenseId ?? null);
            $licenseApprovedUseCase = new LicenseApprovedUseCase($this->licenseRepository);
            $license = new LicenseResource($licenseApprovedUseCase->__invoke($request, $licenseId));
            return $this->successResponse(
                $license,
                'La solicitud de licencia fue actualizada exitosamente a: APROBADO.',
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
