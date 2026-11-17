<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CIEChapter\CIEGroup\CIESubgroup\CIECode;

use App\Application\CIEChapterUseCase\CIEGroupUseCase\CIESubgroupUseCase\CIECodeUseCase\CIECodeAllUseCase;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\Values\CIESubgroupId;
use App\Http\Requests\CIEChapter\CIEGroup\CIESubgroup\CIECode\CIECodeAllRequest;
use App\Http\Resources\CIEChapter\CIEGroup\CIESubgroup\CIECode\CIECodeCollection;
use App\Infrastructure\Repositories\CIECodeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CIECodeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $cieCodeRepository;

    public function __construct(CIECodeRepository $cieCodeRepository)
    {
        $this->cieCodeRepository = $cieCodeRepository;
    }

    public function __invoke(CIECodeAllRequest $request, int|null $cieSubgroupId = null)
    {
        try {
            $cieSubgroupId = new CIESubgroupId($cieSubgroupId);
            $cieCodeAllUseCase = new CIECodeAllUseCase($this->cieCodeRepository);
            $cieCodes = new CIECodeCollection($cieCodeAllUseCase->__invoke($request, $cieSubgroupId));
            return $this->successResponse(
                $cieCodes,
                'Los codigos de CIE - Clasificación Internacional de Enfermedades consultados exitosamente.',
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
