<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\ExamPolygraph;

use App\Application\VacantUseCase\ApplicationUseCase\ExamPolygraphUseCase\ExamPolygraphFindUseCase;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphId;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphFindRequest;
use App\Http\Resources\Vacant\Application\ExamPolygraph\ExamPolygraphResource;
use App\Infrastructure\Repositories\ExamPolygraphRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ExamPolygraphFindController
{
    use ApiResponser;
    use ApiMessage;
    private $examPolygraphRepository;

    public function __construct(ExamPolygraphRepository $examPolygraphRepository)
    {
        $this->examPolygraphRepository = $examPolygraphRepository;
    }

    public function __invoke(ExamPolygraphFindRequest $request, int|null $examPolygraphId = null)
    {
        try {
            $examPolygraphId = new ExamPolygraphId($examPolygraphId ?? null);
            $examPolygraphFindUseCase = new ExamPolygraphFindUseCase($this->examPolygraphRepository);
            $examPolygraph = new ExamPolygraphResource($examPolygraphFindUseCase->__invoke($request, $examPolygraphId));
            return $this->successResponse(
                $examPolygraph,
                'El agendamiento/resultado del examen polígrafo fue encontrada exitosamente.',
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
