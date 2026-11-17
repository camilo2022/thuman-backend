<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\OrganizationalStructure;

use App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase\OrganizationalStructureFindUseCase;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;
use App\Http\Resources\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureResource;
use App\Infrastructure\Repositories\OrganizationalStructureRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class OrganizationalStructureFindController
{
    use ApiResponser;
    use ApiMessage;
    private $organizationalStructureRepository;

    public function __construct(OrganizationalStructureRepository $organizationalStructureRepository)
    {
        $this->organizationalStructureRepository = $organizationalStructureRepository;
    }

    public function __invoke(int $organizationalStructureId)
    {
        try {
            $organizationalStructureId = new OrganizationalStructureId($organizationalStructureId ?? null);
            $organizationalStructureFindUseCase = new OrganizationalStructureFindUseCase($this->organizationalStructureRepository);
            $organizationalStructure = new OrganizationalStructureResource($organizationalStructureFindUseCase->__invoke($organizationalStructureId));
            return $this->successResponse(
                $organizationalStructure,
                'La estructura organizacional fue encontrada exitosamente.',
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
