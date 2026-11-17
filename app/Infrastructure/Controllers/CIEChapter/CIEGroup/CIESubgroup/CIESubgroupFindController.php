<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CIEChapter\CIEGroup\CIESubgroup;

use App\Application\CIEChapterUseCase\CIEGroupUseCase\CIESubgroupUseCase\CIESubgroupFindUseCase;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;
use App\Http\Resources\CIEChapter\CIEGroup\CIESubgroup\CIESubgroupResource;
use App\Infrastructure\Repositories\CIESubgroupRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CIESubgroupFindController
{
    use ApiResponser;
    use ApiMessage;
    private $cieSubgroupRepository;

    public function __construct(CIESubgroupRepository $cieSubgroupRepository)
    {
        $this->cieSubgroupRepository = $cieSubgroupRepository;
    }

    public function __invoke(int $cieSubgroupId)
    {
        try {
            $cieSubgroupId = new CIESubgroupId($cieSubgroupId ?? null);
            $cieSubgroupFindUseCase = new CIESubgroupFindUseCase($this->cieSubgroupRepository);
            $cieSubgroup = new CIESubgroupResource($cieSubgroupFindUseCase->__invoke($cieSubgroupId));
            return $this->successResponse(
                $cieSubgroup,
                'El subgrupo de CIE - Clasificación Internacional de Enfermedades consultados fue encontrada exitosamente.',
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
