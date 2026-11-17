<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CIEChapter\CIEGroup;

use App\Application\CIEChapterUseCase\CIEGroupUseCase\CIEGroupFindUseCase;
use App\Domain\CIEChapter\CIEGroup\Values\CIEGroupId;
use App\Http\Resources\CIEChapter\CIEGroup\CIEGroupResource;
use App\Infrastructure\Repositories\CIEGroupRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CIEGroupFindController
{
    use ApiResponser;
    use ApiMessage;
    private $cieGroupRepository;

    public function __construct(CIEGroupRepository $cieGroupRepository)
    {
        $this->cieGroupRepository = $cieGroupRepository;
    }

    public function __invoke(int $cieGroupId)
    {
        try {
            $cieGroupId = new CIEGroupId($cieGroupId ?? null);
            $cieGroupFindUseCase = new CIEGroupFindUseCase($this->cieGroupRepository);
            $cieGroup = new CIEGroupResource($cieGroupFindUseCase->__invoke($cieGroupId));
            return $this->successResponse(
                $cieGroup,
                'El grupo de CIE - Clasificación Internacional de Enfermedades consultados fue encontrada exitosamente.',
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
