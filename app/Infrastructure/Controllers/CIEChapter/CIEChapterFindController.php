<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CIEChapter;

use App\Application\CIEChapterUseCase\CIEChapterFindUseCase;
use App\Domain\CIEChapter\Values\CIEChapterId;
use App\Http\Resources\CIEChapter\CIEChapterResource;
use App\Infrastructure\Repositories\CIEChapterRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CIEChapterFindController
{
    use ApiResponser;
    use ApiMessage;
    private $cieChapterRepository;

    public function __construct(CIEChapterRepository $cieChapterRepository)
    {
        $this->cieChapterRepository = $cieChapterRepository;
    }

    public function __invoke(int $cieChapterId)
    {
        try {
            $cieChapterId = new CIEChapterId($cieChapterId ?? null);
            $cieChapterFindUseCase = new CIEChapterFindUseCase($this->cieChapterRepository);
            $cieChapter = new CIEChapterResource($cieChapterFindUseCase->__invoke($cieChapterId));
            return $this->successResponse(
                $cieChapter,
                'El capitulo de CIE - Clasificación Internacional de Enfermedades consultados fue encontrada exitosamente.',
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
