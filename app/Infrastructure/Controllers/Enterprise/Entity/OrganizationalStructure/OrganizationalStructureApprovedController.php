<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\OrganizationalStructure;

use App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase\OrganizationalStructureApprovedUseCase;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureApprovedRequest;
use App\Http\Resources\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureResource;
use App\Infrastructure\Repositories\OrganizationalStructureRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class OrganizationalStructureApprovedController
{
    use ApiResponser;
    use ApiMessage;
    private $organizationalStructureRepository;

    public function __construct(OrganizationalStructureRepository $organizationalStructureRepository)
    {
        $this->organizationalStructureRepository = $organizationalStructureRepository;
    }

    public function __invoke(OrganizationalStructureApprovedRequest $request, int $organizationalStructureId)
    {
        try {
            $organizationalStructureId = new OrganizationalStructureId($organizationalStructureId ?? null);
            $organizationalStructureApprovedUseCase = new OrganizationalStructureApprovedUseCase($this->organizationalStructureRepository);
            $organizationalStructure = new OrganizationalStructureResource($organizationalStructureApprovedUseCase->__invoke($request, $organizationalStructureId));
            return $this->successResponse(
                $organizationalStructure,
                'La solicitud de la estructura organizacional fue actualizada exitosamente a: APROBADO.',
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
