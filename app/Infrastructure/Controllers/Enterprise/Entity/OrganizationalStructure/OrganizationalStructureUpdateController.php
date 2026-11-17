<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\OrganizationalStructure;

use App\Application\EnterpriseUseCase\EntityUseCase\OrganizationalStructureUseCase\OrganizationalStructureUpdateUseCase;
use App\Domain\OrganizationalStructure\Values\OrganizationalStructureId;
use App\Http\Requests\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureUpdateRequest;
use App\Http\Resources\Enterprise\Entity\OrganizationalStructure\OrganizationalStructureResource;
use App\Infrastructure\Repositories\OrganizationalStructureRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class OrganizationalStructureUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $organizationalStructureRepository;

    public function __construct(OrganizationalStructureRepository $organizationalStructureRepository)
    {
        $this->organizationalStructureRepository = $organizationalStructureRepository;
    }

    public function __invoke(OrganizationalStructureUpdateRequest $request, int $organizationalStructureId)
    {
        // Gate::authorize('updateOrganizationalStructure');

        try {
            $organizationalStructureId = new OrganizationalStructureId($organizationalStructureId ?? null);
            $organizationalStructureUpdateUseCase = new OrganizationalStructureUpdateUseCase($this->organizationalStructureRepository);
            $organizationalStructure = new OrganizationalStructureResource($organizationalStructureUpdateUseCase->__invoke($request, $organizationalStructureId));
            return $this->successResponse(
                $organizationalStructure,
                'La estructura organizacional fue actualizada exitosamente.',
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
