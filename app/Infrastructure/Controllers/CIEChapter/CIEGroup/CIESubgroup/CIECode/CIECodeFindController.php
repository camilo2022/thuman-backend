<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CIEChapter\CIEGroup\CIESubgroup\CIECode;

use App\Application\CIEChapterUseCase\CIEGroupUseCase\CIESubgroupUseCase\CIECodeUseCase\CIECodeFindUseCase;
use App\Domain\CIEChapter\CIEGroup\CIESubgroup\CIECode\Values\CIECodeId;
use App\Http\Resources\CIEChapter\CIEGroup\CIESubgroup\CIECode\CIECodeResource;
use App\Infrastructure\Repositories\CIECodeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CIECodeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $cieCodeRepository;

    public function __construct(CIECodeRepository $cieCodeRepository)
    {
        $this->cieCodeRepository = $cieCodeRepository;
    }

    public function __invoke(int $cieCodeId)
    {
        try {
            $cieCodeId = new CIECodeId($cieCodeId ?? null);
            $cieCodeFindUseCase = new CIECodeFindUseCase($this->cieCodeRepository);
            $cieCode = new CIECodeResource($cieCodeFindUseCase->__invoke($cieCodeId));
            return $this->successResponse(
                $cieCode,
                'El codigo de CIE - Clasificación Internacional de Enfermedades consultados fue encontrada exitosamente.',
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
