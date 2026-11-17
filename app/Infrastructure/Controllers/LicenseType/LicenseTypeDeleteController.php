<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\LicenseType;

use App\Application\LicenseTypeUseCase\LicenseTypeDeleteUseCase;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Http\Resources\LicenseType\LicenseTypeResource;
use App\Infrastructure\Repositories\LicenseTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LicenseTypeDeleteController
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
            $licenseTypeId = new LicenseTypeId($licenseTypeId);
            $licenseTypeDeleteUseCase = new LicenseTypeDeleteUseCase($this->licenseTypeRepository);
            $licenseType = new LicenseTypeResource($licenseTypeDeleteUseCase->__invoke($licenseTypeId));
            return $this->successResponse(
                $licenseType,
                'El tipo de licencia fue inactivado exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
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
