<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\OrganizationalStructure;

use App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase\OrganizationalStructureInactivedUseCase;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureInactivedRequest;
use App\Http\Resources\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureResource;
use App\Infrastructure\Repositories\OrganizationalStructureRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class OrganizationalStructureInactivedController
{
    use ApiResponser;
    use ApiMessage;
    private $organizationalStructureRepository;

    public function __construct(OrganizationalStructureRepository $organizationalStructureRepository)
    {
        $this->organizationalStructureRepository = $organizationalStructureRepository;
    }

    public function __invoke(OrganizationalStructureInactivedRequest $request, int $organizationalStructureId)
    {
        try {
            $organizationalStructureId = new OrganizationalStructureId($organizationalStructureId ?? null);
            $organizationalStructureInactivedUseCase = new OrganizationalStructureInactivedUseCase($this->organizationalStructureRepository);
            $organizationalStructure = new OrganizationalStructureResource($organizationalStructureInactivedUseCase->__invoke($request, $organizationalStructureId));
            return $this->successResponse(
                $organizationalStructure,
                'La solicitud de la estructura organizacional fue actualizada exitosamente a: INACTIVO.',
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
