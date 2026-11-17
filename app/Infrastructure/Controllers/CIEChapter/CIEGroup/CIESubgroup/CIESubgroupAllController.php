<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CIEChapter\CIEGroup\CIESubgroup;

use App\Application\CIEChapterUseCase\CIEGroupUseCase\CIESubgroupUseCase\CIESubgroupAllUseCase;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;
use App\Http\Requests\CIEChapter\CIEGroup\CIESubgroup\CIESubgroupAllRequest;
use App\Http\Resources\CIEChapter\CIEGroup\CIESubgroup\CIESubgroupCollection;
use App\Infrastructure\Repositories\CIESubgroupRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CIESubgroupAllController
{
    use ApiResponser;
    use ApiMessage;
    private $cieSubgroupRepository;

    public function __construct(CIESubgroupRepository $cieSubgroupRepository)
    {
        $this->cieSubgroupRepository = $cieSubgroupRepository;
    }

    public function __invoke(CIESubgroupAllRequest $request, int|null $cieGroupId = null)
    {
        try {
            $cieGroupId = new CIEGroupId($cieGroupId);
            $cieSubgroupAllUseCase = new CIESubgroupAllUseCase($this->cieSubgroupRepository);
            $cieSubgroups = new CIESubgroupCollection($cieSubgroupAllUseCase->__invoke($request, $cieGroupId));
            return $this->successResponse(
                $cieSubgroups,
                'Los subgrupos de CIE - Clasificación Internacional de Enfermedades consultados exitosamente.',
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
