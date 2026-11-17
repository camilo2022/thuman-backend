<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\OrganizationalStructure;

use App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase\OrganizationalStructureAllUseCase;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureAllRequest;
use App\Http\Resources\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureCollection;
use App\Infrastructure\Repositories\OrganizationalStructureRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class OrganizationalStructureAllController
{
    use ApiResponser;
    use ApiMessage;
    private $organizationalStructureRepository;

    public function __construct(OrganizationalStructureRepository $organizationalStructureRepository)
    {
        $this->organizationalStructureRepository = $organizationalStructureRepository;
    }

    public function __invoke(OrganizationalStructureAllRequest $request)
    {
        try {
            $organizationalStructureAllUseCase = new OrganizationalStructureAllUseCase($this->organizationalStructureRepository);
            $organizationalStructures = new OrganizationalStructureCollection($organizationalStructureAllUseCase->__invoke($request));
            return $this->successResponse(
                $organizationalStructures,
                'La estructura organizationales fueron consultadas exitosamente.',
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
