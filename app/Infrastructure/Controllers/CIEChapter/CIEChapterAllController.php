<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\CIEChapter;

use App\Application\CIEChapterUseCase\CIEChapterAllUseCase;
use App\Http\Requests\CIEChapter\CIEChapterAllRequest;
use App\Http\Resources\CIEChapter\CIEChapterCollection;
use App\Infrastructure\Repositories\CIEChapterRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CIEChapterAllController
{
    use ApiResponser;
    use ApiMessage;
    private $cieChapterRepository;

    public function __construct(CIEChapterRepository $cieChapterRepository)
    {
        $this->cieChapterRepository = $cieChapterRepository;
    }

    public function __invoke(CIEChapterAllRequest $request)
    {
        try {
            $cieChapterAllUseCase = new CIEChapterAllUseCase($this->cieChapterRepository);
            $cieChapters = new CIEChapterCollection($cieChapterAllUseCase->__invoke($request));
            return $this->successResponse(
                $cieChapters,
                'Los capitulos de CIE - Clasificación Internacional de Enfermedades consultados exitosamente.',
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
