<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CIEChapter\CIEGroup;

use App\Application\CIEChapterUseCase\CIEGroupUseCase\CIEGroupAllUseCase;
use App\Domain\CIEChapter\Values\CIEChapterId;
use App\Http\Requests\CIEChapter\CIEGroup\CIEGroupAllRequest;
use App\Http\Resources\CIEChapter\CIEGroup\CIEGroupCollection;
use App\Infrastructure\Repositories\CIEGroupRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CIEGroupAllController
{
    use ApiResponser;
    use ApiMessage;
    private $cieGroupRepository;

    public function __construct(CIEGroupRepository $cieGroupRepository)
    {
        $this->cieGroupRepository = $cieGroupRepository;
    }

    public function __invoke(CIEGroupAllRequest $request, int|null $cieChapterId = null)
    {
        try {
            $cieChapterId = new CIEChapterId($cieChapterId);
            $cieGroupAllUseCase = new CIEGroupAllUseCase($this->cieGroupRepository);
            $cieGroups = new CIEGroupCollection($cieGroupAllUseCase->__invoke($request, $cieChapterId));
            return $this->successResponse(
                $cieGroups,
                'Los grupos de CIE - Clasificación Internacional de Enfermedades consultados exitosamente.',
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
