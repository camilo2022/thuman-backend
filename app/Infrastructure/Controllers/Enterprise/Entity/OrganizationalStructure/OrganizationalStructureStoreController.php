<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\OrganizationalStructure;

use App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase\OrganizationalStructureStoreUseCase;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureStoreRequest;
use App\Http\Resources\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureResource;
use App\Infrastructure\Repositories\OrganizationalStructureRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class OrganizationalStructureStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $organizationalStructureRepository;

    public function __construct(OrganizationalStructureRepository $organizationalStructureRepository)
    {
        $this->organizationalStructureRepository = $organizationalStructureRepository;
    }

    public function __invoke(OrganizationalStructureStoreRequest $request)
    {
        try {
            $organizationalStructureStoreUseCase = new OrganizationalStructureStoreUseCase($this->organizationalStructureRepository);
            $organizationalStructure = new OrganizationalStructureResource($organizationalStructureStoreUseCase->__invoke($request));
            return $this->successResponse(
                $organizationalStructure,
                'La solicitud de permiso fue registrada exitosamente.',
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
